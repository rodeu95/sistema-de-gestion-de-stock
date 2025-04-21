let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let grid;

$.ajaxSetup({
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
});

document.addEventListener('DOMContentLoaded', function () {

    // Configuración inicial para el renderizado de la tabla de productos
    function renderProveedoresTable(proveedores) {

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
                    width: '100px',
                    sort: true,
                    resizable: true,
                    formatter: (cell) => cell,
                    compare: (a, b) => a.toLowerCase().localeCompare(b.toLowerCase())
                },
                {
                    name: gridjs.html(`<span title="Contacto">Contacto</span>`),
                    resizable: true,
                    width: '80px',
                },
                {
                    name: gridjs.html(`<span title="Teléfono">Teléfono</span>`),
                    width: '100px',
                    resizable: true,
                },
                {
                    name: gridjs.html(`<span title="E-mail">E-mail</span>`),
                    width: '130px',
                    resizable: true,
                },
                {
                    name: gridjs.html(`<span title="Dirección">Dirección</span>`),
                    width: '90px',
                    resizable: true,
                },
                {
                    name: gridjs.html(`<span title="Estado">Estado</span>`),
                    width: '60px',
                    resizable: true,
                },
                {
                    name: 'Acciones',
                    width: '60px',
                    resizable: true,
                    formatter: (cell, row) => {

                        const id = row.cell(0).data;
                        const estado = row.cell(6).data;
                        
                        const editButtonHtml = document.getElementById('editProveedorButton').innerHTML.replace('${id}', id);

                        const categoriasButtonHtml =  document.getElementById('mostrarCategorias').innerHTML.replace('${id}', id);


                        let buttonHtml = '';
                        if (estado === 'Inactivo') {
                            buttonHtml = document.getElementById('enableProveedorButton').innerHTML.replace('${id}', id);
                        } else {
                            buttonHtml = document.getElementById('disableProveedorButton').innerHTML.replace('${id}', id);
                        }

                        return gridjs.html(`
                            ${editButtonHtml} ${buttonHtml} ${categoriasButtonHtml}
                            
                        `);
                    }
                }
            ],
            data:            
            proveedores.map(proveedor => [ 
                // console.log(proveedor),                       
                proveedor.id,
                proveedor.nombre,
                proveedor.contacto,
                proveedor.telefono,
                proveedor.email,
                proveedor.direccion,
                proveedor.estado === 0 ? "Inactivo" : "Activo",
                null,
            ]), 
            // server: {
            //     url: url,
            //     headers: {
            //         'Authorization': 'Bearer ' + localStorage.getItem('token')
            //     },
            //     then: data => {
            //         console.log(data);
            //         return data.map(proveedor => [
            //             proveedor.id,
            //             proveedor.nombre,
            //             proveedor.contacto,
            //             proveedor.telefono,
            //             proveedor.email,
            //             proveedor.direccion,
            //             proveedor.estado === 0 ? "Inactivo" : "Activo",
            //             null,
            //         ]);
            //     }
            // },
            // resizable: true,
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
                    'background-color': '#acd8b5',
                    'color' : '#fff',
                    'text-shadow': '2px 2px 2px rgba(0, 0, 0, 0.6)',
                    'border-bottom': '1px solid #ddd',
                    'border-top': 'none', /* Sin borde superior */
                    'border-left': 'none', /* Sin borde izquierdo */
                    'border-right': 'none',
                    'text-align': 'center',
                    'padding': '10px',
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

    function proveedoresIndex() {
        
        console.log(proveedoresIndexUrl);
        $.ajax({
            url: proveedoresIndexUrl,
            method: 'GET',
            success: function (response) {
                console.log(response);
                renderProveedoresTable(response)
            },
            error: function () {
                console.error('Error al filtrar proveedores.');
            }
        });
    };
    proveedoresIndex()
    // renderProveedoresTable(proveedoresIndexUrl);

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
                console.log('Datos recibidos:', data);
                const modal = $('#editProveedorModal');
                // Rellenar el formulario con los datos del producto
                modal.find('#edit_id').val(data.proveedor.id);
                modal.find('#edit_nombre').val(data.proveedor.nombre);
                modal.find('#edit_contacto').val(data.proveedor.contacto);
                modal.find('#edit_telefono').val(data.proveedor.telefono);
                modal.find('#edit_email').val(data.proveedor.email);
                modal.find('#edit_direccion').val(data.proveedor.direccion);
                modal.find('#edit_cuit').val(data.proveedor.cuit);

                $.ajax({
                    url: categoriasIndex,
                    method: 'GET',
                    success: function (categorias) {
                        console.log('Categorías:', categorias);
                        const categoriasContainer = modal.find('#categorias-container');
                        categoriasContainer.empty(); // Limpiamos el contenedor antes de llenarlo
    
                        categorias.forEach(categoria => {
                            const isChecked = data.categoriasProveedor.includes(categoria.id)? 'checked' : ''; // Comprobar si la categoría pertenece al proveedor
                            const checkbox = `
                                <div class="form-check" >
                                    <input type="checkbox" name="categorias[]" class="form-check-input" value="${categoria.id}" id="categoria_${categoria.id}" ${isChecked ? 'checked' : ''}>
                                    <label class="form-check-label" for="categoria_${categoria.id}">${categoria.nombre}</label>
                                </div>
                            `;
                            categoriasContainer.append(checkbox);
                        });
                    },
                    error: function () {
                        console.log('Error al obtener las categorías del proveedor.');
                    }
                });

            }
        });
    })

    $('#editProveedorForm').on('submit', function (e) {
        /*EDICION DEL PRODUCTO DEL MODAL */
        e.preventDefault();
        const formData = $(this).serialize();
        
        const id = $('#edit_id').val();
        let proveedorUpdatetUrlFinal = proveedoresUpdatetUrl.replace("id", id);
        console.log(proveedorUpdatetUrlFinal);
        $.ajax({
            url: proveedorUpdatetUrlFinal, // URL del formulario establecida dinámicamente
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            data: formData,
            success: function (response) {
                if(response.success){
                    $('#editProveedorModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: 'El proveedor se ha actualizado correctamente.',
                        confirmButtonColor: "#acd8b5",
                        confirmButtonText: 'OK'
                    }).then(function() {
                        renderProveedoresTable(); 
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }
        });
    });

    $('#categoriasProveedorModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');

        categoriasProveedorUrlFinal = categoriasProveedorUrl.replace("id", id);
        $.ajax({
            url: categoriasProveedorUrlFinal,
            method: 'GET',
            success: function (data) {
                console.log(data);
                let categorias = data.categoriasProveedor;
                let categoriasLista = '';
                categorias.forEach(categoria => {
                    categoriasLista += `<li>${categoria.nombre}</li>`
                });
                $('#listaCategorias').html(categoriasLista);

                $('#categoriasProveedorModal').modal('show');

            },
            error: function (xhr, status, error) {
                console.error('Error al obtener categorías:', error);
                console.log('Respuesta completa:', xhr.responseText);
            }
        });
    })

    document.getElementById('categoriaFiltro').addEventListener('change', function () {
        const categoriaId = this.value;
        filtrarProveedores(categoriaId);
    });
    function filtrarProveedores(categoriaId) {
        console.log(categoriaId);
        console.log(proveedoresFiltradoUrl);
        $.ajax({
            url: proveedoresFiltradoUrl,
            method: 'GET',
            data: { categoria_id: categoriaId },
            success: function (response) {
                console.log(response);
                renderProveedoresTable(response)
            },
            error: function () {
                console.error('Error al filtrar proveedores.');
            }
        });
    };

    
    
});