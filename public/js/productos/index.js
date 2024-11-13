let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let grid;

document.addEventListener('DOMContentLoaded', function () {

    // Configuración inicial para el renderizado de la tabla de productos
    function renderProductTable() {

        if (grid) {
            grid.destroy();
        }

        grid = new gridjs.Grid({
            columns: [
                'Código',
                {
                    name: 'Nombre',
                    sort: true,
                    formatter: (cell) => cell,
                    compare: (a, b) => a.toLowerCase().localeCompare(b.toLowerCase())
                },
                'Fecha de vencimiento',
                {
                    name: 'Precio de Venta',
                    formatter: (cell) => {
                        // Asegúrate de que el valor sea un número antes de formatearlo
                        const amount = parseFloat(cell);
                        // Si no es un número válido, retorna el valor original
                        if (isNaN(amount)) return cell;
                        // Devuelve el valor con el símbolo '$' y lo formatea como una moneda
                        return '$' + amount.toFixed(2); // Esto agrega dos decimales, cambia según lo necesites
                    }
                },
                'Stock',
                'Unidad',
                {
                    name: 'Acciones',
                    formatter: (cell, row) => {

                        const codigo = row.cell(0).data;

                        const editButtonHtml = document.getElementById('editButtonTemplate').innerHTML.replace('${codigo}', codigo);
                        const deleteButtonHtml = document.getElementById('deleteButtonTemplate').innerHTML.replace('${codigo}', codigo);

                        return gridjs.html(`
                            <form id="delete-form-${codigo}" action="/sistema/public/productos/${codigo}" method="post">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                ${editButtonHtml} ${deleteButtonHtml}
                            </form>
                        `);
                    }
                }
            ],
            server: {
                url: productosIndexUrl,
                then: data => {
                    return data.map(producto => [
                        producto.codigo,
                        producto.nombre,
                        producto.fchVto,
                        parseFloat(producto.precio_venta),
                        producto.unidad === 'UN'
                            ? parseInt(producto.stock)  // Convierte el stock a número y muestra 'unidades'
                            : parseFloat(producto.stock),
                        producto.unidad,
                    ]);
                }
            },
            resizable: true,
            sort: true,

            pagination: {
                enabled: true,
                limit: 10,
            },
            search: true,
            language: {
                search: {
                    placeholder: 'Buscar...'
                },
                pagination: {
                    previous: 'Anterior',
                    next: 'Siguiente',
                    showing: 'Mostrando',
                    of: 'de',
                    to: 'a'
                }
            },
            style: {
                th: {
                    'background-color': '#fff3cd'
                }
            },
        }).render(document.getElementById('gridjs-table'));
    }

    // Llamar a renderProductTable cuando se carga la página
    renderProductTable();

    $('#editProductModal').on('show.bs.modal', function (event) {
        /*CARGA DATOS DEL PRODUCTO EN EL MODAL DE EDICION*/
        const button = $(event.relatedTarget);
        const codigo = button.data('codigo');
        let editProductUrl = editProductUrlTemplate.replace(':codigo', codigo);
        console.log(editProductUrl);

        $.ajax({
            url: editProductUrl, // Ruta para obtener los datos del producto
            method: 'GET',
            success: function (data) {

                const modal = $('#editProductModal');
                // Rellenar el formulario con los datos del producto
                modal.find('#edit_codigo').val(data.codigo);
                modal.find('#edit_nombre').val(data.nombre);
                modal.find('#edit_descripcion').val(data.descripcion);
                modal.find('#edit_unidad').val(data.unidad);
                modal.find('#edit_precioVenta').val(data.precio_venta);
                modal.find('#edit_stock').val(data.stock);
                modal.find('#edit_numero_lote').val(data.numero_lote);
                modal.find('#edit_fchVto').val(data.fchVto);
                modal.find('#edit_categoria_id').val(data.categoria_id);
            }
        });
    })

    $('#editProductForm').on('submit', function (e) {
        /*EDICION DEL PRODUCTO DEL MODAL */
        e.preventDefault();
        const formData = $(this).serialize();
        
        const codigo = $('#edit_codigo').val();
        let productoUpdatetUrlFinal = productoUpdatetUrl.replace("codigo", codigo);
        console.log(productoUpdatetUrlFinal);
        $.ajax({
            url: productoUpdatetUrlFinal, // URL del formulario establecida dinámicamente
            method: 'PUT',
            data: formData,
            success: function (response) {
                $('#editProductModal').modal('hide'); // Cierra el modal                
                window.location.reload()
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }
        });
    });



    // Función para manejar el formulario de agregar un producto mediante AJAX
    $('#addProductForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: productosStoreUrl,
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                $('#addProductModal').modal('hide'); // Cerrar el modal
                $('#addProductForm')[0].reset(); // Resetear el formulario

                Swal.fire({
                    icon: 'success',
                    title: '¡Producto agregado!',
                    text: 'El producto se ha agregado correctamente.',
                    confirmButtonText: 'OK'
                });

                window.location.reload();
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }
        });
    });

    $('#editProductModal').on('hidden.bs.modal', function () {
        $('#editProductForm')[0].reset();
        productoEditUrl = "{{ route('productos.update', 'codigo') }}";
    });



    // Función para actualizar el precio de venta basado en el costo y la utilidad
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

});


function deleteProducto(codigo) {
    // Muestra el SweetAlert antes de realizar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Este producto será eliminado de manera permanente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            const eliminarProductoUrlFinal = eliminarProductoUrl.replace("codigo", codigo);
            console.log(eliminarProductoUrlFinal);

            $.ajax({
                url: eliminarProductoUrlFinal,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.message === 'Producto eliminado exitosamente') {
                        Swal.fire(
                            'Eliminado',
                            'Producto eliminado exitosamente.',
                            'info'
                        ).then(function () {
                            window.location.reload();
                        });
                    } else {
                        alert('Error: ' + data.message); // Mensaje de error si no se encontró el producto
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error al eliminar el producto:', error);
                    alert('Hubo un problema al intentar eliminar el producto.');
                }
            });
        }
    });
}
$(document).on('click', '.btn-delete', function () {
    const codigo = $(this).data('codigo');
    deleteProducto(codigo);
});

function updateStockStep() {
    const unidad = document.getElementById('unidad').value;
    const stockInput = document.getElementById('stock');

    if (unidad === 'KG') {
        stockInput.step = '0.01';
    } else {
        stockInput.step = '1';
    }
}

// Ejecutar función de actualización de stock cuando se cambie la unidad
document.getElementById('unidad').addEventListener('change', updateStockStep);