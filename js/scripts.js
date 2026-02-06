function validarIncidente() {
    const titulo = document.getElementById('titulo')?.value.trim();
    const descripcion = document.getElementById('descripcion')?.value.trim();

    if (!titulo || !descripcion) {
        alert('Título y descripción son obligatorios.');
        return false;
    }
    return true;
}

function validarAnalista() {
    const nombre = document.getElementById('analista_nombre')?.value.trim();
    const email = document.getElementById('analista_email')?.value.trim();

    if (!nombre || !email) {
        alert('Nombre y email del analista son obligatorios.');
        return false;
    }
    return true;
}

function confirmarBorrado() {
    return confirm('¿Seguro que deseas eliminar este registro?');
}
