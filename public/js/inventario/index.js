let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let grid;

document.addEventListener('DOMContentLoaded', function () {
    function renderInventarioTable() {
        grid = new gridjs.Grid({
            columns: [
                {
                    name: gridjs.html(`<span title="Código único del producto">Código</span>`),
                    
                },
                {
                    name: gridjs.html(`<span title="Producto">Producto</span>`),
                    sort: true,
                    
                },
                {
                    name: gridjs.html(`<span title="Categoría">Categoría</span>`),
                    
                },
                {
                    name: gridjs.html(`<span title="Precio de costo">Precio costo</span>`),
                    formatter: (cell, row) => {
                        const precioCosto = parseFloat(row.cells[3].data);
                        const unidad = row.cells[6].data;
                        
                        // Formatea el precio con la unidad para mostrar
                        const displayValue = unidad === 'UN' 
                            ? `$${precioCosto} x UN` 
                            : `$${precioCosto} x KG`;

                        return gridjs.html(displayValue); 
                    },
                    sort: {

                        compare: (a, b) => {
                            console.log('Comparando:', { a, b });
                            // Comparar usando los valores numéricos del atributo data-value
                            const valueA = parseFloat(a);
                            const valueB = parseFloat(b);

                            // Si no son números, considera el orden
                            if (isNaN(valueA) || isNaN(valueB)) return 0;

                            // Ordena numéricamente
                            return valueA - valueB;
                        }
                    }
                },

                {
                    name: gridjs.html(`<span title="Precio de venta">Precio Venta</span>`),
                    formatter: (cell, row) => {
                        const precioVenta = parseFloat(row.cells[4].data);
                        const unidad = row.cells[6].data;
                        console.log('Precio Venta:', { precioVenta, unidad }); // Asume que la unidad está en la columna 3
                        
                        // Formatea el precio con la unidad para mostrar
                        const displayValue = unidad === 'UN' 
                            ? `$${precioVenta} x UN` 
                            : `$${precioVenta} x KG`;

                        return gridjs.html(displayValue); 
                    },
                    sort: {

                        compare: (a, b) => {
                            console.log('Comparando:', { a, b });
                            // Comparar usando los valores numéricos del atributo data-value
                            const valueA = parseFloat(a);
                            const valueB = parseFloat(b);

                            // Si no son números, considera el orden
                            if (isNaN(valueA) || isNaN(valueB)) return 0;

                            // Ordena numéricamente
                            return valueA - valueB;
                        }
                    }
                },
                {
                    name: gridjs.html(`<span title="Stock disponible">Stock</span>`),
                    sort: true,
                },
                {
                    name: 'unidad',
                    hidden: true
                },
                
                {
                    name: gridjs.html(`<span title="Lotes y vencimientos">Lotes</span>`),
                }
            ],
            server: {
                url: inventarioIndexUrl, // Asegúrate de que esta URL apunte a tu ruta correcta en Laravel
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'), // Si usas tokens de autenticación
                },
                then: response => {
                    console.log(response);
                    return response.map(producto => {
                        const precioCosto = parseFloat(producto.precio_costo) || 0;
                        const precioVenta = parseFloat(producto.precio_venta) || 0;
                        const stock = parseFloat(producto.stock) || 0;
                        
                        return [
                            producto.codigo,
                            producto.nombre,
                            producto.categoria ? producto.categoria.nombre : 'Sin categoría',
                            (producto.unidad === 'UN' 
                                ? `${precioCosto.toFixed(2)} x UN` 
                                : `${precioCosto.toFixed(2)} x KG`),
                            `${precioVenta.toFixed(2)}`,
                            stock,
                            producto.unidad,
                            producto.lotes && producto.lotes.length > 0 
                                ? producto.lotes.map(lote => 
                                    `Lote: ${lote.numero_lote} Vence: ${new Date(lote.fecha_vencimiento).toLocaleDateString('es-AR')} Cantidad: ${lote.cantidad}`
                                    ).join('\n') 
                                : 'Sin lotes'
                        ];
                        
                    });
                }
            },
            resizable: true,
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
            pagination: {
                enabled: true,
                limit: 10,
            },
            className: {
                thead: 'grid-header',
                table: 'table',
                th: 'th',
                td: 'td',
                tr: 'tr',
            },
            style: {
                table: {
                    'border-collapse': 'collapse',
                    'border': 'none',
                    'border-radius' : '0',
                    'text-align': 'center',
                    
                },
                th: {
                    'background-color': '#acd8b5',
                    'color' : '#fff',
                    'text-shadow': '2px 2px 2px rgba(0, 0, 0, 0.6)',
                    'border-bottom': '1px solid #ddd', /* Aplica el borde horizontal */
                    'border-top': 'none', /* Sin borde superior */
                    'border-left': 'none', /* Sin borde izquierdo */
                    'border-right': 'none',/* Borde para las celdas del encabezado */
                    'padding': '8px',
                },
                td: {
                    'border-bottom': '1px solid #ddd', /* Aplica el borde horizontal */
                    'border-top': 'none', /* Sin borde superior */
                    'border-left': 'none', /* Sin borde izquierdo */
                    'border-right': 'none',
                    'padding': '8px',
                },
                

            },
            
        }).render(document.getElementById("inventario"));
    }

    renderInventarioTable();

    
})


