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
// function togglePasswordVisibility(id) {
//     var campo = document.getElementById(id);
//     var icono = document.getElementById(id + "-toggle");
//     if (campo.type === "password") {
//         campo.type = "text";
//         icono.textContent = "Ocultar";
//     } else {
//         campo.type = "password";
//         icono.textContent = "Mostrar";
//     }
// }

const passwordInputIni = document.getElementById('passwordIni');
const passwordInputReg = document.getElementById('passwordReg');
const togglePasswordIni = document.getElementById('togglePasswordIni');
const togglePasswordReg = document.getElementById('togglePasswordReg');

togglePasswordIni.addEventListener('click', () => {
    // Cambia el tipo del input entre 'password' y 'text'
    const typeIni = passwordInputIni.type === 'password' ? 'text' : 'password';
    passwordInputIni.type = typeIni;

    // Cambia el ícono entre 'fa-eye' y 'fa-eye-slash'
    togglePasswordIni.classList.toggle('fa-eye');
    togglePasswordIni.classList.toggle('fa-eye-slash');

});

togglePasswordReg.addEventListener('click', () => {
    // Cambia el tipo del input entre 'password' y 'text'
    const typeReg = passwordInputReg.type === 'password' ? 'text' : 'password';
    passwordInputReg.type = typeReg;

    // Cambia el ícono entre 'fa-eye' y 'fa-eye-slash'
    togglePasswordReg.classList.toggle('fa-eye');
    togglePasswordReg.classList.toggle('fa-eye-slash');

});




document.addEventListener('DOMContentLoaded', () => {

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
                console.log(data);
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

    const loginForm = document.querySelector('#loginForm');

    if(loginForm){
        loginForm.addEventListener('submit', event => {
            event.preventDefault(); // Evitar recarga de la página
            const usuario = loginForm.querySelector('#usuarioIni').value;
            const password = loginForm.querySelector('#passwordIni').value;
            login(usuario, password);
        });
    }

})


document.addEventListener('DOMContentLoaded', () => {

    function register(name, email, usuario, password){
        $.ajax({
            url: "http://localhost/sistema/public/user/registro",
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('token') 
            },
            data: JSON.stringify({ name, email, usuario, password }),
            success: function(data) {
                console.log(data);
                if (data.token) {
                    // Guardar el token en localStorage
                    localStorage.setItem('token', data.token);
                    // Redirigir al dashboard o a la página principal
                    window.location.href = 'http://localhost/sistema/public/inicio';
                } else {
                    alert(data.message || 'Error en el registro');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Hubo un problema con el registro');
            }
        });
    }

    const registerForm = document.querySelector('#registerForm');

    if(registerForm){
        registerForm.addEventListener('submit', event => {
            event.preventDefault();
            const name = registerForm.querySelector('#name').value;
            const email = registerForm.querySelector('#email').value; // Evitar recarga de la página
            const usuario = registerForm.querySelector('#usuario').value;
            const password = registerForm.querySelector('#passwordReg').value;
            register(name, email, usuario, password);
        });
    }

})


const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

