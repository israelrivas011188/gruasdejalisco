<?php
require '../../conexion.php';

// Obtener los datos del formulario
$id = isset($_POST['id_edit']) ? intval($_POST['id_edit']) : 0; 
$esCliente = $_POST['esCliente'] ?? '';
$atencion = $conn->real_escape_string($_POST['atencion']);
$telefono_atencion = $conn->real_escape_string($_POST['telefono_atencion']);
$empresa = $conn->real_escape_string($_POST['empresa']);
$telefono_empresa = $conn->real_escape_string($_POST['telefono_empresa']);
$email = $conn->real_escape_string($_POST['email']);
$domicilio_servicio = $conn->real_escape_string($_POST['domicilio_servicio']);
$descripcion_servicio = $conn->real_escape_string($_POST['descripcion_servicio']);


$aplicaContrapeso = isset($_POST['aplicaContrapeso']) ? 1 : 0;
$aplicaIVA = isset($_POST['aplicaIVA']) ? 1 : 0;

$traslado_contrapeso = floatval($_POST['traslado_contrapeso'] ?? 0);
$descripcion_contrapeso = $conn->real_escape_string($_POST['descripcion_contrapeso'] ?? '');
$tiempo_maniobra = floatval($_POST['tiempo_maniobra']);
$subtotal = floatval($_POST['subtotal']);
$iva = floatval($_POST['iva']);
$total = floatval($_POST['total']);


// Manejar la creación o edición de la cotización
if ($id > 0) { 
    // Actualizar la cotización existente
    $sql = "UPDATE cotizaciones SET 
        es_cliente = '$esCliente',
        atencion = '$atencion',
        telefono_atencion = '$telefono_atencion',
        empresa = '$empresa',
        telefono_empresa = '$telefono_empresa',
        email = '$email',
        domicilio_servicio = '$domicilio_servicio',
        descripcion_servicio = '$descripcion_servicio',
        aplica_contrapeso = $aplicaContrapeso,
        traslado_contrapeso = $traslado_contrapeso,
        descripcion_contrapeso = '$descripcion_contrapeso', 
        tiempo_maniobra = $tiempo_maniobra,
        subtotal = $subtotal,
        iva = $iva,
        total = $total,
        aplica_iva = $aplicaIVA
        WHERE id = $id";

    if ($conn->query($sql)) {
        // Eliminar las grúas asociadas a la cotización para luego insertar las nuevas
        $conn->query("DELETE FROM cotizaciones_gruas WHERE cotizacion_id = $id");
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar la cotización: " . $conn->error]);
        exit(); 
    }

} else {
    // Insertar una nueva cotización
    $sql = "INSERT INTO cotizaciones (
        es_cliente, atencion, telefono_atencion, empresa, telefono_empresa, email, 
        domicilio_servicio, descripcion_servicio, aplica_contrapeso, traslado_contrapeso, 
        descripcion_contrapeso, tiempo_maniobra, subtotal, iva, total, aplica_iva 
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta: " . $conn->error]);
        exit();
    }

    $stmt->bind_param(
        "sssssssdddsdddi", 
        $esCliente, $atencion, $telefono_atencion, $empresa, $telefono_empresa, $email, 
        $domicilio_servicio, $descripcion_servicio, $aplicaContrapeso, $traslado_contrapeso, 
        $descripcion_contrapeso, $tiempo_maniobra, $subtotal, $iva, $total, $aplicaIVA
    );

    if ($stmt->execute()) {
        $id = $conn->insert_id; // Obtener el ID de la cotización recién insertada
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar la cotización: " . $stmt->error]);
        exit();
    }
}

// Insertar las grúas en la tabla cotizaciones_gruas
if(isset($_POST['grua'], $_POST['minimo_horas'], $_POST['precio_por_hora'], $_POST['traslado_ida'], $_POST['traslado_vuelta'], $_POST['descripcion_maniobra'])){
    $gruas = $_POST['grua'];
    $minimo_horas = $_POST['minimo_horas'];
    $precio_por_hora = $_POST['precio_por_hora'];
    $traslado_ida = $_POST['traslado_ida'];
    $traslado_vuelta = $_POST['traslado_vuelta'];
    $descripcion_maniobra = $_POST['descripcion_maniobra']; 

    foreach ($gruas as $index => $grua_id) {
        $grua_id = intval($grua_id);
        $minimo_horas_grua = intval($minimo_horas[$index]);
        $precio_por_hora_grua = floatval($precio_por_hora[$index]);
        $traslado_ida_grua = floatval($traslado_ida[$index]);
        $traslado_vuelta_grua = floatval($traslado_vuelta[$index]);
        $descripcion_maniobra_grua = $conn->real_escape_string($descripcion_maniobra[$index]); 

        $sql_grua = "INSERT INTO cotizaciones_gruas (cotizacion_id, grua_id, minimo_horas, precio_por_hora, traslado_ida, traslado_vuelta, descripcion_maniobra) 
                        VALUES ($id, $grua_id, $minimo_horas_grua, $precio_por_hora_grua, $traslado_ida_grua, $traslado_vuelta_grua, '$descripcion_maniobra_grua')";

        if (!$conn->query($sql_grua)) {
            error_log("Error al insertar grúa: " . $conn->error);
            echo json_encode(["success" => false, "message" => "Error al guardar la información de las grúas."]);
            exit();
        }
    }
}

echo json_encode(["success" => true, "message" => $id > 0 ? "Cotización actualizada exitosamente" : "Cotización guardada exitosamente"]);

?>