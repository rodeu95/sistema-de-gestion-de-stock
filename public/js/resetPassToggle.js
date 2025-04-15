const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

const passwordResetInput = document.getElementById('password-confirm');
const togglePasswordResetConfirm = document.getElementById('togglePasswordResetConfirm');

togglePassword.addEventListener('click', () => {
    // Cambia el tipo del input entre 'password' y 'text'
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;

    // Cambia el ícono entre 'fa-eye' y 'fa-eye-slash'
    togglePassword.classList.toggle('fa-eye');
    togglePassword.classList.toggle('fa-eye-slash');

});

togglePasswordResetConfirm.addEventListener('click', () => {
    // Cambia el tipo del input entre 'password' y 'text'
    const type = passwordResetInput.type === 'password' ? 'text' : 'password';
    passwordResetInput.type = type;
    
    // Cambia el ícono entre 'fa-eye' y 'fa-eye-slash'
    togglePasswordResetConfirm.classList.toggle('fa-eye');
    togglePasswordResetConfirm.classList.toggle('fa-eye-slash');

});