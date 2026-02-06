<?php
include 'includes/conexion.php';
include 'includes/auth.php';
include 'includes/layout.php';
requiereLogin();

if (isset($_POST['guardar']) || isset($_POST['actualizar'])) {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $nivel = $_POST['nivel'];
    $turno = $_POST['turno'];

    if (isset($_POST['guardar'])) {
        $stmt = mysqli_prepare($conexion, 'INSERT INTO analistas (nombre, email, nivel, turno) VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssss', $nombre, $email, $nivel, $turno);
    } else {
        $id = (int)$_POST['id_analista'];
        $stmt = mysqli_prepare($conexion, 'UPDATE analistas SET nombre=?, email=?, nivel=?, turno=? WHERE id_analista=?');
        mysqli_stmt_bind_param($stmt, 'ssssi', $nombre, $email, $nivel, $turno, $id);
    }
    mysqli_stmt_execute($stmt);
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = mysqli_prepare($conexion, 'DELETE FROM analistas WHERE id_analista=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}

$edicion = null;
if (isset($_GET['editar'])) {
    $idEditar = (int)$_GET['editar'];
    $stmt = mysqli_prepare($conexion, 'SELECT * FROM analistas WHERE id_analista=?');
    mysqli_stmt_bind_param($stmt, 'i', $idEditar);
    mysqli_stmt_execute($stmt);
    $edicion = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$res = mysqli_query($conexion, 'SELECT * FROM analistas ORDER BY id_analista DESC');

renderHeader('Analistas', 'analistas');
?>
<h1>Gestión de Analistas</h1>
<div class="card">
    <h2><?php echo $edicion ? 'Editar analista' : 'Nuevo analista'; ?></h2>
    <form method="POST" onsubmit="return validarAnalista()">
        <input type="hidden" name="id_analista" value="<?php echo $edicion['id_analista'] ?? ''; ?>">
        <input type="text" id="analista_nombre" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($edicion['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <input type="email" id="analista_email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($edicion['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <div class="inline-grid">
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
        </div>
        <input type="submit" name="<?php echo $edicion ? 'actualizar' : 'guardar'; ?>" value="<?php echo $edicion ? 'Actualizar analista' : 'Guardar analista'; ?>">
    </form>
</div>
<div class="card">
    <h2>Listado de analistas</h2>
    <table>
        <thead><tr><th>Nombre</th><th>Email</th><th>Nivel</th><th>Turno</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php while ($a = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo htmlspecialchars($a['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($a['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($a['nivel'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($a['turno'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                    <a class='accion editar' href='analistas.php?editar=<?php echo $a['id_analista']; ?>'>Editar</a>
                    <a class='accion' href='analistas.php?eliminar=<?php echo $a['id_analista']; ?>' onclick='return confirmarBorrado()'>Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php renderFooter(); ?>
