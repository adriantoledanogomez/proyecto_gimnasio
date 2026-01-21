<?php
include "includes/conexion.php";

if (isset($_POST['guardar'])) {
    $socio = $_POST['socio'];
    $clase = $_POST['clase'];

    mysqli_query($conexion,
        "INSERT INTO inscripciones (id_socio, id_clase, fecha_inscripcion)
         VALUES ($socio, $clase, CURDATE())");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripciones</title>
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

<h1>Inscripciones</h1>

<div class="card">
    <h2>Nueva inscripción</h2>
    <form method="POST">
        <select name="socio">
            <?php
            $socios = mysqli_query($conexion, "SELECT * FROM socios");
            while ($s = mysqli_fetch_assoc($socios)) {
                echo "<option value='{$s['id_socio']}'>{$s['nombre']}</option>";
            }
            ?>
        </select>

        <select name="clase">
            <?php
            $clases = mysqli_query($conexion, "SELECT * FROM clases");
            while ($c = mysqli_fetch_assoc($clases)) {
                echo "<option value='{$c['id_clase']}'>{$c['nombre_clase']}</option>";
            }
            ?>
        </select>

        <input type="submit" name="guardar" value="Inscribir socio">
    </form>
</div>

<div class="card">
    <h2>Listado de inscripciones</h2>
    <table>
        <thead>
            <tr>
                <th>Socio</th>
                <th>Clase</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $res = mysqli_query($conexion,
        "SELECT socios.nombre, clases.nombre_clase, inscripciones.fecha_inscripcion
         FROM inscripciones
         JOIN socios ON socios.id_socio = inscripciones.id_socio
         JOIN clases ON clases.id_clase = inscripciones.id_clase");

        while ($i = mysqli_fetch_assoc($res)) {
            echo "<tr>
                <td>{$i['nombre']}</td>
                <td>{$i['nombre_clase']}</td>
                <td>{$i['fecha_inscripcion']}</td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</div>

</body>
</html>

