<?php
include 'includes/conexion.php';
include 'includes/auth.php';
include 'includes/layout.php';
requiereLogin();

if (isset($_POST['guardar'])) {
    $incidente = (int)$_POST['id_incidente'];
    $analista = (int)$_POST['id_analista'];
    $comentario = trim($_POST['comentario']);

    $stmt = mysqli_prepare($conexion, 'INSERT INTO asignaciones (id_incidente, id_analista, comentario, fecha_asignacion) VALUES (?, ?, ?, NOW())');
    mysqli_stmt_bind_param($stmt, 'iis', $incidente, $analista, $comentario);
    mysqli_stmt_execute($stmt);
}
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = mysqli_prepare($conexion, 'DELETE FROM asignaciones WHERE id_asignacion=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}

$incidentes = mysqli_query($conexion, "SELECT id_incidente, titulo, severidad FROM incidentes ORDER BY fecha_reporte DESC");
$analistas = mysqli_query($conexion, 'SELECT id_analista, nombre, nivel FROM analistas ORDER BY nombre');
$res = mysqli_query($conexion, "SELECT a.id_asignacion, i.titulo, i.severidad, an.nombre, an.nivel, a.comentario, a.fecha_asignacion
                              FROM asignaciones a
                              JOIN incidentes i ON i.id_incidente = a.id_incidente
                              JOIN analistas an ON an.id_analista = a.id_analista
                              ORDER BY a.fecha_asignacion DESC");

renderHeader('Asignaciones', 'asignaciones');
?>
<h1>Asignaciones de Incidentes</h1>
<div class="card">
    <h2>Nueva asignación</h2>
    <form method="POST">
        <select name="id_incidente" required>
            <option value="">Selecciona incidente</option>
            <?php while ($i = mysqli_fetch_assoc($incidentes)): ?>
                <option value="<?php echo $i['id_incidente']; ?>">[<?php echo htmlspecialchars($i['severidad'], ENT_QUOTES, 'UTF-8'); ?>] <?php echo htmlspecialchars($i['titulo'], ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endwhile; ?>
        </select>
        <select name="id_analista" required>
            <option value="">Selecciona analista</option>
            <?php while ($a = mysqli_fetch_assoc($analistas)): ?>
                <option value="<?php echo $a['id_analista']; ?>"><?php echo htmlspecialchars($a['nombre'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($a['nivel'], ENT_QUOTES, 'UTF-8'); ?>)</option>
            <?php endwhile; ?>
        </select>
        <textarea name="comentario" placeholder="Comentario operativo y acciones iniciales" required></textarea>
        <input type="submit" name="guardar" value="Registrar asignación">
    </form>
</div>
<div class="card">
    <h2>Histórico de asignaciones</h2>
    <table>
        <thead><tr><th>Incidente</th><th>Severidad</th><th>Analista</th><th>Nivel</th><th>Comentario</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php while ($as = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo htmlspecialchars($as['titulo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><span class="badge badge-sev"><?php echo htmlspecialchars($as['severidad'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                <td><?php echo htmlspecialchars($as['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($as['nivel'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($as['comentario'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($as['fecha_asignacion'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><a class='accion' href='asignaciones.php?eliminar=<?php echo $as['id_asignacion']; ?>' onclick='return confirmarBorrado()'>Eliminar</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php renderFooter(); ?>
