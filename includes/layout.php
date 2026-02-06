<?php
function renderHeader(string $title, string $active): void
{
    $usuario = htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Operador', ENT_QUOTES, 'UTF-8');
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?> | SOC Manager</title>
        <link rel="stylesheet" href="css/estilos.css">
        <script src="js/scripts.js" defer></script>
    </head>
    <body>
    <nav class="topbar">
        <div class="brand">
            <span class="brand-logo">🛡️</span>
            <div>
                <strong>SOC Manager</strong>
                <small>Gestión de Incidencias</small>
            </div>
        </div>
        <div class="menu-links">
            <a class="<?php echo $active === 'dashboard' ? 'active' : ''; ?>" href="index.php">Dashboard</a>
            <a class="<?php echo $active === 'incidentes' ? 'active' : ''; ?>" href="incidentes.php">Incidentes</a>
            <a class="<?php echo $active === 'activos' ? 'active' : ''; ?>" href="activos.php">Activos</a>
            <a class="<?php echo $active === 'analistas' ? 'active' : ''; ?>" href="analistas.php">Analistas</a>
            <a class="<?php echo $active === 'asignaciones' ? 'active' : ''; ?>" href="asignaciones.php">Asignaciones</a>
        </div>
        <div class="user-area">
            <span><?php echo $usuario; ?></span>
            <a href="logout.php">Salir</a>
        </div>
    </nav>
    <main class="container">
    <?php
}

function renderFooter(): void
{
    ?>
    </main>
    </body>
    </html>
    <?php
}
?>
