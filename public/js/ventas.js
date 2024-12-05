document.addEventListener('DOMContentLoaded', function() {

    $('#producto-select').select2({
        placeholder: 'Seleccione un producto', // Texto del placeholder
        allowClear: true // Permite limpiar la selección
    });
    // Función para calcular el monto total
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
    function addProductToList(productCod, productName, productPrice, quantity, unidad) {
        if (!validateProductQuantity(unidad, quantity)) {
            return; // Detener la ejecución si la cantidad no es válida
        }

        if (!quantity || quantity <= 0) {
            quantity = 1;
        }
    
        // Verificar si el producto es válido
        if (!productCod) {
            alert("Producto no seleccionado.");
            return;
        }
        
        console.log(`Agregando producto: ${productName}, ID: ${productCod}, Cantidad: ${quantity}`);
        const container = document.getElementById('product-list');
        const listItem = document.createElement('li');
        listItem.classList.add('list-group-item', 'product-list-item');
        listItem.setAttribute('data-precio', productPrice);
        listItem.setAttribute('data-cantidad', quantity);

        // Agregar el resumen del producto
        listItem.innerHTML = `
            ${productName} - ${quantity} x $${productPrice} = $${(productPrice * quantity).toFixed(2)}
            <button type="button" class="btn btn-danger float-end remove-product" style="background-color: red; --bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" title="Quitar">Quitar</button>
            
        `;
        console.log(`Producto agregado: ${productName}, Cantidad: ${quantity}`);

        const hiddenInputs = document.getElementById('hidden-inputs');
        if (quantity != null) {
            hiddenInputs.innerHTML += `<input type="hidden" name="producto_cod[]" value="${productCod}">
                                        <input type="hidden" name="cantidad[]" value="${quantity}">`;
        }else{
            console.warn(`Cantidad no válida para el producto código: ${productCod}`);
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

    // Función para manejar el evento de agregar producto
    function handleAddProduct() {
        const select = document.getElementById('producto-select');
        const cantidadInput = document.getElementById('cantidad-input');
        const productCod = select.value;
        const productName = select.options[select.selectedIndex]?.text;
        const productPrice = parseFloat(select.options[select.selectedIndex]?.getAttribute('data-precio')) || 0;
        const unidad = select.options[select.selectedIndex]?.getAttribute('data-unidad'); 
        
        let quantity = parseFloat(cantidadInput.value) || 1; 
    
        if (productCod && quantity > 0) {
            // Agregar el producto a la lista
            addProductToList(productCod, productName, productPrice, quantity, unidad);
    
            // Limpiar los campos de selección
            select.selectedIndex = 0;
            cantidadInput.value = ''; // Dejar el campo de cantidad vacío para que se ajuste a 1 al seleccionar otro producto
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
    
    $('#producto-select').on('select2:select', function() {
        document.getElementById('cantidad-input').value = 1;
    });

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

});
