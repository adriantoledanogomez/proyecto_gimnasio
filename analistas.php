<?php
include 'includes/conexion.php';
include 'includes/auth.php';
requiereLogin();

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $nivel = $_POST['nivel'];
    $turno = $_POST['turno'];

    $stmt = mysqli_prepare($conexion, 'INSERT INTO analistas (nombre, email, nivel, turno) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $nombre, $email, $nivel, $turno);
    mysqli_stmt_execute($stmt);
}
if (isset($_POST['actualizar'])) {
    $id = (int)$_POST['id_analista'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $nivel = $_POST['nivel'];
    $turno = $_POST['turno'];

    $stmt = mysqli_prepare($conexion, 'UPDATE analistas SET nombre=?, email=?, nivel=?, turno=? WHERE id_analista=?');
    mysqli_stmt_bind_param($stmt, 'ssssi', $nombre, $email, $nivel, $turno, $id);
    mysqli_stmt_execute($stmt);
}
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM analistas WHERE id_analista=$id");
}

$edicion = null;
if (isset($_GET['editar'])) {
    $idEditar = (int)$_GET['editar'];
    $resEditar = mysqli_query($conexion, "SELECT * FROM analistas WHERE id_analista=$idEditar");
    $edicion = mysqli_fetch_assoc($resEditar);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Analistas SOC</title>
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
<h1>Gestión de Analistas</h1>
<div class="card">
    <h2><?php echo $edicion ? 'Editar analista' : 'Nuevo analista'; ?></h2>
    <form method="POST" onsubmit="return validarAnalista()">
        <input type="hidden" name="id_analista" value="<?php echo $edicion['id_analista'] ?? ''; ?>">
        <input type="text" id="analista_nombre" name="nombre" placeholder="Nombre" value="<?php echo $edicion['nombre'] ?? ''; ?>" required>
        <input type="email" id="analista_email" name="email" placeholder="Email" value="<?php echo $edicion['email'] ?? ''; ?>" required>
        <select name="nivel" required>
            <?php foreach (['L1', 'L2', 'L3'] as $nivel): ?>
                <option value="<?php echo $nivel; ?>" <?php echo (($edicion['nivel'] ?? '') === $nivel) ? 'selected' : ''; ?>><?php echo $nivel; ?></option>
            <?php endforeach; ?>
        </select>
        <select name="turno" required>
            <?php foreach (['Mañana', 'Tarde', 'Noche'] as $turno): ?>
                <option value="<?php echo $turno; ?>" <?php echo (($edicion['turno'] ?? '') === $turno) ? 'selected' : ''; ?>><?php echo $turno; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="<?php echo $edicion ? 'actualizar' : 'guardar'; ?>" value="<?php echo $edicion ? 'Actualizar analista' : 'Guardar analista'; ?>">
    </form>
</div>
<div class="card">
    <h2>Listado de analistas</h2>
    <table>
        <thead><tr><th>Nombre</th><th>Email</th><th>Nivel</th><th>Turno</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php
        $res = mysqli_query($conexion, 'SELECT * FROM analistas ORDER BY id_analista DESC');
        while ($a = mysqli_fetch_assoc($res)) {
            echo "<tr>
                <td>{$a['nombre']}</td>
                <td>{$a['email']}</td>
                <td>{$a['nivel']}</td>
                <td>{$a['turno']}</td>
                <td>
                    <a class='accion editar' href='analistas.php?editar={$a['id_analista']}'>Editar</a>
                    <a class='accion' href='analistas.php?eliminar={$a['id_analista']}' onclick='return confirmarBorrado()'>Eliminar</a>
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
