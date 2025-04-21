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
                    width: '130px',
                    formatter: (cell, row) => {

                        const numero_lote = row.cells[1].data;
   
                        const deleteButtonHtml = document.getElementById('deleteButtonTemplate').innerHTML.replace('${numero_lote}', numero_lote);
                        const editButtonHtml = document.getElementById('editButtonTemplate').innerHTML.replace('${numero_lote}', numero_lote);

                        return gridjs.html(`
                            ${editButtonHtml}${deleteButtonHtml}
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
                    'background-color': '#acd8b5',
                    'color' : '#fff',
                    'text-shadow': '2px 2px 2px rgba(0, 0, 0, 0.6)',
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

    $('#editLoteModal').on('show.bs.modal', function (event) {
        /*CARGA DATOS DEL LOTE EN EL MODAL DE EDICION*/
        const button = $(event.relatedTarget);
        const numero_lote = button.data('numero_lote');
        let editLoteUrlFinal = editLoteUrl.replace('numero_lote', numero_lote);
        console.log(editLoteUrlFinal);
        $.ajax({
            url: editLoteUrlFinal,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }, // Ruta para obtener los datos del producto
            method: 'GET',
            success: function (data) {
                console.log(data);
                const modal = $('#editLoteModal');
                // Rellenar el formulario con los datos del producto
                modal.find('#edit_producto_cod').val(data.producto_cod);
                modal.find('#edit_numero-lote').val(data.numero_lote);
                modal.find('#edit_cantidad-lote').val(data.cantidad);
                modal.find('#edit_fecha-expiracion').val(data.fecha_vencimiento);
                modal.find('#edit_fecha-ingreso').val(data.fecha_ingreso);
            }
        });
    })

    $('#editLoteForm').on('submit', function (e) {
        /*EDICION DEL PRODUCTO DEL MODAL */
        e.preventDefault();
        const formData = $(this).serialize();
        
        const numero_lote = $('#edit_numero-lote').val();
        let updateLoteUrlFinal = updateLoteUrl.replace('numero_lote', numero_lote);

        $.ajax({
            url: updateLoteUrlFinal, // URL del formulario establecida dinámicamente
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            data: formData,
            success: function (response) {
                if(response.success){
                    $('#editLoteModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: 'El lote se ha actualizado correctamente.',
                        confirmButtonColor: "#acd8b5",
                        confirmButtonText: 'OK'
                    }).then(function() {
                        renderLotesTable(); 
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }
        });
    });
});



function eliminarLote(numero_lote) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Desea eliminar el lote permanentemente?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#acd8b5",
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
                            confirmButtonColor: "#acd8b5",
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