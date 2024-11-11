// Espera que el DOM esté completamente cargado
let puedeEditar = true;
let puedeEliminar = true;
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
                        console.log(codigo);
                        
                        
                        const editButton = puedeEditar
                            ? `<a href="javascript:void(0);" type="button" class="btn shadow btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal" data-codigo="${codigo}" >
                                <i class="fa-solid fa-pen-to-square"></i></a>`
                            : '';
                            // onclick="editarProducto('${codigo}')"
                        const deleteButton = puedeEliminar
                            ? `<button type="button" class="btn shadow btn-danger btn-sm btn-delete" data-codigo="${codigo}"><i class="fa-solid fa-trash-can"></i></button>`
                            : '';
                            // onclick="confirmDelete(${codigo})"
                        return gridjs.html(`
                            <form id="delete-form-${codigo}" action="/sistema/public/productos/${codigo}" method="post">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                ${editButton} ${deleteButton}
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

    $('#editProductModal').on('show.bs.modal', function(event){
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
    
    $('#editProductForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        const codigo = $('#edit_codigo').val();
        let productoUpdatetUrlFinal = productoUpdatetUrl.replace("codigo", codigo);
        console.log(productoUpdatetUrlFinal);
        $.ajax({
            url: productoUpdatetUrlFinal, // URL del formulario establecida dinámicamente
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#editProductModal').modal('hide'); // Cierra el modal
    
                window.location.reload(); // Recarga la tabla de productos
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }
        });
    });
    

    
    // Función para manejar el formulario de agregar un producto mediante AJAX
    $('#addProductForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: productosStoreUrl,
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
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
            error: function(xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }
        });
    });

    $('#editProductModal').on('hidden.bs.modal', function() {
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


// function editarProducto(codigo) {

//     let editProductUrl = editProductUrlTemplate.replace(':codigo', codigo);
//     console.log(editProductUrl);
//     fetch(editProductUrl, { method: 'GET' })
//         .then(response => response.json())
//         .then(data => {
//             console.log(data);
//             // Cargar los datos en los campos del modal
//             document.getElementById('edit_codigo').value = data.codigo;
//             document.getElementById('edit_nombre').value = data.nombre;
//             document.getElementById('edit_unidad').value = data.unidad;
//             document.getElementById('edit_precioVenta').value = data.precio_venta;
//             document.getElementById('edit_numero_lote').value = data.numero_lote;
//             document.getElementById('edit_descripcion').value = data.descripcion;
//             document.getElementById('edit_stock').value = data.stock;
//             document.getElementById('edit_fchVto').value = data.fchVto;

//             // Mostrar el modal
//             $('#editProductModal').modal('show');
//         })
//         .catch(error => console.error('Error al cargar el producto:', error));
// }

// function submitEditarProducto(e) {
//     e.preventDefault();
//     const form = document.getElementById('editProductForm');
//     const formData = new FormData(form);
//     // const codigo = formData.get('codigo');
//     // const updateUrl = productoUpdatetUrl.replace(':codigo', codigo);

//     let dataObj = {};
//     formData.forEach((value, key) => {
//         dataObj[key] = value;
//     });

//     const updateUrl = productoUpdatetUrl.replace(':codigo', dataObj.codigo);
//     console.log(updateUrl);

//     fetch(updateUrl, {
//         method: 'POST',
//         body: JSON.stringify(dataObj),
//         headers: {
            
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//             'X-HTTP-Method-Override': 'PUT'
//         },
//     })
    
//     .then(response => {
//         console.log(response); // Verificar detalles de la respuesta
//         return response.json();
//     })
//     .then(data => {
//         if (data.success) {
//             $('#editProductModal').modal('hide');
            
//         }
//     })
//     .catch(error => console.error('Error al actualizar el producto:', error));
// }
// document.getElementById('editProductForm').addEventListener('submit', submitEditarProducto);


// Captura el evento de envío en el formulario de eliminación


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
                success: function(data) {
                    if (data.message === 'Producto eliminado exitosamente') {
                        Swal.fire(
                            'Eliminado',
                            'Producto eliminado exitosamente.',
                            'info'
                        ).then(function() {
                            window.location.reload(); // Recargar la página después de 2 segundos
                        });
                    } else {
                        alert('Error: ' + data.message); // Mensaje de error si no se encontró el producto
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar el producto:', error);
                    alert('Hubo un problema al intentar eliminar el producto.');
                }
            });
        } else {
            Swal.fire(
                'Cancelado',
                'El producto no ha sido eliminado.',
                'info'
            );
        }
    });
}
$(document).on('click', '.btn-delete', function() {
    const codigo = $(this).data('codigo');
    console.log(codigo); 
    deleteProducto(codigo); 
});
// function confirmDelete(productId) {
//     Swal.fire({
//         title: "¿Estás seguro?",
//         text: "No podrás volver atrás",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Eliminar de todas formas",
//         cancelButtonText: "Cancelar"
//     }).then((result) => {
//         if (result.isConfirmed) {
//             document.getElementById('delete-form-' + productId).submit();
//         }
//     });
// }


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