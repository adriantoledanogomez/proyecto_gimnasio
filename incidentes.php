<?php
include 'includes/conexion.php';
include 'includes/auth.php';
requiereLogin();

if (isset($_POST['guardar'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $severidad = $_POST['severidad'];
    $estado = $_POST['estado'];
    $origen = $_POST['origen'];

    $stmt = mysqli_prepare($conexion, 'INSERT INTO incidentes (titulo, descripcion, severidad, estado, origen, fecha_reporte) VALUES (?, ?, ?, ?, ?, NOW())');
    mysqli_stmt_bind_param($stmt, 'sssss', $titulo, $descripcion, $severidad, $estado, $origen);
    mysqli_stmt_execute($stmt);
}

if (isset($_POST['actualizar'])) {
    $id = (int)$_POST['id_incidente'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $severidad = $_POST['severidad'];
    $estado = $_POST['estado'];
    $origen = $_POST['origen'];

    $stmt = mysqli_prepare($conexion, 'UPDATE incidentes SET titulo=?, descripcion=?, severidad=?, estado=?, origen=? WHERE id_incidente=?');
    mysqli_stmt_bind_param($stmt, 'sssssi', $titulo, $descripcion, $severidad, $estado, $origen, $id);
    mysqli_stmt_execute($stmt);
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM incidentes WHERE id_incidente=$id");
}

$edicion = null;
if (isset($_GET['editar'])) {
    $idEditar = (int)$_GET['editar'];
    $resEditar = mysqli_query($conexion, "SELECT * FROM incidentes WHERE id_incidente=$idEditar");
    $edicion = mysqli_fetch_assoc($resEditar);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Incidentes SOC</title>
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
<h1>Gestión de Incidentes</h1>
<div class="card">
    <h2><?php echo $edicion ? 'Editar incidente' : 'Nuevo incidente'; ?></h2>
    <form method="POST" onsubmit="return validarIncidente()">
        <input type="hidden" name="id_incidente" value="<?php echo $edicion['id_incidente'] ?? ''; ?>">
        <input type="text" id="titulo" name="titulo" placeholder="Título" value="<?php echo $edicion['titulo'] ?? ''; ?>" required>
        <textarea id="descripcion" name="descripcion" placeholder="Descripción" required><?php echo $edicion['descripcion'] ?? ''; ?></textarea>
        <select name="severidad" required>
            <?php foreach (['Baja', 'Media', 'Alta', 'Crítica'] as $sev): ?>
                <option value="<?php echo $sev; ?>" <?php echo (($edicion['severidad'] ?? '') === $sev) ? 'selected' : ''; ?>><?php echo $sev; ?></option>
            <?php endforeach; ?>
        </select>
        <select name="estado" required>
            <?php foreach (['Abierto', 'En investigación', 'Mitigado', 'Cerrado'] as $est): ?>
                <option value="<?php echo $est; ?>" <?php echo (($edicion['estado'] ?? '') === $est) ? 'selected' : ''; ?>><?php echo $est; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="origen" placeholder="Origen (SIEM, usuario, endpoint...)" value="<?php echo $edicion['origen'] ?? ''; ?>" required>
        <input type="submit" name="<?php echo $edicion ? 'actualizar' : 'guardar'; ?>" value="<?php echo $edicion ? 'Actualizar incidente' : 'Guardar incidente'; ?>">
    </form>
</div>

<div class="card">
    <h2>Listado de incidentes</h2>
    <table>
        <thead>
        <tr><th>Título</th><th>Severidad</th><th>Estado</th><th>Origen</th><th>Fecha</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php
        $res = mysqli_query($conexion, 'SELECT * FROM incidentes ORDER BY fecha_reporte DESC');
        while ($i = mysqli_fetch_assoc($res)) {
            echo "<tr>
                <td>{$i['titulo']}</td>
                <td>{$i['severidad']}</td>
                <td>{$i['estado']}</td>
                <td>{$i['origen']}</td>
                <td>{$i['fecha_reporte']}</td>
                <td>
                    <a class='accion editar' href='incidentes.php?editar={$i['id_incidente']}'>Editar</a>
                    <a class='accion' href='incidentes.php?eliminar={$i['id_incidente']}' onclick='return confirmarBorrado()'>Eliminar</a>
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
