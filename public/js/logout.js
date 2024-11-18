document.addEventListener('DOMContentLoaded', () => {
    const logoutButton = document.querySelector('#logout-button');
    const logoutForm = document.querySelector('#logout-form');

    if (logoutButton && logoutForm) {
        logoutButton.addEventListener('click', (event) => {
            event.preventDefault(); // Evita que el formulario se envíe por defecto

            $.ajax({
                url: logoutForm.action,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // Elimina el token de localStorage (si usas JWT o similar)
                    localStorage.removeItem('token');

                    // Redirige al login
                    window.location.href = 'http://localhost/sistema/public/users/login'; // Cambia según tu ruta de login
                },
                error: function(xhr, status, error) {
                    console.error('Error al cerrar sesión:', error);
                    alert('Hubo un problema al cerrar sesión.');
                }
            });
        });
    }
});