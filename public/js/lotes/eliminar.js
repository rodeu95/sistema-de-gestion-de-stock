let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let grid;

document.addEventListener('DOMContentLoaded', function () {
    function renderLotesTable() {

        if (grid) {
            grid.destroy();
        }

        grid = new gridjs.Grid({
            columns: [   
                {
                    name: 'id',
                    hidden: true,
                },             
                {
                    name: 'Producto',
                    sort: true,
                    width: '200px',
                    formatter: (cell) => cell,
                    compare: (a, b) => a.toLowerCase().localeCompare(b.toLowerCase())
                },
                {
                    // name: 'Precio de Venta',
                    name: gridjs.html(`<span title="Número de lote">Número de lote</span>`),
                    resizable: true,
                    sort: true,
                },
                {
                    name: gridjs.html(`<span title="Cantidad">Cantidad</span>`),
                    sort: true,
                },
                {
                    name: gridjs.html(`<span title="Fecha de Ingreso">Fecha de ingreso</span>`),
                    resizable: true,
                    sort: true,
                },
                {
                    name: gridjs.html(`<span title="Fecha de vencimiento">Fecha de vencimiento</span>`),
                    resizable: true,
                    sort: true,
                },
                {
                    name: 'Acciones',
                    formatter: (cell, row) => {

                        const id = row.cells[0].data;
   
                        const deleteButtonHtml = document.getElementById('deleteButtonTemplate').innerHTML.replace('${id}', id);

                        return gridjs.html(`
                            <form id="delete-form-lote" action="/sistema/public/lotes/${id}/destroy" method="post">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                ${deleteButtonHtml}
                            </form>
                        `);
                    }
                }
            ],
            server: {
                url: loteIndexUrl,
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'X-CSRF-TOKEN': csrfToken,
                },
                then: data => {
                    return data.map(lote => {            
                       return [
                            lote.id,
                            lote.producto.nombre,
                            lote.numero_lote,
                            lote.cantidad,
                            lote.fecha_ingreso,
                            lote.fecha_vencimiento
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
            className: {
                table: 'productos-table',
                th: 'th',
                td: 'td',
                tr: 'tr',
            },
            style: {
                table: {
                    'border-collapse': 'collapse',
                    'border': 'none',
                    'border-radius' : '0',   
                },
                th: {
                    'background-color': '#fff',
                    'color' : 'grey',
                    'text-shadow': 'none',
                    'border-bottom': '1px solid #ddd',
                    'border-top': 'none', /* Sin borde superior */
                    'border-left': 'none', /* Sin borde izquierdo */
                    'border-right': 'none',
                },
                td:{
                    'border-bottom': '1px solid #ddd', /* Aplica el borde horizontal */
                    'border-top': 'none', /* Sin borde superior */
                    'border-left': 'none', /* Sin borde izquierdo */
                    'border-right': 'none', /* Sin borde derecho */
                }
                
            },
        }).render(document.getElementById('lotes-table'));
    }

    renderLotesTable();
});

function eliminarLote(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Desea eliminar el lote permanentemente?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            const eliminarLoteUrlFinal = eliminarLoteUrl.replace("id", id);
            console.log(eliminarLoteUrlFinal);
            $.ajax({
                url:eliminarLoteUrlFinal,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,                    
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                    
                },
                success: function (data) {
                    if (data.message === 'Lote eliminado exitosamente') {
                        Swal.fire(
                            'Eliminado',
                            'Lote eliminado exitosamente',
                            'info'
                        ).then(function () {
                            window.location.reload(); // Recargar la página
                        });
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error al eliminar el lote:', error);
                    alert('Hubo un problema al intentar eliminar el lote.');
                }
            });
        }
    });
}

$(document).on('click', '.btn-delete-lote', function() {
    const id = $(this).data('id');
    eliminarLote(id);
});