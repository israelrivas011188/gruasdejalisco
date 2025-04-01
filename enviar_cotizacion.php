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
        $email1 = $cotizacion['email'];
        $email2 = $cotizacion['email_secundario'];

        // Construir el mensaje del correo
        $mensaje = "<h1>Detalle de la Cotización</h1>";
        $mensaje .= "<p><strong>Atención:</strong> {$cotizacion['atencion']}</p>";
        $mensaje .= "<p><strong>Teléfono de Atención:</strong> {$cotizacion['telefono_atencion']}</p>";
        $mensaje .= "<p><strong>Empresa:</strong> {$cotizacion['nombre_empresa']}</p>";
        $mensaje .= "<p><strong>Domicilio del Servicio:</strong> {$cotizacion['domicilio_servicio']}</p>";
        $mensaje .= "<p><strong>Precio por Hora:</strong> $" . number_format($cotizacion['precio_por_hora'], 2) . "</p>";
        $mensaje .= "<p><strong>Mínimo de Horas:</strong> {$cotizacion['minimo_horas']}</p>";
        $mensaje .= "<p><strong>Traslado Ida:</strong> {$cotizacion['traslado_ida']} horas</p>";
        $mensaje .= "<p><strong>Traslado Vuelta:</strong> {$cotizacion['traslado_vuelta']} horas</p>";
        $mensaje .= "<p><strong>Total Horas de Traslado:</strong> " . ($cotizacion['traslado_ida'] + $cotizacion['traslado_vuelta']) . " horas</p>";
        $mensaje .= "<p><strong>Tiempo de Maniobra:</strong> {$cotizacion['tiempo_maniobra']} horas</p>";
        $mensaje .= "<p><strong>Total:</strong> $" . number_format($cotizacion['total'], 2) . "</p>";
        $mensaje .= "<p><em>Fecha de alta: {$cotizacion['fecha_alta']}</em></p>";

        // Enviar el correo
        if (enviarCorreoCotizacion($email1, $email2, $mensaje)) {
            echo "Correo enviado correctamente.";

            // Actualizar el estado de envío en la base de datos
            $sql_update = "UPDATE cotizaciones SET enviado = 1 WHERE id = $id";
            if ($conn->query($sql_update) === TRUE) {
                echo "Estado de envío actualizado.";
            } else {
                echo "Error al actualizar el estado de envío: " . $conn->error;
            }
        } else {
            echo "Error al enviar el correo.";
        }
    } else {
        echo "Cotización no encontrada.";
    }
} else {
    echo "ID de cotización no proporcionado.";
}

$conn->close();
?>
