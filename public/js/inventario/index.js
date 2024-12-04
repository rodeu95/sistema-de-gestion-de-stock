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
                    
                },
                {
                    name: gridjs.html(`<span title="IVA %">IVA %</span>`),
                    
                },
                {
                    name: gridjs.html(`<span title="Porcentaje de utilidad">% Utilidad</span>`),
                    
                },
                {
                    name: gridjs.html(`<span title="Precio de venta">Precio Venta</span>`),
                    formatter: (cell, row) => {
                        const precioVenta = parseFloat(row.cells[6].data);
                        const unidad = row.cells[8].data;
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
                        const iva = parseFloat(producto.iva) || 0;
                        const utilidad = parseFloat(producto.utilidad) || 0;
                        const stock = parseFloat(producto.stock) || 0;
                        
                        return [
                            producto.codigo,
                            producto.nombre,
                            producto.categoria ? producto.categoria.nombre : 'Sin categoría',
                            (producto.unidad === 'UN' 
                                ? `${precioCosto.toFixed(2)} x UN` 
                                : `${precioCosto.toFixed(2)} x KG`),
                            `${iva}%`,
                            `${utilidad}%`,
                            `${precioVenta.toFixed(2)}`,
                            stock,
                            producto.unidad,
                            producto.lotes && producto.lotes.length > 0 
                                ? producto.lotes.map(lote => 
                                    `Lote: ${lote.numero_lote}, Vence: ${new Date(lote.fecha_vencimiento).toLocaleDateString('es-AR')}`
                                ).join('<br>') 
                                : 'Sin lotes'
                        ];
                        
                    });
                }
            },
            resizable: true,
            search: true,
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
                    'border': '1px solid #ddd',
                    'text-align': 'center',
                    
                },
                th: {
                    'border': 'none',/* Borde para las celdas del encabezado */
                    'padding': '8px',
                },
                td: {
                    'border': 'none',
                    'padding': '8px',
                },
                

            },
            
        }).render(document.getElementById("inventario"));
    }

    renderInventarioTable()
})


