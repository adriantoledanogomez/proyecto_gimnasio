<?php
include 'includes/conexion.php';
include 'includes/auth.php';
include 'includes/layout.php';
requiereLogin();

$totalIncidentes = (int)(mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) total FROM incidentes"))['total'] ?? 0);
$incidentesAbiertos = (int)(mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) total FROM incidentes WHERE estado IN ('Abierto','En investigación')"))['total'] ?? 0);
$activosCriticos = (int)(mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) total FROM activos WHERE criticidad IN ('Alta','Crítica')"))['total'] ?? 0);
$analistasActivos = (int)(mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) total FROM analistas"))['total'] ?? 0);

$ultimos = mysqli_query($conexion, "SELECT titulo, severidad, estado, fecha_reporte FROM incidentes ORDER BY fecha_reporte DESC LIMIT 5");

renderHeader('Dashboard', 'dashboard');
?>
<section class="hero">
    <h1>Centro de Operaciones de Seguridad</h1>
    <p>Controla el ciclo completo de incidentes para una pyme: detección, asignación, seguimiento y cierre.</p>
</section>

<section class="kpi-grid">
    <article class="kpi-card">
        <h3>Incidentes totales</h3>
        <p><?php echo $totalIncidentes; ?></p>
    </article>
    <article class="kpi-card warning">
        <h3>Incidentes abiertos</h3>
        <p><?php echo $incidentesAbiertos; ?></p>
    </article>
    <article class="kpi-card danger">
        <h3>Activos críticos</h3>
        <p><?php echo $activosCriticos; ?></p>
    </article>
    <article class="kpi-card success">
        <h3>Analistas</h3>
        <p><?php echo $analistasActivos; ?></p>
    </article>
</section>

<section class="card">
    <h2>Últimos incidentes reportados</h2>
    <table>
        <thead><tr><th>Título</th><th>Severidad</th><th>Estado</th><th>Fecha</th></tr></thead>
        <tbody>
        <?php while ($fila = mysqli_fetch_assoc($ultimos)): ?>
            <tr>
                <td><?php echo htmlspecialchars($fila['titulo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><span class="badge badge-sev"><?php echo htmlspecialchars($fila['severidad'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                <td><?php echo htmlspecialchars($fila['estado'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($fila['fecha_reporte'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</section>
<?php renderFooter(); ?>
