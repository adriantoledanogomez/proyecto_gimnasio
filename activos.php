<?php
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
requiereLogin();

if (isset($_POST['guardar']) || isset($_POST['actualizar'])) {
    $nombre = trim($_POST['nombre_activo']);
    $tipo = trim($_POST['tipo']);
    $criticidad = $_POST['criticidad'];
    $propietario = trim($_POST['propietario']);

    if (isset($_POST['guardar'])) {
        $stmt = mysqli_prepare($conexion, 'INSERT INTO activos (nombre_activo, tipo, criticidad, propietario) VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssss', $nombre, $tipo, $criticidad, $propietario);
    } else {
        $id = (int)$_POST['id_activo'];
        $stmt = mysqli_prepare($conexion, 'UPDATE activos SET nombre_activo=?, tipo=?, criticidad=?, propietario=? WHERE id_activo=?');
        mysqli_stmt_bind_param($stmt, 'ssssi', $nombre, $tipo, $criticidad, $propietario, $id);
    }
    mysqli_stmt_execute($stmt);
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = mysqli_prepare($conexion, 'DELETE FROM activos WHERE id_activo=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}

$edicion = null;
if (isset($_GET['editar'])) {
    $idEditar = (int)$_GET['editar'];
    $stmt = mysqli_prepare($conexion, 'SELECT * FROM activos WHERE id_activo=?');
    mysqli_stmt_bind_param($stmt, 'i', $idEditar);
    mysqli_stmt_execute($stmt);
    $edicion = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$res = mysqli_query($conexion, 'SELECT * FROM activos ORDER BY id_activo DESC');

renderHeader('Activos', 'activos');
?>
<h1>Inventario de Activos</h1>
<div class="card">
    <h2><?php echo $edicion ? 'Editar activo' : 'Nuevo activo'; ?></h2>
    <form method="POST">
        <input type="hidden" name="id_activo" value="<?php echo $edicion['id_activo'] ?? ''; ?>">
        <input type="text" name="nombre_activo" placeholder="Nombre del activo" value="<?php echo htmlspecialchars($edicion['nombre_activo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <input type="text" name="tipo" placeholder="Tipo (Servidor, Firewall, SaaS...)" value="<?php echo htmlspecialchars($edicion['tipo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <select name="criticidad" required>
            <?php foreach (['Baja', 'Media', 'Alta', 'Crítica'] as $crit): ?>
                <option value="<?php echo $crit; ?>" <?php echo (($edicion['criticidad'] ?? '') === $crit) ? 'selected' : ''; ?>><?php echo $crit; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="propietario" placeholder="Propietario" value="<?php echo htmlspecialchars($edicion['propietario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <input type="submit" name="<?php echo $edicion ? 'actualizar' : 'guardar'; ?>" value="<?php echo $edicion ? 'Actualizar activo' : 'Guardar activo'; ?>">
    </form>
</div>
<div class="card">
    <h2>Listado de activos</h2>
    <table>
        <thead><tr><th>Activo</th><th>Tipo</th><th>Criticidad</th><th>Propietario</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php while ($a = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo htmlspecialchars($a['nombre_activo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($a['tipo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><span class="badge badge-sev"><?php echo htmlspecialchars($a['criticidad'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                <td><?php echo htmlspecialchars($a['propietario'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                    <a class='accion editar' href='activos.php?editar=<?php echo $a['id_activo']; ?>'>Editar</a>
                    <a class='accion' href='activos.php?eliminar=<?php echo $a['id_activo']; ?>' onclick='return confirmarBorrado()'>Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php renderFooter(); ?>
