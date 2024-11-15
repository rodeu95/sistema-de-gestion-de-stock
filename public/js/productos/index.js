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
                {
                    // name: 'Código',
                    width: '130px',
                    name: gridjs.html(`<span title="Código único del producto">Código</span>`)
                },
                
                {
                    name: 'Producto',
                    width: '190px',
                    sort: true,
                    formatter: (cell) => cell,
                    compare: (a, b) => a.toLowerCase().localeCompare(b.toLowerCase())
                },
                {
                    // name: 'Fecha de vencimiento',
                    width: '160px',
                    name: gridjs.html(`<span title="Fecha de Vencimiento">Fecha de Vencimiento</span>`)
                },
                {
                    // name: 'Precio de Venta',
                    name: gridjs.html(`<span title="Precio de venta">Precio de venta</span>`),
                    resizable: true,
                    width: '130px',
                    formatter: (cell) => {
                        // Asegúrate de que el valor sea un número antes de formatearlo
                        const amount = parseFloat(cell);
                        // Si no es un número válido, retorna el valor original
                        if (isNaN(amount)) return cell;
                        // Devuelve el valor con el símbolo '$' y lo formatea como una moneda
                        return '$' + amount.toFixed(2); // Esto agrega dos decimales, cambia según lo necesites
                    }
                },
                {
                    name: gridjs.html(`<span title="Stock">Stock</span>`),
                    width: '80px'
                },
                // {
                //     name: gridjs.html(`<span title="Unidad">Unidad</span>`),
                //     width: '80px'
                // },
                {
                    name: gridjs.html(`<span title="Estado">Estado</span>`),
                    width: '80px'
                },
                {
                    name: 'Acciones',
                    width: '110px',
                    // width: '130px',
                    formatter: (cell, row) => {

                        const codigo = row.cell(0).data;
                        const estado = row.cell(5).data;
                        let buttonHtml = '';
                        if (estado === 'Inactivo') {
                            // Si está deshabilitado, mostrar el ícono de habilitar
                            buttonHtml = `
                                <button class="btn shadow btn-success btn-sm btn-enable" title="Habilitar producto" data-codigo=${codigo}>
                                    <i class="fa-solid fa-check-circle"></i>
                                </button>
                            `;
                        } else {
                            // Si está habilitado, mostrar el ícono de deshabilitar
                            buttonHtml = `
                                <button class="btn shadow btn-danger btn-sm btn-disable" title="Deshabilitar producto" data-codigo=${codigo}>
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            `;
                        }
                        

                        const editButtonHtml = document.getElementById('editButtonTemplate').innerHTML.replace('${codigo}', codigo);
                        // const disableButtonHtml = document.getElementById('disableButtonTemplate').innerHTML.replace('${codigo}', codigo);

                        return gridjs.html(`
                            <form id="delete-form-${codigo}" action="/sistema/public/productos/${codigo}/disable" method="PUT">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DISABLE">
                                ${editButtonHtml} ${buttonHtml}
                            </form>
                        `);
                    }
                }
            ],
            server: {
                url: productosIndexUrl,
                then: data => {
                    return data.map(producto => {
                        // Define el stock visual para unidades (UN) y kilogramos (KG)
                        const stockVisual = producto.unidad === 'UN' 
                            ? `${parseInt(producto.stock)} UN`  // Agrega 'un' para unidades
                            : `${parseFloat(producto.stock)} KG`;  // Agrega 'kg' para kilogramos
            
                        return [
                            producto.codigo,
                            producto.nombre,
                            producto.fchVto,
                            parseFloat(producto.precio_venta),
                            stockVisual, // Stock visualizado con 'un' o 'kg'
                            // producto.unidad,
                            producto.estado === 0 ? "Inactivo" : "Activo",
                            producto.stock, // Mantén el stock sin 'un' o 'kg' para poder ordenarlo correctamente
                        ];
                    });
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
                    'background-color': '#fff3cd',
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
                renderProductTable();
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }
        });
    });



    // Función para manejar el formulario de agregar un producto mediante AJAX
    $('#addProductForm').on('submit', function (e) {
        /*AGREGAR PRODUCTO POR MODAL*/
        e.preventDefault();

        $.ajax({
            url: productosStoreUrl,
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if(response.success){
                    $('#addProductModal').modal('hide'); // Cerrar el modal
                    $('#addProductForm')[0].reset(); // Resetear el formulario

                    Swal.fire({
                        icon: 'success',
                        title: '¡Producto agregado!',
                        text: 'El producto se ha agregado correctamente.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        renderProductTable(); // Recargar la página después de 2 segundos
                    });
                }
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

function disableProducto(codigo) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Este producto será deshabilitado y no aparecerá en el inventario.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, deshabilitar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            const disableProductoUrlFinal = disableProductoUrl.replace("codigo", codigo);
            console.log(disableProductoUrlFinal);
            $.ajax({
                url: disableProductoUrlFinal,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (data) {
                    if (data.message === 'Producto deshabilitado exitosamente') {
                        Swal.fire(
                            'Deshabilitado',
                            'Producto deshabilitado exitosamente.',
                            'info'
                        ).then(function () {
                            window.location.reload(); // Recargar la página
                        });
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error al deshabilitar el producto:', error);
                    alert('Hubo un problema al intentar deshabilitar el producto.');
                }
            });
        }
    });
}

function enableProducto(codigo) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Este producto será habilitado y reaparecerá en el inventario.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, habilitar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            const enableProductoUrlFinal = enableProductoUrl.replace("codigo", codigo);
            console.log(enableProductoUrlFinal);
            $.ajax({
                url: enableProductoUrlFinal,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (data) {
                    if (data.message === 'Producto deshabilitado exitosamente') {
                        Swal.fire(
                            'Habilitado',
                            'Producto habilitado exitosamente.',
                            'info'
                        )
                        .then(function () {
                            window.location.reload(); // Recargar la página
                        });
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error al deshabilitar el producto:', error);
                    alert('Hubo un problema al intentar deshabilitar el producto.');
                }
            });
        }
    });
}
// function deleteProducto(codigo) {
//     /*ELIMINADO DE PRODUCTO*/
//     // Muestra el SweetAlert antes de realizar la eliminación
//     Swal.fire({
//         title: '¿Estás seguro?',
//         text: 'Este producto será eliminado de manera permanente.',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonText: 'Sí, eliminar',
//         cancelButtonText: 'Cancelar',
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//     }).then((result) => {
//         if (result.isConfirmed) {
//             const eliminarProductoUrlFinal = eliminarProductoUrl.replace("codigo", codigo);
//             console.log("Eliminado de producto", eliminarProductoUrlFinal);

//             $.ajax({
//                 url: eliminarProductoUrlFinal,
//                 method: 'DELETE',
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                 success: function (data) {
//                     if (data.message === 'Producto eliminado exitosamente') {
//                         Swal.fire(
//                             'Eliminado',
//                             'Producto eliminado exitosamente.',
//                             'info'
//                         ).then(function () {
//                             window.location.reload();
//                         });
//                     } else {
//                         alert('Error: ' + data.message); // Mensaje de error si no se encontró el producto
//                     }
//                 },
//                 error: function (xhr, status, error) {
//                     console.error('Error al eliminar el producto:', error);
//                     alert('Hubo un problema al intentar eliminar el producto.');
//                 }
//             });
//         }
//     });
// }
$(document).on('click', '.btn-disable', function (e) {
    e.preventDefault();
    const codigo = $(this).data('codigo');
    console.log(disableProductoUrl.replace("codigo", codigo));
    disableProducto(codigo);
});
$(document).on('click', '.btn-enable', function (e) {
    e.preventDefault();
    const codigo = $(this).data('codigo');
    console.log(enableProductoUrl.replace("codigo", codigo));
    enableProducto(codigo);
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