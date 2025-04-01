<?php
require_once 'functions/core.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT cotizaciones.*, clientes.empresa, gruas.marca, gruas.tonelaje 
            FROM cotizaciones 
            JOIN clientes ON cotizaciones.cliente_id = clientes.id
            JOIN gruas ON cotizaciones.grua_id = gruas.id
            WHERE cotizaciones.id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $cotizacion = $result->fetch_assoc();
        ?>
        <div>
            <p><strong>Atención:</strong> <?= htmlspecialchars($cotizacion['atencion']) ?></p>
            <p><strong>Empresa:</strong> <?= htmlspecialchars($cotizacion['empresa']) ?></p>
            <p><strong>Grúa:</strong> Grúa <?= htmlspecialchars($cotizacion['marca']) ?>, <?= htmlspecialchars($cotizacion['tonelaje']) ?> toneladas</p>
            <p><strong>Domicilio del Servicio:</strong> <?= htmlspecialchars($cotizacion['domicilio_servicio']) ?></p>
            <p><strong>Precio por Hora:</strong> $<?= number_format($cotizacion['precio_por_hora'], 2) ?></p>
            <p><strong>Mínimo de Horas:</strong> <?= htmlspecialchars($cotizacion['minimo_horas']) ?></p>
            <p><strong>Traslado Ida (horas):</strong> <?= htmlspecialchars($cotizacion['traslado_ida']) ?></p>
            <p><strong>Traslado Vuelta (horas):</strong> <?= htmlspecialchars($cotizacion['traslado_vuelta']) ?></p>
            <p><strong>Total Horas de Traslado:</strong> <?= htmlspecialchars($cotizacion['total_horas_traslado']) ?></p>
            <p><strong>Tiempo de Maniobra:</strong> <?= htmlspecialchars($cotizacion['tiempo_maniobra']) ?> horas</p>
            <p><strong>Total:</strong> $<?= number_format($cotizacion['total'], 2) ?></p>
            <p><strong>Fecha de Alta:</strong> <?= htmlspecialchars($cotizacion['fecha_alta']) ?></p>
        </div>
        <?php
    } else {
        echo "<p>No se encontraron detalles para esta cotización.</p>";
    }
} else {
    echo "<p>ID de cotización no proporcionado.</p>";
}

$conn->close();
?>
