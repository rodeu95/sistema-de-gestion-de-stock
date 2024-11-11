
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
 
                        const editButtonHtml = document.getElementById('editButtonTemplate').innerHTML.replace('${codigo}', codigo);
                        const deleteButtonHtml = document.getElementById('deleteButtonTemplate').innerHTML.replace('${codigo}', codigo);

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
                then: data => {
                    return data.map(venta => {
                        // Acceder a los productos relacionados de cada venta
                        const productos = venta.productos.map(producto => producto.nombre).join(', '); // Cambia 'nombre' por el campo que necesites
                        const metodoPago = venta.metodoPago ? venta.metodoPago.nombre : 'No especificado';
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

    $('#editVentaModal').on('show.bs.modal', function(event){
        const button = $(event.relatedTarget); 
        const id = button.data('id');
        let editVentaUrl = editVentaUrlTemplate.replace(':id', id);
        console.log(editVentaUrl);

        $.ajax({
            url: editVentaUrl, // Ruta para obtener los datos del producto
            method: 'GET',
            success: function(data) {
                const { venta, productos } = data;

                const $productoSelect = $('#producto-select');
                $productoSelect.empty(); // Clear existing options
                $productoSelect.append('<option value="" disabled selected>Seleccione un producto</option>');
                $.each(productos, function(index, producto) {
                    $productoSelect.append(
                        `<option value="${producto.codigo}" data-precio="${producto.precio}">
                            ${producto.nombre} - $${producto.precio}
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
                    const totalPrice = (producto.pivot.cantidad * producto.precio).toFixed(2);
                    $productList.append(
                        `<li class="list-group-item product-list-item" 
                              data-precio="${producto.precio}" 
                              data-cantidad="${producto.pivot.cantidad}">
                            ${producto.nombre} - ${producto.pivot.cantidad} x $${producto.precio} = $${totalPrice}
                            <button type="button" class="btn btn-danger btn-sm float-end remove-product">Eliminar</button>
                            <input type="hidden" name="producto_cod[]" value="${producto.codigo}">
                            <input type="hidden" name="cantidad[]" value="${producto.pivot.cantidad}">
                        </li>`
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    })
    
    $('#editVentaForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const id = $('#edit_id').val();
        let ventaUpdateUrlFinal = ventaUpdatetUrl.replace("id", id);

        $.ajax({
            url: ventaUpdateUrlFinal,
            method: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Actualizar la tabla sin recargar la página
                    renderProductTable();
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