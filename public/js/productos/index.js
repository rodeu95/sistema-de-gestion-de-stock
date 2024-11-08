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
                        const codigo = row.cells[0].data;
                        console.log(codigo)
                        const editButton = puedeEditar
                            ? `<a href="javascript:void(0);" type="button" class="btn shadow btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal" data-codigo="${codigo}">
                                <i class="fa-solid fa-pen-to-square"></i></a>`
                            : '';

                        const deleteButton = puedeEliminar
                            ? `<button type="button" class="btn shadow btn-danger btn-sm" onclick="confirmDelete(${codigo})"><i class="fa-solid fa-trash-can"></i></button>`
                            : '';

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
                url: 'http://localhost/sistema/public/api/productos',
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

    // Función para confirmar la eliminación de un producto
    

    // Función para manejar el formulario de agregar un producto mediante AJAX
    $('#addProductForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: productosIndexUrl,
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

    $('#editProductModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget); // Botón que abre el modal
        const codigo = button.data('codigo'); // Obtener el código del producto desde el botón
        const modal = $(this);

        // Obtener los datos del producto desde la API o la ruta correspondiente
        $.ajax({
            url: '/productos/' + codigo, // Ruta para obtener los datos del producto
            method: 'GET',
            success: function (data) {
                // Rellenar el formulario con los datos
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
    });

    // Enviar los datos modificados del producto
    $('#editProductForm').on('submit', function (e) {
        e.preventDefault(); // Evitar el envío normal del formulario

        const formData = $(this).serialize(); // Obtener los datos del formulario

        $.ajax({
            url: productoEditUrl, // Ruta para actualizar el producto
            method: 'PUT',
            data: formData,
            success: function (response) {
                $('#editProductModal').modal('hide'); 
                $('#editProductModal')[0].reset();
                // Cerrar el modal
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto actualizado!',
                    text: 'El producto se ha actualizado correctamente.',
                    confirmButtonText: 'OK'
                });

                window.location.reload();// Recargar la tabla de productos
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }
        });
    });

    $('#editProductModal').on('hidden.bs.modal', function () {
        $('#editProductForm')[0].reset();
    });

    // Cargar los datos de un producto en el modal de edición
    

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

    // Función para actualizar el valor de stock dependiendo de la unidad (kg o unidad)
    

});
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