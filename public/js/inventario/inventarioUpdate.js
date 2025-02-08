let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.getElementById('categoria-select').addEventListener('change', function(){
    const categoriaId = document.getElementById('categoria-select').value;
    const filteredContainer = document.getElementById('filtered-products');

    // Filtrar productos por categoría
    const filteredProducts = productos.filter(producto => producto.categoria_id == categoriaId);

    // Renderizar productos filtrados
    if (filteredProducts.length > 0) {
        filteredContainer.innerHTML = filteredProducts.map(producto => `
            <div class="product-entry row g-3 mb-3">
                <div class="col-md-8">
                    <input type="hidden" name="producto_cod[]" value="${producto.codigo}">
                    <span class="form-control-plaintext">${producto.nombre}</span>
                </div>
                <div class="col-md-4">
                    <input type="number" name="cantidad[]" class="form-control" placeholder="Cantidad" min="1" required>
                </div>
            </div>
        `).join('');
    } else {
        filteredContainer.innerHTML = '<p class="text-center text-muted">No se encontraron productos en esta categoría.</p>';
    }
});


document.addEventListener('DOMContentLoaded', () => {
    // const editStockModal = document.getElementById('editStockModal');
    // editStockModal.addEventListener('show.bs.modal', (event) => {
    //     const button = event.relatedTarget; // Botón que activó el modal
    //     const productoCodigo = button.getAttribute('data-codigo');
    //     const productoNombre = button.getAttribute('data-nombre');

    //     // Rellenar el modal con los datos del producto
    //     document.getElementById('modal-producto-cod').value = productoCodigo;
    //     document.getElementById('modal-producto-nombre').value = productoNombre;
    //     document.getElementById('modal-cantidad').value = 1;

    //     

    // });

    $('#editStockModal').on('show.bs.modal', function(event){
        const button = $(event.relatedTarget); 
        const codigo = button.data('codigo');
        const nombre = button.data('nombre');

        console.log(codigo);
        console.log(nombre);
        editInventarioUrlFinal = editInventarioUrl.replace(':codigo', codigo);

        $.ajax({
            url: editInventarioUrlFinal,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }, 
            method: 'GET',
            success: function(response){
                console.log(response);
                $('#modal-producto-nombre').val(nombre);
                $('#modal-producto-codigo').val(codigo);
                $('#modal-cantidad').val('');

            }
        })
    })
    

    $('#editStockModal').on('submit', function (e){
        e.preventDefault();

        const formData = {
            cantidad: $('#modal-cantidad').val(),
        };
        const codigo = $('#modal-producto-codigo').val();
        const updateInventarioUrlFinal = updateInventarioUrl.replace(':codigo', codigo);
        console.log(updateInventarioUrlFinal);

        $.ajax({
            url: updateInventarioUrlFinal, 
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'X-CSRF-TOKEN': csrfToken
            },
            data: formData,
            success: function (response) {
                console.log(response);
                if(response){
                    $('#editStockModal').modal('hide'); // Cierra el modal                
                    Swal.fire({
                        icon: 'success',
                        title: '¡Stock actualizado!',
                        text: 'El stock del producto se ha actualizado correctamente.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.reload(); // Recargar la página después de 2 segundos
                    });
                }
                
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Muestra los errores de la respuesta
            }

        }) 
    });
});