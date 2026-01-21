<?php
include "includes/conexion.php";

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre_clase'];
    $horario = $_POST['horario']; // fecha y hora
    $instructor = $_POST['instructor'];

    mysqli_query($conexion,
        "INSERT INTO clases (nombre_clase, horario, instructor)
         VALUES ('$nombre','$horario','$instructor')");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clases</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<nav>
    <a href="index.php">Inicio</a>
    <a href="socios.php">Socios</a>
    <a href="clases.php">Clases</a>
    <a href="inscripciones.php">Inscripciones</a>
</nav>

<div class="container">

<h1>Gestión de Clases</h1>

<div class="card">
    <h2>Nueva clase</h2>

    <form method="POST">
        <input type="text" name="nombre_clase" placeholder="Nombre de la clase" required>

        <input type="datetime-local" name="horario" required>

        <input type="text" name="instructor" placeholder="Instructor" required>

        <input type="submit" name="guardar" value="Guardar clase">
    </form>
</div>

<div class="card">
    <h2>Listado de clases</h2>

    <table>
        <thead>
            <tr>
                <th>Clase</th>
                <th>Fecha y hora</th>
                <th>Instructor</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $res = mysqli_query($conexion, "SELECT * FROM clases");
        while ($c = mysqli_fetch_assoc($res)) {

            $fecha = date("d/m/Y H:i", strtotime($c['horario']));

            echo "<tr>
                <td>{$c['nombre_clase']}</td>
                <td>$fecha</td>
                <td>{$c['instructor']}</td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</div>

</body>
</html>


