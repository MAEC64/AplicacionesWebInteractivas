/* Módulo de favoritos: guarda los IDs de películas marcadas en localStorage
y expone funciones auxiliares usadas por app.js y detalle.js.*/

const CLAVE_FAVORITOS = 'movieReviews_favoritos';

/*
 Lee la lista de IDs favoritos guardados en localStorage.
 */
function obtenerFavoritos() {
  const crudo = localStorage.getItem(CLAVE_FAVORITOS);
  return crudo ? JSON.parse(crudo) : [];
}

/*
Sobrescribe la lista de favoritos en localStorage.
 */
function persistirFavoritos(listaIds) {
  localStorage.setItem(CLAVE_FAVORITOS, JSON.stringify(listaIds));
}

function esPeliculaFavorita(id) {
  return obtenerFavoritos().includes(id);
}

/*
 Agrega o quita un ID de la lista de favoritos según su estado actual.
 Devuelve el nuevo estado (true = quedó marcada como favorita).
 */
function alternarFavorito(id) {
  const favoritosPrevios = obtenerFavoritos();
  const yaEstaba = favoritosPrevios.includes(id);

  const nuevaLista = yaEstaba
    ? favoritosPrevios.filter(idGuardado => idGuardado !== id)
    : [...favoritosPrevios, id];

  persistirFavoritos(nuevaLista);
  refrescarContadorFavoritos();
  return esPeliculaFavorita(id);
}

function quitarFavorito(id) {
  const listaSinEsePelicula = obtenerFavoritos().filter(idGuardado => idGuardado !== id);
  persistirFavoritos(listaSinEsePelicula);
  refrescarContadorFavoritos();
}

function vaciarFavoritos() {
  persistirFavoritos([]);
  refrescarContadorFavoritos();
}

function refrescarContadorFavoritos() {
  $('#favoritosCount').text(obtenerFavoritos().length);
}

/*
 Convierte un rating sobre 10 en un puñado de iconos de estrella (sobre 5).
 */
function pintarEstrellas(rating) {
  const totalEstrellas = Math.round((rating || 0) / 2);
  let iconosHtml = '';
  for (let posicion = 1; posicion <= 5; posicion++) {
    iconosHtml += posicion <= totalEstrellas
      ? '<i class="bi bi-star-fill"></i>'
      : '<i class="bi bi-star"></i>';
  }
  return iconosHtml;
}

/*
 Construye el contenido del modal de favoritos.
 Cruza los IDs guardados en localStorage con el catálogo cargado en la
 página actual para poder mostrar póster, título y año de cada favorito.
 */
function pintarModalFavoritos(catalogoDisponible) {
  const idsFavoritos = obtenerFavoritos();
  const $cuerpoModal = $('#favoritosModalBody');
  $('#favoritosModalCount').text(idsFavoritos.length);

  if (idsFavoritos.length === 0) {
    $cuerpoModal.html(`
      <div class="text-center py-4">
        <i class="bi bi-heart display-4 text-secondary"></i>
        <p class="mt-3 mb-1">No tienes películas favoritas</p>
        <p class="text-muted small">Agrega algunas desde la página de inicio</p>
      </div>
    `);
    return;
  }

  const peliculasFavoritas = catalogoDisponible.filter(peli => idsFavoritos.includes(peli.id));

  const tarjetasHtml = peliculasFavoritas.map(peli => {
    const posterUrl = peli.primaryImage || 'https://via.placeholder.com/150x220?text=Sin+imagen';
    return `
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <img src="${posterUrl}" class="card-img-top fav-mini-img" alt="${peli.primaryTitle}">
          <div class="card-body p-2">
            <p class="small fw-bold mb-1 text-truncate">${peli.primaryTitle}</p>
            <p class="small text-muted mb-2">${peli.startYear || ''} &middot; ${(peli.averageRating || 0)} <i class="bi bi-star-fill text-warning"></i></p>
            <div class="d-flex gap-1">
              <a href="resena.html?id=${peli.id}" class="btn btn-primary btn-sm flex-fill">Ver</a>
              <button class="btn btn-outline-danger btn-sm eliminar-favorito" data-id="${peli.id}"><i class="bi bi-x"></i></button>
            </div>
          </div>
        </div>
      </div>
    `;
  }).join('');

  $cuerpoModal.html(`<div class="row g-3">${tarjetasHtml}</div>`);
}
