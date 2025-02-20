
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let grid;
let initialTotal = 0;

document.addEventListener('DOMContentLoaded', function () {

    const yearSelect = document.getElementById('year-select');

    // Rango de años: puedes ajustar el año inicial y cuántos años a futuro incluir
    const startYear = 2000; // Año inicial
    const endYear = new Date().getFullYear(); // Año actual

    // Generar las opciones
    for (let year = startYear; year <= endYear; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // Establecer un valor predeterminado (opcional)
    yearSelect.value = endYear;

    function renderVentasTable() {
        
        if (grid) {
            grid.destroy();
        }
        
        grid = new gridjs.Grid({
            columns: [
                {
                    name: gridjs.html(`<span title="ID">ID</span>`),
                    width: '50px'
                },
                {
                    name: gridjs.html(`<span title="Productos">Productos</span>`),
                    width: '100px'
                },
                {
                    name: gridjs.html(`<span title="Método de pago">Método de pago</span>`),
                    width: '80px'
                }, 
                {
                    name: gridjs.html(`<span title="Monto total">Monto total</span>`),
                    width: '80px',
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
                    width: '80px'
                }, 
                {
                    name: gridjs.html(`<span title="Vendedor">Vendedor</span>`),
                    width: '80px',
                },
                {
                    name: gridjs.html(`<span title="Estado">Estado</span>`),
                    width: '80px'
                },
                {
                    name: 'Acciones',
                    width: '80px',
                    formatter: (cell, row) => {
                        const id = row.cells[0].data;
                        const estado = row.cell(6).data;
 
                        const showVentaButtonHtml = document.getElementById('showVentaTemplate').innerHTML.replace('${id}', id);
                        
                        let buttonHtml = '';
                        if (estado === 'Confirmada') {
                            buttonHtml = document.getElementById('anularButtonTemplate').innerHTML.replace('${id}', id);
                        
                        } else {
                            buttonHtml;
                        }

                        return gridjs.html(`
                            
                            ${showVentaButtonHtml} ${buttonHtml}
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
                    console.log('Datos del servidor:', response);
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
                            venta.estado === 0 ? "Anulada" : "Confirmada",
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
            search: {
                enable:true,
                id: 'filtro-busqueda',
            },
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
                table: 'table',
                th: 'th',
                td: 'td',
                tr: 'tr',
            },
            style: {
                th: {
                    'background-color': '#fff',
                    'color' : 'grey',
                    'text-shadow': 'none',
                    'border-bottom': '1px solid #ddd', /* Aplica el borde horizontal */
                    'border-top': 'none', /* Sin borde superior */
                    'border-left': 'none', /* Sin borde izquierdo */
                    'border-right': 'none',
                },
                td:{
                    'border-bottom': '1px solid #ddd', /* Aplica el borde horizontal */
                    'border-top': 'none', /* Sin borde superior */
                    'border-left': 'none', /* Sin borde izquierdo */
                    'border-right': 'none',
                }
                
            },
        }).render(document.getElementById('ventas-table'));
    }

    document.getElementById('apply-filters').addEventListener('click', function () {
        const fechaVenta = document.getElementById('fecha_venta').value; // Fecha exacta
        const mes = document.getElementById('select-mes').value; // Mes seleccionado
        const anio = document.getElementById('year-select').value; // Año seleccionado
    
        // Crear el texto de búsqueda combinando los filtros seleccionados
        let searchValue = '';
    
        if (fechaVenta) {
            searchValue = `${fechaVenta}`;
        } else if (mes && anio) {
            searchValue = `${anio}-${mes}`; // Formato de año-mes (e.g., "2024-11")
        } else if (mes) {
            searchValue = `${anio}-${mes}`; // Si no hay año, buscar solo el mes
        } else if (anio) {
            searchValue = `${anio}`;
        }
    
        // Obtener el cuadro de búsqueda de Grid.js y actualizar su valor
        const searchInput = document.querySelector('.gridjs-search input'); // Encuentra el input de búsqueda
        searchInput.value = searchValue;
    
        // Disparar el evento "input" para que Grid.js actualice la tabla
        searchInput.dispatchEvent(new Event('input'));
    });

    // Llamar a renderProductTable cuando se carga la página
    renderVentasTable();


});
function formatearFecha(fecha) {
    const date = new Date(fecha);
    const dia = date.getDate().toString().padStart(2, '0');
    const mes = (date.getMonth() + 1).toString().padStart(2, '0'); // Enero es 0
    const año = date.getFullYear();
    const horas = date.getHours().toString().padStart(2, '0');
    const minutos = date.getMinutes().toString().padStart(2, '0');
    const segundos = date.getSeconds().toString().padStart(2, '0');

    return `${dia}/${mes}/${año} a las ${horas}:${minutos}:${segundos}`;
}

$('#showVentaModal').on('show.bs.modal', function (event) {

    const button = $(event.relatedTarget);
    const id = button.data('id');
    let showVentatUrlFinal = showVentatUrl.replace(':id', id);


    $.ajax({
        url: showVentatUrlFinal,
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }, 
        method: 'GET',
        success: function (response) {
           if(response.success){
                let venta = response.venta;
                
                $('#venta-id').text(venta.id);
                $('#venta-monto').text('$'+venta.monto_total);
                $('#venta-fecha').text(formatearFecha(venta.created_at));
                
                $('#venta-estado').text(venta.estado === 0 ? "Anulada" : "Confirmada");

                let productosHtml = '';
                venta.productos.forEach(producto => {
                    if(producto.unidad === 'UN'){
                        productosHtml += `<li>${producto.pivot.cantidad} UN - ${producto.nombre}</li>`;
                    }else{
                        productosHtml += `<li>${producto.pivot.cantidad} KG - ${producto.nombre}</li>`;
                    }
                    
                });

                $('#lista-productos').html(productosHtml);

                $('#showVentaModal').modal('show');
            }           
                        
        },
        error: function() {
            alert('No se pudo cargar la venta.');
        }
    });
})

 
function anularVenta(id) {
    // Muestra el SweetAlert antes de realizar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Desea anular la venta permanentemente?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            const anularVentaUrlFinal = anularVentaUrl.replace("id", id);
            console.log(anularVentaUrlFinal);
            $.ajax({
                url: anularVentaUrlFinal,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                success: function(data) {
                    if (data.message === 'Venta anulada exitosamente') {
                        Swal.fire(
                            'Anulada',
                            'Venta anulada exitosamente.',
                            'info'
                        ).then(function() {
                            window.location.reload(); // Recargar la página después de 2 segundos
                        });
                    } else {
                        alert('Error: ' + data.message); // Mensaje de error si no se encontró el producto
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al anular la venta:', error);
                    alert('Hubo un problema al intentar anular la venta.');
                }
            });
        }
    });
}
$(document).on('click', '#anular-venta-btn', function() {
    const id = $(this).data('id');
    anularVenta(id); 
});
