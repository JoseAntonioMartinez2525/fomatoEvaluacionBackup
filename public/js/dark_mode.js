function toggleDarkMode() {
    const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
    if (!toggleDarkModeButton) return;

    // Función para actualizar el estado del botón
    function updateButtonState(isDarkMode) {
        document.body.classList.toggle('dark-mode', isDarkMode);
        document.body.classList.toggle('light-mode', !isDarkMode);

        toggleDarkModeButton.innerHTML = isDarkMode
            ? '<i class="fa-solid fa-sun"></i>&nbsp;Modo Claro'
            : '<i class="fa-solid fa-moon"></i>&nbsp;Modo Obscuro';

        toggleDarkModeButton.classList.toggle('btn-light', isDarkMode);
        toggleDarkModeButton.classList.toggle('btn-secondary', !isDarkMode);
    }

    // Estado inicial desde el servidor. La variable `window.isDarkModeGlobal` debe ser definida en las vistas Blade.
    const initialDarkMode = typeof window.isDarkModeGlobal !== 'undefined' ? window.isDarkModeGlobal : false;
    if (typeof window.isDarkModeGlobal === 'undefined') {
        console.warn('La variable global `window.isDarkModeGlobal` no está definida. Se usará el modo claro por defecto. Defínala en su vista Blade para persistir el estado del tema, por ejemplo: <script>window.isDarkModeGlobal = {{ session(\'dark_mode\', false) ? \'true\' : \'false\' }};<\/script>');
    }

    updateButtonState(initialDarkMode);

    toggleDarkModeButton.addEventListener('click', function () {
        const isDarkMode = document.body.classList.toggle('dark-mode');
        document.body.classList.toggle('light-mode', !isDarkMode);

        updateButtonState(isDarkMode);

        // Enviar la preferencia al servidor
        fetch('/toggle-dark-mode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                dark_mode: isDarkMode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error al guardar la preferencia del tema.');
            }
        })
        .catch(error => console.error('Error en la petición para cambiar el tema:', error));
    });
}
