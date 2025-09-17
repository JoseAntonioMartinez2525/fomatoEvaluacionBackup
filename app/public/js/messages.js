function showMessage(message, backgroundColor) {
    const messageDiv = document.createElement('div');
    messageDiv.textContent = message;
    messageDiv.style.position = 'fixed';
    messageDiv.style.top = '20px';
    messageDiv.style.left = '50%';
    messageDiv.style.transform = 'translateX(-50%)';
    messageDiv.style.backgroundColor = backgroundColor;
    messageDiv.style.color = 'white';
    messageDiv.style.padding = '10px 20px';
    messageDiv.style.borderRadius = '5px';
    messageDiv.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    messageDiv.style.zIndex = '1000';
    messageDiv.style.fontSize = '16px';
    document.body.appendChild(messageDiv);

    // Ocultar el mensaje después de 3 segundos
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}