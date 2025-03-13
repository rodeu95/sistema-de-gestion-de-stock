let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let grid;

document.addEventListener('DOMContentLoaded', function () {

    // Configuración inicial para el renderizado de la tabla de productos
    function renderProveedoresTable() {

        if (grid) {
            grid.destroy();
        }

        grid = new gridjs.Grid({
            columns: [
                {
                    name: 'ID',
                    hidden: true,
                },

                {
                    name: gridjs.html(`<span title="Nombre">Nombre</span>`),
                    width: '150px',
                    sort: true,
                    formatter: (cell) => cell,
                    compare: (a, b) => a.toLowerCase().localeCompare(b.toLowerCase())
                },
                {
                    name: gridjs.html(`<span title="Contacto">Contacto</span>`),
                    resizable: true,
                    width: '100px',
                },
                {
                    name: gridjs.html(`<span title="Teléfono">Teléfono</span>`),
                    width: '150px',
                },
                {
                    name: gridjs.html(`<span title="E-mail">E-mail</span>`),
                },
                {
                    name: gridjs.html(`<span title="Dirección">Dirección</span>`),
                    resizable: true,
                },
                {
                    name: gridjs.html(`<span title="CUIT">CUIT</span>`),
                    resizable: true,
                    width: '130px',
                },
                {
                    name: gridjs.html(`<span title="Estado">Estado</span>`),
                    resizable: true,
                },
                {
                    name: 'Acciones',
                    width: '110px',
                    formatter: (cell, row) => {

                        const id = row.cell(0).data;
                        const estado = row.cell(7).data;
                        
                        const editButtonHtml = document.getElementById('editProveedorButton').innerHTML.replace('${id}', id);

                        let buttonHtml = '';
                        if (estado === 'Inactivo') {
                            buttonHtml = document.getElementById('enableProveedor').innerHTML.replace('${id}', id);
                        } else {
                            buttonHtml = document.getElementById('disableProveedor').innerHTML.replace('${id}', id);
                        }
                        
                        return gridjs.html(`
                            ${editButtonHtml} ${buttonHtml}
                            
                        `);
                    }
                }
            ],
            server: {
                url: proveedoresIndexUrl,
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                then: data => {
                    console.log(data);
                    return data.map(proveedor => {

                        return [
                            proveedor.id,
                            proveedor.nombre,
                            proveedor.contacto,
                            proveedor.telefono,
                            proveedor.email,
                            proveedor.direccion,
                            proveedor.cuit,
                            proveedor.estado === 0 ? "Inactivo" : "Activo",
                            null,
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
                table: 'proveedores-table',
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
        }).render(document.getElementById('proveedores-table'));
    }

    // Llamar a renderProductTable cuando se carga la página
    renderProveedoresTable();

    $('#editProveedorModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');
        let editProveedorUrl = editProveedorUrlTemplate.replace(':id', id);
        console.log(editProveedorUrl);

        $.ajax({
            url: editProveedorUrl,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }, // Ruta para obtener los datos del producto
            method: 'GET',
            success: function (data) {

                const modal = $('#editProveedorModal');
                // Rellenar el formulario con los datos del producto
                modal.find('#edit_id').val(data.id);
                modal.find('#edit_nombre').val(data.nombre);
                modal.find('#edit_contacto').val(data.contacto);
                modal.find('#edit_telefono').val(data.telefono);
                modal.find('#edit_email').val(data.email);
                modal.find('#edit_direccion').val(data.direccion);
                modal.find('#edit_cuit').val(data.cuit);
            }
        });
    })
});