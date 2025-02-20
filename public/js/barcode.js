let codigo = '';
let timeout;

function isVentasCreatePage() {
  return window.location.pathname === "/sistema/public/ventas/create";
}

function isProductosCreatePage() {
  return window.location.pathname === "/sistema/public/productos/create";
}

function isInventarioEditPage() {
  return window.location.pathname === "/sistema/public/inventario/edit";
}

// Escuchar eventos globales de teclado
document.addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    return;
  }
  // Concatenar la tecla presionada al código
  codigo += e.key;
  console.log(codigo);

  if (isVentasCreatePage() || isProductosCreatePage() || isInventarioEditPage()) {
    return; 
  }
  const modalAddProductAbierto = document.getElementById('addProductModal')?.classList.contains('show');

    if (modalAddProductAbierto) {
        return; // No ejecutar la lógica de escaneo si el modal está abierto
    }

  clearTimeout(timeout);

  timeout = setTimeout(() => {
    if (codigo.length >= 8 && codigo.length <= 13) { 
      console.log(`Código escaneado: ${codigo}`);

      // Solo mostrar el modal si no estamos en ventas.create
      if (!isVentasCreatePage()) {
        $('#barcodeModal').modal('show');
      }

      // Realizar la solicitud AJAX
      $.ajax({
        url: `http://localhost/sistema/public/api/productos/${codigo}`,
        method: 'GET',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function(response) {
            $('#producto-codigo').text(response.codigo || 'N/A');
            $('#producto-nombre').text(response.nombre || 'N/A');

            if (response.unidad === 'UN') {
                $('#producto-precio').text(`$${response.precio_venta} x UN` || 'N/A');
            } else {
                $('#producto-precio').text(`$${response.precio_venta} x KG` || 'N/A');
            }
            
            $('#producto-stock').text(response.stock || 'N/A');
        },
        error: function(xhr, status, error) {
            console.error(`Error: ${error}`);
            alert('Producto no encontrado. Por favor, verifica el código.');

            // Limpiar los campos del modal
            $('#producto-nombre').text('');
            $('#producto-precio').text('');
            $('#producto-stock').text('');
        }
      });
    }

    // Reiniciar el código después de procesarlo
    codigo = '';
  }, 200);
});
