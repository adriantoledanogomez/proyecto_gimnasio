<?php
include 'includes/conexion.php';
include 'includes/auth.php';
requiereLogin();

if (isset($_POST['guardar'])) {
    $incidente = (int)$_POST['id_incidente'];
    $analista = (int)$_POST['id_analista'];
    $comentario = $_POST['comentario'];

    $stmt = mysqli_prepare($conexion, 'INSERT INTO asignaciones (id_incidente, id_analista, comentario, fecha_asignacion) VALUES (?, ?, ?, NOW())');
    mysqli_stmt_bind_param($stmt, 'iis', $incidente, $analista, $comentario);
    mysqli_stmt_execute($stmt);
}
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM asignaciones WHERE id_asignacion=$id");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignaciones SOC</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/scripts.js"></script>
</head>
<body>
<nav>
    <a href="index.php">Dashboard</a>
    <a href="incidentes.php">Incidentes</a>
    <a href="activos.php">Activos</a>
    <a href="analistas.php">Analistas</a>
    <a href="asignaciones.php">Asignaciones</a>
    <a href="logout.php">Salir</a>
</nav>
<div class="container">
<h1>Asignaciones de Incidentes</h1>
<div class="card">
    <h2>Nueva asignación</h2>
    <form method="POST">
        <select name="id_incidente" required>
            <option value="">Selecciona incidente</option>
            <?php
            $incidentes = mysqli_query($conexion, "SELECT id_incidente, titulo, severidad FROM incidentes ORDER BY fecha_reporte DESC");
            while ($i = mysqli_fetch_assoc($incidentes)) {
                echo "<option value='{$i['id_incidente']}'>[{$i['severidad']}] {$i['titulo']}</option>";
            }
            ?>
        </select>
        <select name="id_analista" required>
            <option value="">Selecciona analista</option>
            <?php
            $analistas = mysqli_query($conexion, 'SELECT id_analista, nombre, nivel FROM analistas ORDER BY nombre');
            while ($a = mysqli_fetch_assoc($analistas)) {
                echo "<option value='{$a['id_analista']}'>{$a['nombre']} ({$a['nivel']})</option>";
            }
            ?>
        </select>
        <textarea name="comentario" placeholder="Comentario de asignación" required></textarea>
        <input type="submit" name="guardar" value="Registrar asignación">
    </form>
</div>
<div class="card">
    <h2>Histórico de asignaciones</h2>
    <table>
        <thead><tr><th>Incidente</th><th>Analista</th><th>Comentario</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php
        $res = mysqli_query($conexion, "SELECT a.id_asignacion, i.titulo, an.nombre, a.comentario, a.fecha_asignacion
                                      FROM asignaciones a
                                      JOIN incidentes i ON i.id_incidente = a.id_incidente
                                      JOIN analistas an ON an.id_analista = a.id_analista
                                      ORDER BY a.fecha_asignacion DESC");
        while ($as = mysqli_fetch_assoc($res)) {
            echo "<tr>
                <td>{$as['titulo']}</td>
                <td>{$as['nombre']}</td>
                <td>{$as['comentario']}</td>
                <td>{$as['fecha_asignacion']}</td>
                <td>
                    <a class='accion' href='asignaciones.php?eliminar={$as['id_asignacion']}' onclick='return confirmarBorrado()'>Eliminar</a>
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
