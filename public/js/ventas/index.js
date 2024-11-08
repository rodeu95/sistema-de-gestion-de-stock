let puedeEditar = true;
let puedeEliminar = true;
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
 
                        const editButton = puedeEditar
                            ? `<a href="javascript:void(0);" type="button" class="btn shadow btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editVentaModal" data-codigo="${id}">
                                <i class="fa-solid fa-pen-to-square"></i></a>`
                            : '';

                        const deleteButton = puedeEliminar
                            ? `<button type="button" class="btn shadow btn-danger btn-sm" onclick="confirmDelete(${id})"><i class="fa-solid fa-trash-can"></i></button>`
                            : '';

                        return gridjs.html(`
                            <form id="delete-form-${id}" action="/sistema/public/ventas/${id}" method="post">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                ${editButton} ${deleteButton}
                            </form>
                        `);
                    }
                }
            ],
            server: {
                url: 'http://localhost/sistema/public/api/ventas',
                then: data => {
                    return data.map(venta => {
                        // Acceder a los productos relacionados de cada venta
                        const productos = venta.productos.map(producto => producto.nombre).join(', '); // Cambia 'nombre' por el campo que necesites
                        const metodoPago = venta.metodoPago ? venta.metodoPago.nombre : 'No especificado';
                        return [
                            venta.id,           // ID de la venta
                            productos,          // Columna de Productos con los nombres de los productos relacionados
                            metodoPago,  // Método de pago
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
});