function validarSocio() {
    let nombre = document.getElementById("nombre").value;
    let email = document.getElementById("email").value;

    if (nombre === "" || email === "") {
        alert("Nombre y email son obligatorios");
        return false;
    }
    return true;
}

function confirmarBorrado() {
    return confirm("¿Seguro que deseas eliminar este registro?");
}
