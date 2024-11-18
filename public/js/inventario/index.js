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
                    sort: true,
                },
                {
                    name: gridjs.html(`<span title="Stock disponible">Stock</span>`),
                    sort: true,
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
                            (producto.unidad === 'UN' 
                                ? `$${precioVenta.toFixed(2)} x UN` 
                                : `$${precioVenta.toFixed(2)} x KG`),
                            stock,
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
            },
            style: {
                table: {
                    'border-collapse': 'collapse',
                    'border': '1px solid #ddd',
                    'text-align': 'center',
                },
                th: {
                    'border': '1px solid #ddd', /* Borde para las celdas del encabezado */
                    'padding': '8px',
                },
                td: {
                    'border': '1px solid #ddd', /* Borde para las celdas del contenido */
                    'padding': '8px',
                },
            },
            
        }).render(document.getElementById("inventario"));
    }

    renderInventarioTable()
})

