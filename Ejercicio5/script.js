$(document).ready(function () {
  inicializarEfectoHero();
  inicializarScrollSuave();
  inicializarCotizador();
});

function inicializarEfectoHero() {
  // Oculta el contenido del hero y lo hace aparecer con un fundido al cargar la página
  $('#rrHeroContenido').hide().fadeIn(700);
}

/*Scroll suave al hacer clic en enlaces internos*/
function inicializarScrollSuave() {
  $('a.nav-link, a[href^="#cotizador"]').on('click', function (event) {
    const destino = $(this).attr('href');

    if (destino.startsWith('#') && $(destino).length) {
      event.preventDefault();

      $('html, body').animate(
        { scrollTop: $(destino).offset().top - 70 },
        500
      );

      // Si el menú móvil está abierto, lo cierra después de navegar
      $('#menuPrincipal').collapse('hide');
    }
  });
}

/*Cotizador rápido*/
const PRECIOS = {
  recapeo: 450,
  retrobright: 300,
  limpieza: 250
};

const DIAGNOSTICO_BASE = 100;

function inicializarCotizador() {
  // Recalcula automáticamente al cambiar cualquier opción
  $('#rrConsola, #rrRecapeo, #rrRetrobright, #rrLimpieza').on('change', calcularEstimado);

  // El botón sirve como confirmación visual, con un pequeño efecto de resalte
  $('#rrCalcular').on('click', function () {
    calcularEstimado();
    resaltarTotal();
  });

  calcularEstimado();
}

function calcularEstimado() {
  let total = DIAGNOSTICO_BASE;

  if ($('#rrRecapeo').is(':checked')) total += PRECIOS.recapeo;
  if ($('#rrRetrobright').is(':checked')) total += PRECIOS.retrobright;
  if ($('#rrLimpieza').is(':checked')) total += PRECIOS.limpieza;

  $('#rrTotal').text(total.toLocaleString('es-MX'));
  $('#rrResumenConsola').text($('#rrConsola').val());
}

function resaltarTotal() {
  // Efecto sencillo: el total se desvanece y vuelve a aparecer para llamar la atención
  $('#rrTotalDisplay').fadeTo(120, 0.2).fadeTo(200, 1);
}
