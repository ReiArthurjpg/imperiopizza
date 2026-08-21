// Ano atual
document.addEventListener('DOMContentLoaded', () => {
    const yearEl = document.getElementById('currentYear');
    if (yearEl) {
        yearEl.textContent = new Date().getFullYear();
    }
});

// Mostrar/Ocultar Senha simples
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('ph-eye', 'ph-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('ph-eye-slash', 'ph-eye');
    }
}
