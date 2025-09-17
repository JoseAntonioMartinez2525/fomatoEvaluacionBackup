
            document.addEventListener('DOMContentLoaded', function () {
                    const userEmail = "{{ Auth::user()->email }}"; // Obtén el email del usuario desde Blade

                    // Verifica si el email es el que esperas
                    if (userEmail === 'joma_18@alu.uabcs.mx') {
                        // Muestra el enlace
                        document.getElementById('jsonDataLink').classList.remove('d-none');
                    }
                });