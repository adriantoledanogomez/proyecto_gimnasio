<?php
include 'includes/auth.php';
requiereLogin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel SOC</title>
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
    <h1>Centro de Operaciones de Seguridad (SOC)</h1>

    <div class="card bienvenida">
        Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>. Desde este panel puedes registrar incidencias,
        inventariar activos críticos, gestionar analistas y asignar responsables para la respuesta a incidentes.
    </div>

    <div class="grid-cards">
        <div class="card mini-card">
            <h2>Incidentes</h2>
            <p>CRUD completo para detección, severidad, estado y origen.</p>
        </div>
        <div class="card mini-card">
            <h2>Activos</h2>
            <p>Servidores, endpoints y servicios críticos bajo vigilancia.</p>
        </div>
        <div class="card mini-card">
            <h2>Analistas</h2>
            <p>Equipo SOC con turnos y niveles de escalado.</p>
        </div>
    </div>
</div>
</body>
</html>
