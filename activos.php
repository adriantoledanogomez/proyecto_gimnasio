<?php
include 'includes/conexion.php';
include 'includes/auth.php';
requiereLogin();

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre_activo'];
    $tipo = $_POST['tipo'];
    $criticidad = $_POST['criticidad'];
    $propietario = $_POST['propietario'];

    $stmt = mysqli_prepare($conexion, 'INSERT INTO activos (nombre_activo, tipo, criticidad, propietario) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $nombre, $tipo, $criticidad, $propietario);
    mysqli_stmt_execute($stmt);
}
if (isset($_POST['actualizar'])) {
    $id = (int)$_POST['id_activo'];
    $nombre = $_POST['nombre_activo'];
    $tipo = $_POST['tipo'];
    $criticidad = $_POST['criticidad'];
    $propietario = $_POST['propietario'];

    $stmt = mysqli_prepare($conexion, 'UPDATE activos SET nombre_activo=?, tipo=?, criticidad=?, propietario=? WHERE id_activo=?');
    mysqli_stmt_bind_param($stmt, 'ssssi', $nombre, $tipo, $criticidad, $propietario, $id);
    mysqli_stmt_execute($stmt);
}
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM activos WHERE id_activo=$id");
}

$edicion = null;
if (isset($_GET['editar'])) {
    $idEditar = (int)$_GET['editar'];
    $resEditar = mysqli_query($conexion, "SELECT * FROM activos WHERE id_activo=$idEditar");
    $edicion = mysqli_fetch_assoc($resEditar);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Activos SOC</title>
    <link rel="stylesheet" href="css/estilos.css">
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
<h1>Inventario de Activos</h1>
<div class="card">
    <h2><?php echo $edicion ? 'Editar activo' : 'Nuevo activo'; ?></h2>
    <form method="POST">
        <input type="hidden" name="id_activo" value="<?php echo $edicion['id_activo'] ?? ''; ?>">
        <input type="text" name="nombre_activo" placeholder="Nombre del activo" value="<?php echo $edicion['nombre_activo'] ?? ''; ?>" required>
        <input type="text" name="tipo" placeholder="Tipo (Servidor, Firewall, SaaS...)" value="<?php echo $edicion['tipo'] ?? ''; ?>" required>
        <select name="criticidad" required>
            <?php foreach (['Baja', 'Media', 'Alta', 'Crítica'] as $crit): ?>
                <option value="<?php echo $crit; ?>" <?php echo (($edicion['criticidad'] ?? '') === $crit) ? 'selected' : ''; ?>><?php echo $crit; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="propietario" placeholder="Propietario" value="<?php echo $edicion['propietario'] ?? ''; ?>" required>
        <input type="submit" name="<?php echo $edicion ? 'actualizar' : 'guardar'; ?>" value="<?php echo $edicion ? 'Actualizar activo' : 'Guardar activo'; ?>">
    </form>
</div>
<div class="card">
    <h2>Listado de activos</h2>
    <table>
        <thead><tr><th>Activo</th><th>Tipo</th><th>Criticidad</th><th>Propietario</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php
        $res = mysqli_query($conexion, 'SELECT * FROM activos ORDER BY id_activo DESC');
        while ($a = mysqli_fetch_assoc($res)) {
            echo "<tr>
                <td>{$a['nombre_activo']}</td>
                <td>{$a['tipo']}</td>
                <td>{$a['criticidad']}</td>
                <td>{$a['propietario']}</td>
                <td>
                    <a class='accion editar' href='activos.php?editar={$a['id_activo']}'>Editar</a>
                    <a class='accion' href='activos.php?eliminar={$a['id_activo']}' onclick='return confirmarBorrado()'>Eliminar</a>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</div>
<script src="js/scripts.js"></script>
</body>
</html>
