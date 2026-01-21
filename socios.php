<?php
include "includes/conexion.php";

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    mysqli_query($conexion,
        "INSERT INTO socios (nombre, email, telefono, fecha_alta)
         VALUES ('$nombre','$email','$telefono',CURDATE())");
}

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM socios WHERE id_socio=$id");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Socios</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/scripts.js"></script>
</head>
<body>

<nav>
    <a href="index.php">Inicio</a>
    <a href="socios.php">Socios</a>
    <a href="clases.php">Clases</a>
    <a href="inscripciones.php">Inscripciones</a>
</nav>

<div class="container">

<h1>Gestión de Socios</h1>

<div class="card">
    <h2>Nuevo socio</h2>
    <form method="POST" onsubmit="return validarSocio()">
        <input type="text" name="nombre" id="nombre" placeholder="Nombre">
        <input type="email" name="email" id="email" placeholder="Email">
        <input type="text" name="telefono" placeholder="Teléfono">
        <input type="submit" name="guardar" value="Guardar socio">
    </form>
</div>

<div class="card">
    <h2>Listado de socios</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $resultado = mysqli_query($conexion, "SELECT * FROM socios");
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>
                <td>{$fila['nombre']}</td>
                <td>{$fila['email']}</td>
                <td>{$fila['telefono']}</td>
                <td>
                    <a class='accion' href='socios.php?eliminar={$fila['id_socio']}' onclick='return confirmarBorrado()'>Eliminar</a>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</div>

</body>
</html>

