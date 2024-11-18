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
    const alertBox = document.getElementById('alert-box');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.display = 'none';
            console.log('Alerta oculta'); // Confirmación de que se oculta
        }, 5000);
    } else {
        console.log('No se encontró el alert-box'); // Para verificar que no se encuentre
    }
});

// const mostrar = document.getElementById('mostrar');
// mostrar.addEventListener('click', function(){
//     var campo = document.getElementById('contraseña');
//     var campoConf = document.getElementById('confirmarContraseña');
//     if(campo.type === "password" && campoConf.type === "password"){
//         campoConf.type = "text";
//         campo.type = "text";
//         mostrar.textContent = "Ocultar";
//     }else{
//         campoConf.type = "text";
//         campo.type = "password";
//         mostrar.textContent = "Mostrar";
//     }
// })
function togglePasswordVisibility(id) {
    var campo = document.getElementById(id);
    var icono = document.getElementById(id + "-toggle");
    if (campo.type === "password") {
        campo.type = "text";
        icono.textContent = "Ocultar";
    } else {
        campo.type = "password";
        icono.textContent = "Mostrar";
    }
}



const seleccion = document.getElementById('tipoUsuario');
if (seleccion) {
    seleccion.addEventListener('change', function () {
        const atributoContainer = document.getElementById('atributos-container');
        const atributoLabel = document.getElementById('atributos');

        atributoContainer.innerHTML = '';

        // Oculta el contenedor de atributos inicialmente
        atributoContainer.style.display = 'none';
        atributoLabel.style.display = 'none';

        if (this.value) {
            atributoContainer.style.display = 'block';
            atributoLabel.style.display = 'block';

            const rolNombre = this.options[this.selectedIndex].text; // Obtener el nombre del rol

            if (rolNombre === 'Administrador') {
                atributoContainer.innerHTML = '<p>El Administrador tiene todos los permisos automáticamente.</p>';
            } else {
                cargarPermisos(this.value); // Carga permisos dinámicamente
            }
        }
    });
}

function cargarPermisos(rolId) {
    const atributosContainer = document.getElementById('atributos-container');

    fetch('http://localhost/sistema/public/roles/' + rolId + '/permissions')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar permisos');
            }
            return response.json();
        })
        .then(data => {
            atributosContainer.innerHTML = ''; // Limpia el contenedor
            // Verifica si hay permisos
            if (data.length > 0) {
                data.forEach(permission => {
                    const checkbox = document.createElement('div');
                    checkbox.className = 'permission-checkbox';
                    checkbox.innerHTML = `
                    <input type="checkbox" name="permissions[]" class="form-check-input" value="${permission.id}">
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

function login(usuario, password){
    $.ajax({
        url: loginUrl,
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + localStorage.getItem('token') 
        },
        data: JSON.stringify({ usuario, password }),
        success: function(data) {
            // console.log(data);
            if (data.token) {
                // Guardar el token en localStorage
                localStorage.setItem('token', data.token);
                // Redirigir al dashboard o a la página principal
                window.location.href = 'http://localhost/sistema/public/inicio';
            } else {
                alert(data.message || 'Error en el inicio de sesión');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Hubo un problema con el inicio de sesión');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('#loginForm');

    if(loginForm){
        loginForm.addEventListener('submit', event => {
            event.preventDefault(); // Evitar recarga de la página
            const usuario = loginForm.querySelector('#usuarioIni').value;
            const password = loginForm.querySelector('#contraseñaIni').value;
            login(usuario, password);
        });
    }

})

