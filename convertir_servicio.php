<?php
require_once '../../conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Asegurar que el ID sea un número entero

    // Obtener la cotización desde la base de datos
    $sql = "SELECT * FROM cotizaciones WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $cotizacion = $result->fetch_assoc();

        // Insertar la cotización como un nuevo servicio en la tabla de servicios
        $sql_servicio = "INSERT INTO servicios (
            numero_cotizacion, atencion, telefono_atencion, cliente_id, grua_id, domicilio_servicio, 
            precio_por_hora, minimo_horas, traslado_ida, traslado_vuelta, tiempo_maniobra, total, fecha_alta
        ) VALUES (
            '{$cotizacion['numero_cotizacion']}', '{$cotizacion['atencion']}', '{$cotizacion['telefono_atencion']}', 
            '{$cotizacion['cliente_id']}', '{$cotizacion['grua_id']}', '{$cotizacion['domicilio_servicio']}', 
            '{$cotizacion['precio_por_hora']}', '{$cotizacion['minimo_horas']}', '{$cotizacion['traslado_ida']}', 
            '{$cotizacion['traslado_vuelta']}', '{$cotizacion['tiempo_maniobra']}', '{$cotizacion['total']}', NOW()
        )";

        if ($conn->query($sql_servicio) === TRUE) {
            // Marcar la cotización como convertida en servicio
            $sql_update = "UPDATE cotizaciones SET convertido_en_servicio = 1 WHERE id = $id";
            if ($conn->query($sql_update) === TRUE) {
                echo "Cotización convertida en servicio correctamente.";
            } else {
                echo "Error al actualizar la cotización como convertida: " . $conn->error;
            }
        } else {
            echo "Error al convertir la cotización en un servicio: " . $conn->error;
        }
    } else {
        echo "Cotización no encontrada.";
    }
} else {
    echo "ID de cotización no proporcionado.";
}

$conn->close();
?>
