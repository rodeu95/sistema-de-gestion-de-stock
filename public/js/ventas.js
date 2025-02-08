function isVentasCreatePage() {
    return window.location.pathname === "/sistema/public/ventas/create";
}

document.addEventListener('DOMContentLoaded', function() {
    let currentListItem = null;
    let scannedCode = ''; // Acumula el código escaneado
    let scanTimeout;

    const productName = document.getElementById('producto-nombre');
    const cantidadInput = document.getElementById('cantidad-input');
    const productCod = document.getElementById('codigo-input');
    const productPrice = document.getElementById('producto-precio');
    const unidad = document.getElementById('producto-unidad'); 
    const productStock = document.getElementById('producto-stock');

    // Escuchar entrada de teclas globalmente
    document.addEventListener('keypress', function (event) {
        // Ignorar si no es la vista de ventas.create
        if (!isVentasCreatePage()) return;

        // Detectar si es un número o letra válido
        if (event.key.length === 1) {
            scannedCode += event.key;
            
            // Reiniciar temporizador para limpiar el buffer si el usuario deja de escribir
            clearTimeout(scanTimeout);
            scanTimeout = setTimeout(() => scannedCode = '', 300);
        }

        // Si se presiona Enter, buscar el producto
        if (event.key === 'Enter') {
            event.preventDefault();

            if (scannedCode.trim() !== '') {
                buscarProducto(scannedCode.trim());
                scannedCode = '';
                console.log(scannedCode); 
            }
        }
    });

    function buscarProducto(codigo) {
        $.ajax({
            url: `http://localhost/sistema/public/api/productos/${codigo}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response) {
                $('#codigo-input').val(codigo);
                $('#producto-nombre').val(response.nombre || '');
                $('#producto-precio').val(response.precio_venta || '');
                $('#producto-stock').val(response.stock || '');
                $('#cantidad-input').prop('disabled', false);
                $('#producto-unidad').val(response.unidad);
                
                // Asignar cantidad por unidad
                if (response.unidad === 'UN') {
                    $('#cantidad-input').val(1);
                } else {
                    $('#cantidad-input').val(0.1);
                }

                // Agregar automáticamente el producto a la lista
                document.getElementById('add-product').click();
            },
            error: function () {
                alert('Producto no encontrado. Por favor, verifica el código.');
                $('#producto-nombre').val('');
                $('#producto-precio').val('');
                $('#producto-stock').val('');
                $('#cantidad-input').prop('disabled', true);
            }
        });
    }

        
        function calculateTotal() {
            let total = 0;

            // Recorrer todos los productos en la lista
            document.querySelectorAll('.product-list-item').forEach(function(item) {
                const precio = parseFloat(item.getAttribute('data-precio')) || 0;
                const cantidad = parseFloat(item.getAttribute('data-cantidad')) || 0;

                // Sumar el subtotal (precio * cantidad) al total
                total += precio * cantidad;
            });

            // Actualizar el campo del monto total
            document.getElementById('monto_total').value = total.toFixed(2);
        }

        function esFraccion(value){
            return value % 1 !== 0;
        }

        function validateProductQuantity(productoUnidad, cantidad) {
            if (productoUnidad === 'UN' && esFraccion(cantidad)) {
                alert("El producto no puede ser fraccionado.");
                return false; // Evitar agregar el producto
            }
            return true; // Permitir agregar el producto si la cantidad es válida
        }

        // Función para agregar un producto a la lista
        function addProductToList(codigo, nombre, precio, cantidad, unidadProducto) {
            document.getElementById('hidden-inputs').innerHTML = '';
            if (!validateProductQuantity(unidadProducto, cantidad)) {
                return; // Detener la ejecución si la cantidad no es válida
            }

            // Verificar si el producto es válido
            if (!codigo) {
                alert("Producto no seleccionado.");
                return;
            }
            
            console.log(`Agregando producto: ${nombre}, ID: ${codigo}, Cantidad: ${cantidad}`);

            const existingItem = document.querySelector(`.product-list-item[data-codigo="${codigo}"]`);
            if (existingItem) {
                existingItem.remove();
            }
            const container = document.getElementById('product-list');
            const listItem = document.createElement('li');
            listItem.classList.add('list-group-item', 'product-list-item');
            listItem.setAttribute('data-precio', precio);
            listItem.setAttribute('data-cantidad', cantidad);
            listItem.setAttribute('data-nombre', nombre);
            listItem.setAttribute('data-codigo', codigo);
            listItem.setAttribute('data-unidad', unidadProducto);

            // Agregar el resumen del producto
            listItem.innerHTML = `
                
                <div class="row">
                    <div class="col-lg-8">
                        <strong>${nombre}</strong> - ${cantidad} x $${precio} = $${(precio * cantidad).toFixed(2)}                        
                    </div>
                    <div class="col-lg-4">
                        <button type="button" class="btn float-end remove-product" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem; " title="Quitar"><i class="fa-solid fa-minus remove-product" style="color:#fff; font-size: .75rem;"></i></button>
                        
                        <button type="button" id="editar-cantidad" class="btn float-end edit-product" style= " margin-right:5px; --bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem; " title="Cantidad">Cantidad</button>
                    </div>
                </div>               
            `;
            // --bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;
            console.log(`Producto agregado: ${nombre}, Cantidad: ${cantidad}`);

            const hiddenInputs = document.getElementById('hidden-inputs');
            if (cantidad != null) {
                hiddenInputs.innerHTML += `<input type="hidden" name="producto_cod[]" value="${codigo}">
                                            <input type="hidden" name="cantidad[]" value="${cantidad}">`;
            }else{
                console.warn(`Cantidad no válida para el producto código: ${codigo}`);
            }
            // Agregar a la lista de productos
            container.appendChild(listItem);

            // Recalcular el total
            calculateTotal();

            // Agregar evento de eliminar
            listItem.querySelector('.remove-product').addEventListener('click', function() {
                listItem.remove();
                calculateTotal(); // Recalcular el total después de eliminar
            });
        }


        function validateStock(productStock, quantity) {
            if (quantity > productStock) {
                alert(`La cantidad seleccionada (${quantity}) excede el stock disponible (${productStock}).`);
                return false; // No permitir agregar el producto
            }
            return true; // La cantidad es válida
        }
        // Función para manejar el evento de agregar producto
        function handleAddProduct() {

            const codigo = productCod.value; // Obtiene el valor del input de código
            const nombre = productName.value; // Obtiene el valor del input de nombre
            const precio = parseFloat(productPrice.value) || 0; // Convierte el precio a número
            const stock = parseFloat(productStock.value) || 0; // Convierte el stock a número
            const unidadProducto = unidad.value; // Obtiene la unidad del producto

                
            let quantity = parseFloat(cantidadInput.value) || 1;        

            if (!validateStock(stock, quantity)) {
                return; // Detener si la cantidad supera el stock
            }
        
            if (productCod && quantity > 0) {
                // Agregar el producto a la lista
                addProductToList(codigo, nombre, precio, quantity, unidadProducto);
                cantidadInput.value = '';
                productCod.value = '';
                productName.value = '';

            } else {
                alert("Por favor, selecciona un producto y una cantidad válida.");
            }
        }

        // Función para validar el envío del formulario
        function validateForm(event) {
            const productList = document.querySelectorAll('.product-list-item');
            if (productList.length === 0) {
                event.preventDefault(); // Prevenir el envío
                alert("Debes agregar al menos un producto antes de enviar el formulario.");
            }
        }

        // Agregar eventos
        document.getElementById('add-product').addEventListener('click', handleAddProduct);
        
        // Validar el formulario al enviarlo
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', validateForm);
        }

        document.getElementById('ventasForm').addEventListener('submit', function(event) {
            
            const productoInputs = document.querySelectorAll('#hidden-inputs input[name="producto_cod[]"]');
            const cantidadInputs = document.querySelectorAll('#hidden-inputs input[name="cantidad[]"]');

            // Elimina valores vacíos o nulos
            
            productoInputs.forEach((input, index) => {
                if (!input.value || !cantidadInputs[index].value || cantidadInputs[index].value <= 0) {
                    input.remove();
                    cantidadInputs[index].remove();
                }
            });

            // Si no hay productos válidos, cancela el envío y muestra un mensaje
            if (document.querySelectorAll('#hidden-inputs input[name="producto_cod[]"]').length === 0) {
                event.preventDefault();
                alert("Debe agregar al menos un producto con cantidad válida.");
            }
        });

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('edit-product')) {
                currentListItem = event.target.closest('.product-list-item');
                const currentQuantity = parseFloat(currentListItem.getAttribute('data-cantidad')) || 1;
                document.getElementById('newQuantity').value = currentQuantity;
    
                // Mostrar el modal
                const editModal = new bootstrap.Modal(document.getElementById('editQuantityModal'));
                editModal.show();
            }
        });
    
        // Confirmar la edición de la cantidad
        document.getElementById('confirmEditQuantity').addEventListener('click', function () {
            const newQuantity = parseFloat(document.getElementById('newQuantity').value);
            if (!newQuantity || newQuantity <= 0) {
                alert("Ingrese una cantidad válida.");
                return;
            }
    
            // Actualizar la cantidad en el elemento de la lista
            const codigo = currentListItem.getAttribute('data-codigo');
            const nombre = currentListItem.getAttribute('data-nombre');
            const precio = parseFloat(currentListItem.getAttribute('data-precio')) || 0;
            const unidadProducto = currentListItem.getAttribute('data-unidad'); 
            const stock = productStock.value;

            if(!validateStock(stock, newQuantity)){
                return;
            }else{
                addProductToList(codigo, nombre, precio, newQuantity, unidadProducto, currentListItem);
            }

            $('#editQuantityModal').modal('hide');
        });
    

});
