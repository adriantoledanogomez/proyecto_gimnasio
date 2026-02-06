<?php
include 'includes/conexion.php';
include 'includes/auth.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if (isset($_POST['acceder'])) {
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conexion, 'SELECT id_usuario, nombre, password_hash FROM usuarios WHERE usuario = ?');
    mysqli_stmt_bind_param($stmt, 's', $usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $fila = mysqli_fetch_assoc($resultado);

    if ($fila && password_verify($password, $fila['password_hash'])) {
        $_SESSION['usuario_id'] = $fila['id_usuario'];
        $_SESSION['usuario_nombre'] = $fila['nombre'];
        header('Location: index.php');
        exit;
    }

    $error = 'Credenciales inválidas.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SOC Manager</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="container login-wrapper">
    <div class="card login-card">
        <h1>SOC Manager</h1>
        <p class="muted">Panel profesional de gestión de incidencias para pequeñas empresas.</p>
        <?php if ($error): ?>
            <div class="alerta-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="submit" name="acceder" value="Entrar al panel">
        </form>
        <small>Demo: <strong>admin</strong> / <strong>admin123</strong></small>
    </div>
</div>
</body>
</html>
