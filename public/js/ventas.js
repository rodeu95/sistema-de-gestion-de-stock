document.addEventListener('DOMContentLoaded', function() {
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

    // Función para agregar un producto a la lista
    function addProductToList(productCod, productName, productPrice, quantity) {
        if (!productCod || quantity <= 0) {
            alert("Cantidad inválida o producto no seleccionado.");
            return;
        };
        console.log(`Agregando producto: ${productName}, ID: ${productCod}, Cantidad: ${quantity}`);
        const container = document.getElementById('product-list');
        const listItem = document.createElement('li');
        listItem.classList.add('list-group-item', 'product-list-item');
        listItem.setAttribute('data-precio', productPrice);
        listItem.setAttribute('data-cantidad', quantity);

        // Agregar el resumen del producto
        listItem.innerHTML = `
            ${productName} - ${quantity} x $${productPrice} = $${(productPrice * quantity).toFixed(2)}
            <button type="button" class="btn btn-danger btn-sm float-end remove-product">Eliminar</button>
            
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
        const quantity = parseFloat(cantidadInput.value) || 0;


        if (productCod && quantity > 0) {
            // Agregar el producto a la lista
            addProductToList(productCod, productName, productPrice, quantity);

            // Limpiar los campos de selección y cantidad
            select.selectedIndex = 0;
            cantidadInput.value = '';
        }else {
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
    
});
