function validarContraseña() {
    var pass = document.getElementById("password").value;
    var confirmarPass = document.getElementById("confirmPassword").value;

    if (pass != confirmarPass) {
        alert("Las contraseñas no coinciden");
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    const userId = document.getElementById('userId')?.value;
    const seleccion = document.getElementById('tipoUsuario');
    if (seleccion) {

        const atributoContainer = document.getElementById('atributos-container');
        const atributoLabel = document.getElementById('atributos');

        function mostrarPermisos() {
            atributoContainer.innerHTML = '';

            // Oculta el contenedor de atributos inicialmente
            atributoContainer.style.display = 'none';
            atributoLabel.style.display = 'none';

            if (seleccion.value) {
                atributoContainer.style.display = 'block';
                atributoLabel.style.display = 'block';

                const rolNombre = seleccion.options[seleccion.selectedIndex].text; // Obtener el nombre del rol

                if (rolNombre === 'Administrador') {
                    atributoContainer.innerHTML = '<p>El Administrador tiene todos los permisos automáticamente.</p>';
                } else {
                    cargarPermisos(seleccion.value, userId); // Carga permisos dinámicamente
                }
            }
        }

        // Ejecutar la función al cargar la página (para mostrar permisos si ya hay un rol seleccionado)
        mostrarPermisos();

        // Agregar el evento para detectar cambios en la selección
        seleccion.addEventListener('change', mostrarPermisos);
    }
});


function cargarPermisos(rolId, userId) {
    const atributosContainer = document.getElementById('atributos-container');
    let url = `http://localhost/sistema/public/roles/${rolId}/permissions`;
    if (userId) {
        url += `/${userId}`;
    }

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar permisos');
            }
            return response.json();
        })
        .then(data => {
            atributosContainer.innerHTML = ''; // Limpia el contenedor
            console.log(data);

            // Verifica si hay permisos
            if (data.permissions.length > 0) {
                data.permissions.forEach(permission => {
                    const checkbox = document.createElement('div');
                    checkbox.className = 'permission-checkbox';
                    const isChecked = data.permisosUsuario.includes(permission.id) ? 'checked' : '';
                    checkbox.innerHTML = `
                    <input type="checkbox" name="permissions[]" class="form-check-input" value="${permission.id}" ${isChecked}>
                    <label class="form-check-label">${permission.description}</label><br>
                    `;
                    atributosContainer.appendChild(checkbox);
                });
                atributosContainer.style.display = 'block'; // Muestra el contenedor de atributos
            } else {
                atributosContainer.innerHTML = '<p>No hay permisos disponibles para este rol.</p>';
            }
        })
        .catch(error => console.error('Error al cargar permisos:', error));
}

const passwordInput = document.getElementById('password');
const passwordInputConfirm = document.getElementById('confirmPassword');
const togglePassword = document.getElementById('togglePassword');
const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');

togglePassword.addEventListener('click', () => {
    // Cambia el tipo del input entre 'password' y 'text'
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;

    // Cambia el ícono entre 'fa-eye' y 'fa-eye-slash'
    togglePassword.classList.toggle('fa-eye');
    togglePassword.classList.toggle('fa-eye-slash');

});

togglePasswordConfirm.addEventListener('click', () => {
    // Cambia el tipo del input entre 'password' y 'text'
    const type = passwordInputConfirm.type === 'password' ? 'text' : 'password';
    passwordInputConfirm.type = type;

    // Cambia el ícono entre 'fa-eye' y 'fa-eye-slash'
    togglePasswordConfirm.classList.toggle('fa-eye');
    togglePasswordConfirm.classList.toggle('fa-eye-slash');

});