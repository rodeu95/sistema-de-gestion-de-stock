
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let grid;

document.addEventListener('DOMContentLoaded', function () {
    function renderProductTable() {

        if (grid) {
            grid.destroy();
        }

        grid = new gridjs.Grid({
            columns: [
                'ID', 
                'Productos', 
                'Método de pago', 
                {
                    name: 'Monto total',
                    formatter: (cell) => {
                        // Asegúrate de que el valor sea un número antes de formatearlo
                        const amount = parseFloat(cell);
                        // Si no es un número válido, retorna el valor original
                        if (isNaN(amount)) return cell;
                        // Devuelve el valor con el símbolo '$' y lo formatea como una moneda
                        return '$' + amount.toFixed(2); // Esto agrega dos decimales, cambia según lo necesites
                    }
                }, 
                'Fecha de Venta', 
                {
                    name: 'Acciones',
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
                then: response => {
                    console.log(response.ventas);
                    const ventas = response.ventas;
                    return ventas.map(venta => {
                        // Acceder a los productos relacionados de cada venta
                        const productos = venta.productos.map(producto => `${producto.nombre} (${producto.pivot.cantidad})`).join(', '); 
                        const metodoPago = venta.metodo_pago ? venta.metodo_pago.nombre : 'No especificado';
                        return [
                            venta.id, 
                            productos,  
                            metodoPago,  
                            parseFloat(venta.monto_total),  // Monto total
                            venta.fecha_venta,  // Fecha de venta

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
                    'background-color': '#fff3cd'
                }
            },
        }).render(document.getElementById('ventas-table'));
    }

    // Llamar a renderProductTable cuando se carga la página
    renderProductTable();

    function calculateTotal() {
        let total = 0;

        // Recorrer todos los productos en la lista
        document.querySelectorAll('.product-list-item').forEach(function(item) {
            const precio = parseFloat(item.getAttribute('data-precio')) || 0;
            const cantidad = parseFloat(item.getAttribute('data-cantidad')) || 0;

            // Sumar el subtotal (precio * cantidad) al total
            total += precio * cantidad;
        });

        // Actualizar el campo del monto total
        document.getElementById('monto_total').value = total.toFixed(2);
    }

    // Función para agregar un producto a la lista
    function addProductToList(productCod, productName, productPrice, quantity) {
        if (!productCod || quantity <= 0) {
            alert("Cantidad inválida o producto no seleccionado.");
            return;
        };
        console.log(`Agregando producto: ${productName}, ID: ${productCod}, Cantidad: ${quantity}`);
        const container = document.getElementById('product-list');
        const listItem = document.createElement('li');
        listItem.classList.add('list-group-item', 'product-list-item');
        listItem.setAttribute('data-precio', productPrice);
        listItem.setAttribute('data-cantidad', quantity);

        // Agregar el resumen del producto
        listItem.innerHTML = `
            ${productName} - ${quantity} x $${productPrice} = $${(productPrice * quantity).toFixed(2)}
            <button type="button" class="btn btn-danger btn-sm float-end remove-product">Eliminar</button>
        `;
        console.log(`Producto agregado: ${productName}, Cantidad: ${quantity}`);

        const hiddenInputs = document.getElementById('hidden-inputs');
        if (quantity != null) {
            hiddenInputs.innerHTML += `<input type="hidden" name="producto_cod[]" value="${productCod}">
                                        <input type="hidden" name="cantidad[]" value="${quantity}">`;
        } else {
            console.warn(`Cantidad no válida para el producto código: ${productCod}`);
        }
        // Agregar a la lista de productos
        container.appendChild(listItem);

        // Recalcular el total
        calculateTotal();

        // Agregar evento de eliminar
        listItem.querySelector('.remove-product').addEventListener('click', function() {
            listItem.remove();
            calculateTotal(); // Recalcular el total después de eliminar
        });
    }

    // Función para manejar el evento de agregar producto
    function handleAddProduct() {
        const select = document.getElementById('producto-select');
        const cantidadInput = document.getElementById('cantidad-input');
        const productCod = select.value;
        const productName = select.options[select.selectedIndex]?.text;
        const productPrice = parseFloat(select.options[select.selectedIndex]?.getAttribute('data-precio')) || 0;
        
        // Establecer la cantidad predeterminada en 1
        let quantity = parseFloat(cantidadInput.value) || 1; 
    
        if (productCod && quantity > 0) {
            // Agregar el producto a la lista
            addProductToList(productCod, productName, productPrice, quantity);
    
            // Limpiar los campos de selección
            select.selectedIndex = 0;
            cantidadInput.value = ''; // Dejar el campo de cantidad vacío para que se ajuste a 1 al seleccionar otro producto
        } else {
            alert("Por favor, selecciona un producto y una cantidad válida.");
        }
    }

    // Función para validar el envío del formulario
    function validateForm(event) {
        const productList = document.querySelectorAll('.product-list-item');
        if (productList.length === 0) {
            event.preventDefault(); // Prevenir el envío
            alert("Debes agregar al menos un producto antes de enviar el formulario.");
        }
    }

    // Agregar eventos
    document.getElementById('add-product').addEventListener('click', handleAddProduct);
    
    // Validar el formulario al enviarlo
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', validateForm);
    }

    // Modal de edición de venta
    $('#editVentaModal').on('show.bs.modal', function(event){
        const button = $(event.relatedTarget); 
        const id = button.data('id');
        let editVentaUrl = editVentaUrlTemplate.replace(':id', id);
        console.log(editVentaUrl);

        $.ajax({
            url: editVentaUrl, 
            method: 'GET',
            success: function(data) {
                console.log(data);
                const venta = data.venta
                const productos = data.productos;
                console.log(productos);

                const $productoSelect = $('#producto-select');
                $productoSelect.empty(); // Clear existing options
                $productoSelect.append('<option value="" disabled selected>Seleccione un producto</option>');
                $.each(productos, function(index, producto) {
                    $productoSelect.append(
                        `<option value="${producto.codigo}" data-precio="${producto.precio_venta}">
                            ${producto.nombre} - $${producto.precio_venta}
                        </option>`
                    );
                });

                // Populate other form fields
                $('#edit_id').val(venta.id);
                $('#monto_total').val(venta.monto_total);
                $('#fecha_venta').val(venta.fecha_venta);

                // Populate the product list in the modal
                const $productList = $('#product-list');
                $productList.empty(); // Clear existing list items
                $.each(venta.productos, function(index, producto) {
                    const totalPrice = (producto.pivot.cantidad * producto.precio_venta).toFixed(2);
                    $productList.append(
                        `<li class="list-group-item product-list-item" 
                              data-precio="${producto.precio}" 
                              data-cantidad="${producto.pivot.cantidad}">
                            ${producto.nombre} - ${producto.pivot.cantidad} x $${producto.precio_venta} = $${totalPrice}
                            <button type="button" class="btn btn-danger btn-sm float-end remove-product">Eliminar</button>
                            <input type="hidden" name="producto_cod[]" value="${producto.codigo}">
                            <input type="hidden" name="cantidad[]" value="${producto.pivot.cantidad}">
                        </li>`
                    );
                });

                $('.remove-product').on('click', function() {
                    $(this).closest('.product-list-item').remove();
                    calculateTotal();
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    });

    $('#ventasForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const id = $('#edit_id').val();
        let ventaUpdateUrlFinal = ventaUpdatetUrl.replace("id", id);
        console.log(ventaUpdateUrlFinal);
        $.ajax({
            url: ventaUpdateUrlFinal,
            method: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Actualizar la tabla sin recargar la página
                    window.location.reload();
                    $('#editVentaModal').modal('hide');
                    Swal.fire('Venta actualizada con éxito', '', 'success');
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
            console.log(eliminarVentaUrlFinal);
            
            $.ajax({
                url: eliminarVentaUrlFinal,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
    console.log(id); 
    deleteVenta(id); 
});