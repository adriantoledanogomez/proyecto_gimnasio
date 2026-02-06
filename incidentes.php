<?php
include 'includes/conexion.php';
include 'includes/auth.php';
include 'includes/layout.php';
requiereLogin();

if (isset($_POST['guardar']) || isset($_POST['actualizar'])) {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $severidad = $_POST['severidad'];
    $estado = $_POST['estado'];
    $origen = trim($_POST['origen']);

    if (isset($_POST['guardar'])) {
        $stmt = mysqli_prepare($conexion, 'INSERT INTO incidentes (titulo, descripcion, severidad, estado, origen, fecha_reporte) VALUES (?, ?, ?, ?, ?, NOW())');
        mysqli_stmt_bind_param($stmt, 'sssss', $titulo, $descripcion, $severidad, $estado, $origen);
    } else {
        $id = (int)$_POST['id_incidente'];
        $stmt = mysqli_prepare($conexion, 'UPDATE incidentes SET titulo=?, descripcion=?, severidad=?, estado=?, origen=? WHERE id_incidente=?');
        mysqli_stmt_bind_param($stmt, 'sssssi', $titulo, $descripcion, $severidad, $estado, $origen, $id);
    }
    mysqli_stmt_execute($stmt);
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = mysqli_prepare($conexion, 'DELETE FROM incidentes WHERE id_incidente=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}

$edicion = null;
if (isset($_GET['editar'])) {
    $idEditar = (int)$_GET['editar'];
    $stmt = mysqli_prepare($conexion, 'SELECT * FROM incidentes WHERE id_incidente=?');
    mysqli_stmt_bind_param($stmt, 'i', $idEditar);
    mysqli_stmt_execute($stmt);
    $edicion = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$filtroEstado = $_GET['estado'] ?? '';
$filtroSeveridad = $_GET['severidad'] ?? '';
$where = [];
$params = [];
$types = '';

if ($filtroEstado !== '') {
    $where[] = 'estado = ?';
    $params[] = $filtroEstado;
    $types .= 's';
}
if ($filtroSeveridad !== '') {
    $where[] = 'severidad = ?';
    $params[] = $filtroSeveridad;
    $types .= 's';
}

$sql = 'SELECT * FROM incidentes';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY fecha_reporte DESC';
$stmtListado = mysqli_prepare($conexion, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmtListado, $types, ...$params);
}
mysqli_stmt_execute($stmtListado);
$res = mysqli_stmt_get_result($stmtListado);

renderHeader('Incidentes', 'incidentes');
?>
<h1>Gestión de Incidentes</h1>
<div class="card">
    <h2><?php echo $edicion ? 'Editar incidente' : 'Nuevo incidente'; ?></h2>
    <form method="POST" onsubmit="return validarIncidente()">
        <input type="hidden" name="id_incidente" value="<?php echo $edicion['id_incidente'] ?? ''; ?>">
        <input type="text" id="titulo" name="titulo" placeholder="Título" value="<?php echo htmlspecialchars($edicion['titulo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <textarea id="descripcion" name="descripcion" placeholder="Descripción" required><?php echo htmlspecialchars($edicion['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        <div class="inline-grid">
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
        </div>
        <input type="text" name="origen" placeholder="Origen (SIEM, usuario, endpoint...)" value="<?php echo htmlspecialchars($edicion['origen'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <input type="submit" name="<?php echo $edicion ? 'actualizar' : 'guardar'; ?>" value="<?php echo $edicion ? 'Actualizar incidente' : 'Guardar incidente'; ?>">
    </form>
</div>

<div class="card">
    <h2>Listado y filtros</h2>
    <form method="GET" class="inline-grid compact-form">
        <select name="estado">
            <option value="">Todos los estados</option>
            <?php foreach (['Abierto', 'En investigación', 'Mitigado', 'Cerrado'] as $est): ?>
                <option value="<?php echo $est; ?>" <?php echo $filtroEstado === $est ? 'selected' : ''; ?>><?php echo $est; ?></option>
            <?php endforeach; ?>
        </select>
        <select name="severidad">
            <option value="">Todas las severidades</option>
            <?php foreach (['Baja', 'Media', 'Alta', 'Crítica'] as $sev): ?>
                <option value="<?php echo $sev; ?>" <?php echo $filtroSeveridad === $sev ? 'selected' : ''; ?>><?php echo $sev; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <table>
        <thead>
        <tr><th>Título</th><th>Severidad</th><th>Estado</th><th>Origen</th><th>Fecha</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php while ($i = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo htmlspecialchars($i['titulo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><span class="badge badge-sev"><?php echo htmlspecialchars($i['severidad'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                <td><?php echo htmlspecialchars($i['estado'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($i['origen'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($i['fecha_reporte'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                    <a class='accion editar' href='incidentes.php?editar=<?php echo $i['id_incidente']; ?>'>Editar</a>
                    <a class='accion' href='incidentes.php?eliminar=<?php echo $i['id_incidente']; ?>' onclick='return confirmarBorrado()'>Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php renderFooter(); ?>
