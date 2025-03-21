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

                        const numero_lote = row.cells[1].data;
   
                        const deleteButtonHtml = document.getElementById('deleteButtonTemplate').innerHTML.replace('${numero_lote}', numero_lote);

                        return gridjs.html(`
                            <form id="delete-form-lote" action="{{ route('api.lotes.destroy', ['numero_lote' => 'numero_lote']) }}" method="DELETE">
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
                    console.log(data);
                    return data.map(lote => {            
                    
                       return [
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

function eliminarLote(numero_lote) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Desea eliminar el lote permanentemente?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#aed5b6",
        cancelButtonColor: "grey",
    }).then((result) => {
        if (result.isConfirmed) {
            const eliminarLoteUrlFinal = eliminarLoteUrl.replace("numero_lote", numero_lote);
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
                        Swal.fire({
                            title: 'Eliminado',
                            text: 'Lote eliminado exitosamente',
                            icon: 'info',
                            confirmButtonColor: "#aed5b6",
                        }).then(function () {
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
    const numero_lote = $(this).data('numero_lote');
    console.log(eliminarLoteUrl.replace("numero_lote", numero_lote));
    eliminarLote(numero_lote);
});