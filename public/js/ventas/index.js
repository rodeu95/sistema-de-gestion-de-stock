
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let grid;
let initialTotal = 0;

document.addEventListener('DOMContentLoaded', function () {
    
    function renderVentasTable() {

        if (grid) {
            grid.destroy();
        }
        
        grid = new gridjs.Grid({
            columns: [
                'ID', 
                {
                    name: gridjs.html(`<span title="Productos">Productos</span>`),
                    width: '190px'
                },
                {
                    name: gridjs.html(`<span title="Método de pago">Método de pago</span>`),
                    width: '120px'
                }, 
                {
                    name: gridjs.html(`<span title="Monto total">Monto total</span>`),
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
                    name: gridjs.html(`<span title="Fecha de Venta">Fecha de Venta</span>`),
                    width: '130px'
                }, 
                {
                    name: gridjs.html(`<span title="Vendedor">Vendedor</span>`),
                    width: '110px',
                },
                {
                    name: 'Acciones',
                    width: '110px',
                    formatter: (cell, row) => {
                        const id = row.cells[0].data;
 
                        const editButtonHtml = document.getElementById('editButtonTemplate').innerHTML.replace('${id}', id);
                        const deleteButtonHtml = document.getElementById('deleteButtonTemplate').innerHTML.replace('${id}', id);

                        return gridjs.html(`
                            <form id="delete-form-${id}" action="/sistema/public/ventas/${id}" method="post">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                ${editButtonHtml} ${deleteButtonHtml}
                            </form>
                        `);
                    }
                }
            ],
            server: {
                url: ventasIndexUrl,
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                then: response => {
                    const ventas = response.ventas;
                    return ventas.map(venta => {
                        // Acceder a los productos relacionados de cada venta
                        const productos = venta.productos.map(producto => `${producto.nombre} (${producto.pivot.cantidad})`).join(', '); 
                        const metodoPago = venta.metodo_pago ? venta.metodo_pago.nombre : 'No especificado';
                        const vendedor = venta.vendedor.usuario
                        return [
                            venta.id, 
                            productos,  
                            metodoPago,  
                            parseFloat(venta.monto_total),  // Monto total
                            venta.fecha_venta,  // Fecha de venta
                            vendedor,
                        ];
                    });
                },
                
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
        }).render(document.getElementById('ventas-table'));
    }

    // Llamar a renderProductTable cuando se carga la página
    renderVentasTable();



    function actualizarTotalVenta() {
        let total = 0; // Comenzamos con el monto inicial
    
        $('#product-list .product-list-item').each(function() {
            const precio = parseFloat($(this).data('precio'));
            const cantidad = parseFloat($(this).data('cantidad'));
            if (!isNaN(precio) && !isNaN(cantidad)) {
                total += precio * cantidad;
            }
        });
    
        $('#monto_total').val(total.toFixed(2));
    }

    $('#ventaForm').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: ventasStoreUrl,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            data: $(this).serialize(),
            success: function(response){
                $('#ventaForm')[0].reset();
                if (response) {
                    Swal.fire({
                        icon : 'success',
                        title : '¡Nueva Venta!',
                        text : 'Nueva venta registrada',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.reload(); 
                    });
                    
                } else {
                    Swal.fire('Error', 'Hubo un problema al agregar la venta', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error addingsale:", error);
                Swal.fire('Error', 'Hubo un error al agregar la venta', 'error');
            }
            
        });
    });

    // Modal de edición de venta
    $('#editVentaModal').on('show.bs.modal', function(event){
       

        /*ABRIR VENTA MODAL*/
        const button = $(event.relatedTarget); 
        const id = button.data('id');
        let editVentaUrl = editVentaUrlTemplate.replace(':id', id);

        $.ajax({
            url: editVentaUrl,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }, 
            method: 'GET',
            success: function(data) {
                console.log("DATA DE LA VENTA:", data);
                const venta = data.venta
                const productos = data.productos;
                console.log(productos);

                const productosFiltrados = productos.filter(producto => data.producto_cod.includes(producto.codigo));
                console.log("Productos filtrados:", productosFiltrados);

                if (!venta.monto_total || isNaN(parseFloat(venta.monto_total))) {
                    console.error("Invalid monto_total in response");
                    return;
                }

                initialTotal = parseFloat(venta.monto_total);

                $('#edit_id').val(venta.id);
                $('#monto_total').val(venta.monto_total);
                $('#fecha_venta').val(venta.fecha_venta);
                if (data.cantidad && Array.isArray(data.cantidad)) {
                    data.cantidad.forEach((cantidad, index) => {
                        $(`#cantidad_${index}`).val(cantidad);
                    });
                } else {
                    console.warn("Propiedad cantidad no encontrada o no es un array.");
                }
                
                // Verificar y rellenar códigos de producto si están presentes
                if (data.producto_cod && Array.isArray(data.producto_cod)) {
                    data.producto_cod.forEach((codigo, index) => {
                        $(`#producto_cod_${index}`).val(codigo);
                    });
                } else {
                    console.warn("Propiedad producto_cod no encontrada o no es un array.");
                }
                

                const $productoSelect = $('#producto-select');
                $productoSelect.empty(); // Clear existing options
                $productoSelect.append('<option value="" disabled selected>Seleccione un producto</option>');

                $.each(productos, function(index, producto) {
                    if(producto.stock > 0 && producto.estado === 1){
                        $productoSelect.append(
                            `<option value="${producto.codigo}" data-precio="${producto.precio_venta}">
                                ${producto.nombre} - $${producto.precio_venta} x ${producto.unidad}
                            </option>`
                        );
                    }

                });

                $('#producto-select').chosen({
                    placeholder_text_single: 'Seleccione un producto', // Texto del placeholder
                    no_results_text: 'No se encontraron productos', // Texto cuando no hay resultados
                    width: '100%' // Establecer el ancho del select
                });
                                
                // $('#producto-select').select2({
                //     placeholder: 'Seleccione un producto', // Texto del placeholder
                //     allowClear: true // Permite limpiar la selección
                // });
                // $('#producto-select').trigger('change');
                
                // Populate the product list in the modal
                const $productList = $('#product-list');
                $productList.empty(); // Clear existing list items
                $.each(venta.productos, function(index, producto) {
                    const totalPrice = (producto.pivot.cantidad * producto.precio_venta).toFixed(2);
                    $productList.append(
                        `<li class="list-group-item product-list-item" data-codigo="${producto.codigo}"
                              data-precio="${producto.precio_venta}" 
                              data-cantidad="${producto.pivot.cantidad}">
                            ${producto.nombre} - ${producto.pivot.cantidad} x $${producto.precio_venta} = $${totalPrice}
                            <button type="button" class="btn btn-danger btn-sm float-end remove-product">Eliminar</button>
                        </li>`
                    );
                });                

                const hiddenInputs = document.getElementById('hidden-inputs');
                hiddenInputs.innerHTML += data.producto_cod.map((codigo, index) => 
                    `<input type="hidden" class="hidden-child" id="hidden-producto-${codigo}" data-codigo="${codigo}" name="producto_cod[]" value="${codigo}">
                    <input type="hidden" class="hidden-child" id="hidden-cantidad-${codigo}" data-cantidad="${data.cantidad[index]}" name="cantidad[]" value="${data.cantidad[index]}">`
                ).join('');
                actualizarTotalVenta();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
        
        
    });


    $('#producto-select').on('change', function() {
        selectedProduct = $(this).find(':selected');
        $('#cantidad-input').val('1');
    });

    $('#add-product').on('click', function() {
        const precio = selectedProduct.data('precio');
        const codigo = selectedProduct.val();
        const nombre = selectedProduct.text();
        const cantidad = parseFloat($('#cantidad-input').val());

    
        if (codigo && !isNaN(precio) && !isNaN(cantidad)) {
            $('#product-list').append(
                `<li class="list-group-item product-list-item" data-codigo="${codigo}"
                      data-precio="${precio}" 
                      data-cantidad="${cantidad}">
                    ${nombre} - ${cantidad} x $${precio} = $${(cantidad * precio).toFixed(2)}
                    <button type="button" class="btn btn-danger btn-sm float-end remove-product">Eliminar</button>
                </li>`
            );
            
        }

        const hiddenInputs = document.getElementById('hidden-inputs');
        hiddenInputs.innerHTML +=
            `<input type="hidden" class="hidden-child" id="hidden-producto-${codigo}" data-codigo="${codigo}" name="producto_cod[]" value="${codigo}">
            <input type="hidden" class="hidden-child" id="hidden-cantidad-${codigo}" data-cantidad="${cantidad}" name="cantidad[]" value="${cantidad}">`
        ;

        actualizarTotalVenta();
    });

    
    // Evento para eliminar un producto
    $(document).on('click', '.remove-product', function() {
        const item = $(this).closest('.product-list-item')
        const codigo = item.data('codigo');

        item.remove();
        $(`#hidden-producto-${codigo}`).remove();
        $(`#hidden-cantidad-${codigo}`).remove();

        actualizarTotalVenta();
    });

    $('#editVentasForm').on('submit', function(e) {

        e.preventDefault();
        const formData = $(this).serialize();
        console.log('Actualizar venta, data', formData)
        const id = $('#edit_id').val();
        let ventaUpdateUrlFinal = ventaUpdatetUrl.replace("id", id);

        $.ajax({
            url: ventaUpdateUrlFinal,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('token')
              },
            data: formData,
            success: function(response) {
                if (response.success) {
                    // window.location.reload();
                    $('#editVentaModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizada!',
                        text: 'La venta se ha actualizado correctamente.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        renderVentasTable(); // Recargar la página después de 2 segundos
                    });
                } else {
                    Swal.fire('Error', 'Hubo un problema al actualizar la venta', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error updating sale:", error);
                Swal.fire('Error', 'Hubo un error al realizar la actualización', 'error');
            }
        });
    });
});

function deleteVenta(id) {
    // Muestra el SweetAlert antes de realizar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta venta será eliminada de manera permanente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            const eliminarVentaUrlFinal = eliminarVentaUrl.replace("id", id);
            
            $.ajax({
                url: eliminarVentaUrlFinal,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                success: function(data) {
                    if (data.message === 'Venta eliminada exitosamente') {
                        Swal.fire(
                            'Eliminada',
                            'Venta eliminada exitosamente.',
                            'info'
                        ).then(function() {
                            window.location.reload(); // Recargar la página después de 2 segundos
                        });
                    } else {
                        alert('Error: ' + data.message); // Mensaje de error si no se encontró el producto
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar la venta:', error);
                    alert('Hubo un problema al intentar eliminar la venta.');
                }
            });
        }
    });
}
$(document).on('click', '.btn-delete', function() {
    const id = $(this).data('id');
    deleteVenta(id); 
});