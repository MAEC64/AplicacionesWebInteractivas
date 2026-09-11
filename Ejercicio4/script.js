document.addEventListener('DOMContentLoaded', function () {
  inicializarCotizador();
});

const PRECIOS = {
  recapeo: 450,
  retrobright: 300,
  limpieza: 250
};

const DIAGNOSTICO_BASE = 100;

function inicializarCotizador() {
  const selectConsola = document.getElementById('rrConsola');
  const checkRecapeo = document.getElementById('rrRecapeo');
  const checkRetrobright = document.getElementById('rrRetrobright');
  const checkLimpieza = document.getElementById('rrLimpieza');
  const botonCalcular = document.getElementById('rrCalcular');

  if (!botonCalcular) return;

  botonCalcular.addEventListener('click', function () {
    calcularEstimado(selectConsola, checkRecapeo, checkRetrobright, checkLimpieza);
  });

  /*También recalcula automáticamente al cambiar cualquier opción,
  el botón sirve como confirmación visual para quien prefiera dar clic.*/
  [selectConsola, checkRecapeo, checkRetrobright, checkLimpieza].forEach(function (campo) {
    campo.addEventListener('change', function () {
      calcularEstimado(selectConsola, checkRecapeo, checkRetrobright, checkLimpieza);
    });
  });

  calcularEstimado(selectConsola, checkRecapeo, checkRetrobright, checkLimpieza);
}

function calcularEstimado(selectConsola, checkRecapeo, checkRetrobright, checkLimpieza) {
  let total = DIAGNOSTICO_BASE;

  if (checkRecapeo.checked) total += PRECIOS.recapeo;
  if (checkRetrobright.checked) total += PRECIOS.retrobright;
  if (checkLimpieza.checked) total += PRECIOS.limpieza;

  document.getElementById('rrTotal').textContent = total.toLocaleString('es-MX');
  document.getElementById('rrResumenConsola').textContent = selectConsola.value;
}
