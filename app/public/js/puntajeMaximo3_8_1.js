// puntajeMaxForm3_8_1
function guardarEnSesion(puntaje) {
    sessionStorage.setItem('puntajeMaximo', puntaje);
}

function obtenerPuntajeSesion() {
    return sessionStorage.getItem('puntajeMaximo');
}

// Opcional: Mostrar el puntaje almacenado para depuración
function mostrarPuntajeSesion() {
    console.log('Puntaje desde sesión:', obtenerPuntajeSesion());
}
