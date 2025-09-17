//     document.addEventListener('DOMContentLoaded', function () {
function toggleDarkMode() {
    const toggleDarkModeButton = document.getElementById('toggle-dark-mode');

    // Guardar la preferencia del usuario en localStorage
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        document.body.classList.add(currentTheme);
        if (currentTheme === 'dark') {
            toggleDarkModeButton.classList.replace('btn-secondary', 'btn-primary');
            toggleDarkModeButton.innerHTML = '<i class="fa-solid fa-sun"></i>&nbspModo Claro';
        }
    }

    toggleDarkModeButton.addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');
        let theme = 'light';

        if (document.body.classList.contains('dark-mode')) {
            theme = 'dark';
            toggleDarkModeButton.classList.replace('btn-secondary', 'btn-light');
            toggleDarkModeButton.innerHTML = '<i class="fa-solid fa-sun"></i>&nbspModo Claro';
        } else {
            toggleDarkModeButton.classList.replace('btn-primary', 'btn-secondary');
            toggleDarkModeButton.innerHTML = '<i class="fa-solid fa-moon"></i>&nbspModo Obscuro';
        }
        localStorage.setItem('theme', theme);
    });
}
// });