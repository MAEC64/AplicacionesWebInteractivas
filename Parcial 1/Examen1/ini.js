/* Página de inicio: descarga el catálogo, aplica filtros de búsqueda/década
y dibuja la cuadrícula de tarjetas.*/

const ENDPOINT_TOP_RATED = 'https://api.imdbapi.dev/titles/top-rated';

let catalogoPeliculas = [];
let filtroDecadaActual = 'todos';

$(document).ready(function () {
  refrescarContadorFavoritos();
  obtenerCatalogo();
  registrarEventos();
});

/*
 Centraliza todo el "cableado" de eventos de la página, para que el
 bloque $(document).ready quede corto y sea fácil ver qué dispara qué.
 */
function registrarEventos() {
  $('#filtrosDecada button').on('click', function () {
    $('#filtrosDecada button').removeClass('active');
    $(this).addClass('active');
    filtroDecadaActual = $(this).data('decada');
    pintarCatalogo();
  });

  $('#buscador').on('keyup', function () {
    pintarCatalogo();
  });

  $('#formBuscador').on('submit', function (evento) {
    evento.preventDefault();
  });

  $('#btnReintentar').on('click', obtenerCatalogo);

  $('#btnFavoritos').on('click', function () {
    pintarModalFavoritos(catalogoPeliculas);
    $('#favoritosModal').modal('show');
  });

  $('#btnEliminarTodos').on('click', function () {
    vaciarFavoritos();
    pintarModalFavoritos(catalogoPeliculas);
    pintarCatalogo();
  });

  $(document).on('click', '.eliminar-favorito', function () {
    const idPelicula = $(this).data('id');
    quitarFavorito(idPelicula);
    pintarModalFavoritos(catalogoPeliculas);
    pintarCatalogo();
  });

  $(document).on('click', '.favorite-btn', function (evento) {
    evento.preventDefault();
    evento.stopPropagation();
    const idPelicula = $(this).data('id');
    const quedoActivo = alternarFavorito(idPelicula);
    $(this).toggleClass('active', quedoActivo)
      .toggleClass('bi-heart', !quedoActivo)
      .toggleClass('bi-heart-fill', quedoActivo);
  });
}

/*
 Descarga el catálogo desde la API principal y, si falla (caída del
 servicio, timeout, etc.), recurre al archivo local peliculas.json como
 respaldo antes de mostrar el mensaje de error definitivo.
 */
function obtenerCatalogo() {
  $('#errorContainer').addClass('d-none');
  $('#loadingSpinner').removeClass('d-none');
  $('#moviesGrid').empty();

  $.ajax({
    url: ENDPOINT_TOP_RATED,
    method: 'GET',
    dataType: 'json',
    timeout: 5000
  })
    .done(function (respuesta) {
      catalogoPeliculas = Array.isArray(respuesta) ? respuesta : (respuesta.titles || []);
      if (!catalogoPeliculas.length) throw new Error('Respuesta vacía');
      onCatalogoListo();
    })
    .fail(function () {
      $.getJSON('peliculas.json')
        .done(function (datosLocales) {
          catalogoPeliculas = datosLocales;
          onCatalogoListo();
        })
        .fail(function () {
          $('#loadingSpinner').addClass('d-none');
          $('#errorContainer').removeClass('d-none');
        });
    });
}

function onCatalogoListo() {
  $('#loadingSpinner').addClass('d-none');
  pintarCatalogo();
}

function anioDentroDeDecada(anio, decada) {
  if (decada === 'todos') return true;
  const [inicio, fin] = decada.split('-').map(Number);
  return anio >= inicio && anio <= fin;
}

/*
 Filtra el catálogo en memoria según el texto buscado y la década activa,
 y reconstruye por completo la cuadrícula de tarjetas.
 */
function pintarCatalogo() {
  const textoBuscado = $('#buscador').val().toLowerCase().trim();
  const $contenedorGrid = $('#moviesGrid');
  $contenedorGrid.empty();

  const peliculasFiltradas = catalogoPeliculas.filter(peli => {
    const coincideTexto = (peli.primaryTitle || '').toLowerCase().includes(textoBuscado);
    const coincideDecada = anioDentroDeDecada(peli.startYear, filtroDecadaActual);
    return coincideTexto && coincideDecada;
  });

  if (peliculasFiltradas.length === 0) {
    $contenedorGrid.html('<div class="col-12 text-center text-muted py-5">No se encontraron películas.</div>');
    return;
  }

  peliculasFiltradas.forEach(peli => {
    $contenedorGrid.append(construirTarjeta(peli));
  });

  $('.movie-card').hover(
    function () { $(this).addClass('card-hover'); },
    function () { $(this).removeClass('card-hover'); }
  );
}

/*
 Genera el markup de una sola tarjeta de película.
 */
function construirTarjeta(peli) {
  const posterUrl = peli.primaryImage || 'https://via.placeholder.com/300x445?text=Sin+imagen';
  const esFavorita = esPeliculaFavorita(peli.id);
  const claseCorazon = esFavorita ? 'bi-heart-fill' : 'bi-heart';

  return `
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card h-100 movie-card">
        <div class="poster-wrap">
          <img src="${posterUrl}" class="card-img-top" alt="${peli.primaryTitle}">
          <span class="rating-badge"><i class="bi bi-star-fill"></i> ${peli.averageRating || 'N/A'}</span>
          <span class="year-badge">${peli.startYear || 'N/A'}</span>
        </div>
        <div class="card-body d-flex flex-column">
          <div class="d-flex justify-content-between align-items-start gap-2">
            <h6 class="card-title mb-1">${peli.primaryTitle}</h6>
            <i class="bi ${claseCorazon} favorite-btn ${esFavorita ? 'active' : ''}" data-id="${peli.id}"></i>
          </div>
          <div class="mb-3 small">${pintarEstrellas(peli.averageRating)}</div>
          <a href="resena.html?id=${peli.id}" class="btn btn-primary mt-auto">Ver reseña</a>
        </div>
      </div>
    </div>
  `;
}
