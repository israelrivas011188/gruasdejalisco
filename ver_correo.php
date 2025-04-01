<?php
require_once '../../conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Obtener la cotización desde la base de datos
    $sql = "SELECT cotizaciones.*, clientes.nombre_empresa, clientes.email, cotizaciones.email_secundario 
            FROM cotizaciones 
            JOIN clientes ON cotizaciones.cliente_id = clientes.id 
            WHERE cotizaciones.id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $cotizacion = $result->fetch_assoc();

        // Construir la vista previa del mensaje del correo
        echo "<div style='padding: 20px; font-family: Arial, sans-serif;'>";
        echo "<h1>Vista Previa del Correo de la Cotización</h1>";
        echo "<p><strong>Atención:</strong> {$cotizacion['atencion']}</p>";
        echo "<p><strong>Teléfono de Atención:</strong> {$cotizacion['telefono_atencion']}</p>";
        echo "<p><strong>Empresa:</strong> {$cotizacion['nombre_empresa']}</p>";
        echo "<p><strong>Domicilio del Servicio:</strong> {$cotizacion['domicilio_servicio']}</p>";
        echo "<p><strong>Precio por Hora:</strong> $" . number_format($cotizacion['precio_por_hora'], 2) . "</p>";
        echo "<p><strong>Mínimo de Horas:</strong> {$cotizacion['minimo_horas']}</p>";
        echo "<p><strong>Traslado Ida:</strong> {$cotizacion['traslado_ida']} horas</p>";
        echo "<p><strong>Traslado Vuelta:</strong> {$cotizacion['traslado_vuelta']} horas</p>";
        echo "<p><strong>Total Horas de Traslado:</strong> " . ($cotizacion['traslado_ida'] + $cotizacion['traslado_vuelta']) . " horas</p>";
        echo "<p><strong>Tiempo de Maniobra:</strong> {$cotizacion['tiempo_maniobra']} horas</p>";
        echo "<p><strong>Total:</strong> $" . number_format($cotizacion['total'], 2) . "</p>";
        echo "<p><em>Fecha de alta: {$cotizacion['fecha_alta']}</em></p>";

        echo "<div style='margin-top: 20px;'>";
        echo "<p><strong>Correos de Envío:</strong></p>";
        echo "<p>Principal: {$cotizacion['email']}</p>";
        if (!empty($cotizacion['email_secundario'])) {
            echo "<p>Secundario: {$cotizacion['email_secundario']}</p>";
        }
        echo "</div>";

        echo "</div>";
    } else {
        echo "<p>Cotización no encontrada.</p>";
    }
} else {
    echo "<p>ID de cotización no proporcionado.</p>";
}

$conn->close();
?>
