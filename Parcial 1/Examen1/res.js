// Página de reseña: obtiene una película por ID y llena la ficha de detalle.

const ENDPOINT_DETALLE = 'https://api.imdbapi.dev/titles/';

let catalogoRespaldo = [];   // se llena solo si la API falla y se usa peliculas.json
let peliculaSeleccionada = null;

$(document).ready(function () {
  refrescarContadorFavoritos();
  registrarEventos();

  const parametrosUrl = new URLSearchParams(window.location.search);
  const idSolicitado = parametrosUrl.get('id');

  if (!idSolicitado) {
    mostrarErrorDetalle();
  } else {
    obtenerDetalle(idSolicitado);
  }
});

function registrarEventos() {
  $('#btnFavoritos').on('click', function () {
    if (catalogoRespaldo.length) {
      pintarModalFavoritos(catalogoRespaldo);
      $('#favoritosModal').modal('show');
      return;
    }
    // Si aún no se cargó ningún catálogo local, se descarga antes de abrir el modal.
    $.getJSON('peliculas.json').done(function (datosLocales) {
      catalogoRespaldo = datosLocales;
      pintarModalFavoritos(catalogoRespaldo);
    });
    $('#favoritosModal').modal('show');
  });

  $('#btnEliminarTodos').on('click', function () {
    vaciarFavoritos();
    pintarModalFavoritos(catalogoRespaldo);
    sincronizarBotonFavorito();
  });

  $(document).on('click', '.eliminar-favorito', function () {
    const idFavorito = $(this).data('id');
    quitarFavorito(idFavorito);
    pintarModalFavoritos(catalogoRespaldo);
    sincronizarBotonFavorito();
  });

  $('#btnFavoritoDetalle').on('click', function () {
    if (!peliculaSeleccionada) return;
    alternarFavorito(peliculaSeleccionada.id);
    sincronizarBotonFavorito();
  });
}

/*
 Pide la ficha completa de una película a la API por su ID. Si la API no
 responde, busca ese mismo ID dentro de peliculas.json como respaldo.
 */
function obtenerDetalle(idPelicula) {
  $('#loadingSpinner').removeClass('d-none');
  $('#detalleContainer').addClass('d-none');
  $('#errorContainer').addClass('d-none');

  $.ajax({
    url: ENDPOINT_DETALLE + idPelicula,
    method: 'GET',
    dataType: 'json',
    timeout: 5000
  })
    .done(function (datosApi) {
      peliculaSeleccionada = datosApi;
      pintarDetalle(datosApi);
    })
    .fail(function () {
      $.getJSON('peliculas.json')
        .done(function (datosLocales) {
          catalogoRespaldo = datosLocales;
          const encontrada = datosLocales.find(peli => peli.id === idPelicula);
          if (encontrada) {
            peliculaSeleccionada = encontrada;
            pintarDetalle(encontrada);
          } else {
            mostrarErrorDetalle();
          }
        })
        .fail(mostrarErrorDetalle);
    });
}

function mostrarErrorDetalle() {
  $('#loadingSpinner').addClass('d-none');
  $('#errorContainer').removeClass('d-none');
}

function formatearMoneda(valor) {
  if (!valor && valor !== 0) return 'N/A';
  return '$' + Number(valor).toLocaleString('en-US');
}

function construirBadges(lista, clase) {
  if (!lista || !lista.length) return '<span class="text-muted">N/A</span>';
  return lista.map(item => `<span class="badge ${clase} me-1 mb-1">${item}</span>`).join('');
}

/*
 Vuelca todos los campos de una película en los distintos elementos de
 la ficha (póster, rating, sinopsis, badges, metascore, enlaces, etc.).
 */
function pintarDetalle(peli) {
  $('#loadingSpinner').addClass('d-none');

  $('#poster').attr('src', peli.primaryImage || 'https://via.placeholder.com/350x520?text=Sin+imagen');
  $('#poster').attr('alt', peli.primaryTitle || '');
  $('#titulo').text(peli.primaryTitle || 'Sin título');
  $('#anio').html(`<i class="bi bi-calendar"></i> ${peli.startYear || 'N/A'}`);
  $('#duracion').text(peli.runtimeMinutes || 'N/A');
  $('#clasificacion').text(peli.contentRating || 'N/A');

  $('#rating').text(peli.averageRating || 'N/A');
  $('#estrellas').html(pintarEstrellas(peli.averageRating));
  $('#numVotos').text((peli.numVotes || 0).toLocaleString('en-US'));

  $('#generos').html(construirBadges(peli.genres, 'bg-primary'));
  $('#intereses').html(construirBadges(peli.interests, 'bg-secondary'));

  const metascore = peli.metascore;
  if (metascore || metascore === 0) {
    const colorBarra = metascore >= 70 ? 'bg-success' : (metascore >= 50 ? 'bg-warning' : 'bg-danger');
    $('#metascoreBar').css('width', metascore + '%').attr('class', 'progress-bar ' + colorBarra);
    $('#metascoreTexto').text(metascore + ' / 100');
  } else {
    $('#metascoreBar').css('width', '0%');
    $('#metascoreTexto').text('N/A');
  }

  $('#sinopsis').text(peli.description || 'Sin sinopsis disponible.');
  $('#idiomas').text((peli.spokenLanguages || []).join(', ') || 'N/A');
  $('#paises').text((peli.countriesOfOrigin || []).join(', ') || 'N/A');
  $('#presupuesto').text(formatearMoneda(peli.budget));
  $('#recaudacion').text(formatearMoneda(peli.grossWorldwide));

  const nombresProductoras = (peli.productionCompanies || []).map(empresa => empresa.name || empresa).join(', ');
  $('#productoras').text(nombresProductoras || 'N/A');

  if (peli.trailer) {
    $('#btnTrailer').attr('href', peli.trailer).removeClass('d-none');
  } else {
    $('#btnTrailer').addClass('d-none');
  }

  const $listaEnlaces = $('#enlacesExternos').empty();
  if (peli.externalLinks && peli.externalLinks.length) {
    peli.externalLinks.forEach(url => {
      $listaEnlaces.append(`<a href="${url}" target="_blank" class="d-block small">${url}</a>`);
    });
  } else {
    $listaEnlaces.text('N/A');
  }

  sincronizarBotonFavorito();

  $('#detalleContainer').removeClass('d-none').hide().fadeIn(400);
}

function sincronizarBotonFavorito() {
  if (!peliculaSeleccionada) return;
  const esFavorita = esPeliculaFavorita(peliculaSeleccionada.id);
  $('#btnFavoritoDetalle').toggleClass('btn-outline-danger', !esFavorita).toggleClass('btn-danger', esFavorita);
  $('#iconoFavoritoDetalle').toggleClass('bi-heart', !esFavorita).toggleClass('bi-heart-fill', esFavorita);
}
