document.addEventListener('DOMContentLoaded', function () {
  inicializarBotonesCarrito();
  inicializarFiltrosSidebar();
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

/* --- Filtros de la sidebar (categoría y precio) --- */

let categoriaActiva = 'todos';
let precioActivo = 'todos';

function inicializarFiltrosSidebar() {
  const botonesCategoria = document.querySelectorAll('#filtroCategorias .list-group-item');
  const botonesPrecio = document.querySelectorAll('#filtroPrecios .list-group-item');
  const botonLimpiar = document.getElementById('limpiarFiltros');

  botonesCategoria.forEach(function (boton) {
    boton.addEventListener('click', function () {
      activarBoton(botonesCategoria, boton);
      categoriaActiva = boton.dataset.categoria;
      aplicarFiltros();
    });
  });

  botonesPrecio.forEach(function (boton) {
    boton.addEventListener('click', function () {
      activarBoton(botonesPrecio, boton);
      precioActivo = boton.dataset.precio;
      aplicarFiltros();
    });
  });

  botonLimpiar.addEventListener('click', function () {
    categoriaActiva = 'todos';
    precioActivo = 'todos';
    activarBoton(botonesCategoria, botonesCategoria[0]);
    activarBoton(botonesPrecio, botonesPrecio[0]);
    aplicarFiltros();
  });

  aplicarFiltros();
}

function activarBoton(grupoBotones, botonSeleccionado) {
  grupoBotones.forEach(function (boton) {
    boton.classList.remove('active');
  });
  botonSeleccionado.classList.add('active');
}

function aplicarFiltros() {
  const productos = document.querySelectorAll('.producto-col');
  let visibles = 0;

  productos.forEach(function (producto) {
    const coincideCategoria = categoriaActiva === 'todos' || producto.dataset.categoria === categoriaActiva;
    const coincidePrecio = precioActivo === 'todos' || precioCoincide(Number(producto.dataset.precio), precioActivo);
    const mostrar = coincideCategoria && coincidePrecio;

    producto.classList.toggle('d-none', !mostrar);
    if (mostrar) visibles++;
  });

  document.getElementById('sinResultados').classList.toggle('d-none', visibles > 0);
  document.getElementById('contadorProductos').textContent =
    visibles + (visibles === 1 ? ' producto encontrado' : ' productos encontrados');
}

function precioCoincide(precio, rango) {
  const [min, max] = rango.split('-').map(Number);
  return precio >= min && precio <= max;
}
