document.addEventListener('DOMContentLoaded', function () {
  inicializarBotonesCarrito();
});

function inicializarBotonesCarrito() {
  const botonesAgregar = document.querySelectorAll('.btn-agregar');

  botonesAgregar.forEach(function (boton) {
    boton.addEventListener('click', function (event) {
      const tarjeta = boton.closest('.card');
      const nombreProducto = tarjeta.querySelector('h3').textContent;

      mostrarMensajeCarrito(boton, nombreProducto);
    });
  });
}

function mostrarMensajeCarrito(boton, nombreProducto) {
  console.log('Producto agregado al carrito (simulado):', nombreProducto);

  const textoOriginal = boton.textContent;

  boton.textContent = '¡Agregado! ✔';
  boton.disabled = true;
  boton.classList.remove('btn-outline-primary');
  boton.classList.add('btn-success');

  setTimeout(function () {
    boton.textContent = textoOriginal;
    boton.disabled = false;
    boton.classList.remove('btn-success');
    boton.classList.add('btn-outline-primary');
  }, 1500);
}
