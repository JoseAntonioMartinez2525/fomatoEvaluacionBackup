function submitForm(url, formId) {
    const form = document.getElementById(formId);
    const formData = new FormData(form);

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    })
        .then(response => response.json())
        .then(data => alert(data.message))
        .catch(error => console.error('Error:', error));
}
