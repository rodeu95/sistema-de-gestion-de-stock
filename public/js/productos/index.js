
// document.addEventListener('DOMContentLoaded', function () {
//     const grid = new grid.js.Grid({
//         columns: ['Código', 'Nombre', 'Fecha de vencimiento', 'Precio de Venta', 'Stock', 'Acciones'],
//         server:{
//             url: "{{ url('/api/productos') }}",
//             then: data => data.results.map(producto => [
//                 producto.codigo,
//                 producto.nombre,
//                 producto.fchVto,
//                 producto.precio_venta,
//                 producto.stock,

//             ])
//         } 
//     }).render(document.getElementById('gridjs-table'));
// });

function confirmDelete(productId) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "No podrás volver atrás",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar de todas formas",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + productId).submit();
        }
    });
}

// AJAX for Adding Product
$('#addProductForm').on('submit', function(e) {
    e.preventDefault();
 
    $.ajax({
        url: productosIndexUrl,
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
            $('#addProductModal').modal('hide'); // Close modal
            $('#addProductForm')[0].reset(); // Reset form

            Swal.fire({
                icon: 'success',
                title: '¡Producto agregado!',
                text: 'El producto se ha agregado correctamente.',
                confirmButtonText: 'OK'
            });

            // Reload product list (assuming there's a route that returns HTML for the product table)
            renderProductTable()
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText); // Muestra los errores de la respuesta
        }
    });
});

// $('#editProductForm').on('submit', function(e) {
//     e.preventDefault();
 
//     $.ajax({
//         url: productosIndexUrl,
//         method: "PUT",
//         data: $(this).serialize(),
//         success: function(response) {
//             $('#editProductModal').modal('hide'); // Close modal
//             $('#editProductForm')[0].reset(); // Reset form

//             Swal.fire({
//                 icon: 'success',
//                 title: '¡Producto actualizado!',
//                 text: 'El producto se ha actualizado correctamente.',
//                 confirmButtonText: 'OK'
//             });

//             renderProductTable();
//             // $.get(productosIndexUrl, function(data) {
//             //     $('#product-list').html($(data).find('#product-list').html()); // Update the table body
//             // });
//             console.log(response);
//         },
//         error: function(xhr, status, error) {
//             console.log(xhr.responseText); // Muestra los errores de la respuesta
//         }
//     });
// });

document.getElementById('precioCosto').addEventListener('input', updatePrecioVenta);
document.getElementById('utilidad').addEventListener('input', updatePrecioVenta);

function updatePrecioVenta() {
    const precioCosto = parseFloat(document.getElementById('precioCosto').value);
    const iva = 21 / 100; // IVA predeterminado
    const utilidad = parseFloat(document.getElementById('utilidad').value) / 100;

    if (!isNaN(precioCosto) && !isNaN(utilidad)) {
        const precioVenta = precioCosto * (1 + iva + utilidad);
        document.getElementById('precioVenta').value = precioVenta.toFixed(2);
    }
}
function updateStockStep() {
    const unidad = document.getElementById('unidad').value;
    const stockInput = document.getElementById('stock');

    if (unidad === 'KG') {
        stockInput.step = '0.01';
    } else {
        stockInput.step = '1';
    }
}



