<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$user = 'root';
$password = 'w33QTylDEgoXIFmK2yGt1EI5Wb9';
$database = 'gruasjalisco';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]);
    exit();
}

// Obtener los datos del formulario
$atencion = $_POST['atencion'] ?? null;
$telefono_atencion = $_POST['telefono_atencion'] ?? null;
$empresa = $_POST['empresa'] ?? null;
$telefono_empresa = $_POST['telefono_empresa'] ?? null;
$email = $_POST['email'] ?? null;
$domicilio_servicio = $_POST['domicilio_servicio'] ?? null;
$descripcion_servicio = $_POST['descripcion_servicio'] ?? null;

// Convertir los valores de 'aplicaContrapeso' y 'aplicaIVA' a numéricos
$aplicaContrapeso = isset($_POST['aplicaContrapeso']) ? 1 : 0;
$aplicaIVA = isset($_POST['aplicaIVA']) ? 1 : 0;

$traslado_contrapeso = floatval($_POST['traslado_contrapeso'] ?? 0);
$descrip_contrapeso = $_POST['descripcion_contrapeso'] ?? null; // Obtener la descripción del contrapeso
$tiempo_maniobra = floatval($_POST['tiempo_maniobra'] ?? 0);
$subtotal = floatval($_POST['subtotal'] ?? 0);
$iva = floatval($_POST['iva'] ?? 0);
$total = floatval($_POST['total'] ?? 0);

// Insertar la cotización en la tabla cotizaciones
$sql = "INSERT INTO cotizaciones (
    atencion, telefono_atencion, empresa, telefono_empresa, email, 
    domicilio_servicio, descripcion_servicio, aplica_contrapeso, traslado_contrapeso, 
    descripcion_contrapeso, tiempo_maniobra, subtotal, iva, total, aplica_iva
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta: " . $conn->error]);
    exit();
}

$stmt->bind_param(
    "sssssssdddsdddi",
    $atencion, $telefono_atencion, $empresa, $telefono_empresa, $email, 
    $domicilio_servicio, $descripcion_servicio, $aplicaContrapeso, $traslado_contrapeso, 
    $descrip_contrapeso, $tiempo_maniobra, $subtotal, $iva, $total, $aplicaIVA 
);

if ($stmt->execute()) {
    $last_id = $conn->insert_id; // Obtener el ID de la cotización recién insertada

    // Verificar si se enviaron datos de grúas
    if (isset($_POST['grua']) && isset($_POST['minimo_horas']) && isset($_POST['precio_por_hora']) && isset($_POST['traslado_ida']) && isset($_POST['traslado_vuelta']) && isset($_POST['descripcion_maniobra'])) { 

        // Insertar las grúas en la tabla cotizaciones_gruas
        $gruas = $_POST['grua'];
        $minimo_horas = $_POST['minimo_horas'];
        $precio_por_hora = $_POST['precio_por_hora'];
        $traslado_ida = $_POST['traslado_ida'];
        $traslado_vuelta = $_POST['traslado_vuelta'];
        $descripcion_maniobra = $_POST['descripcion_maniobra']; // Obtener las descripciones de maniobra

        foreach ($gruas as $index => $grua_id) {
            $grua_id = intval($grua_id);
            $minimo_horas_grua = intval($minimo_horas[$index]);
            $precio_por_hora_grua = floatval($precio_por_hora[$index]);
            $traslado_ida_grua = floatval($traslado_ida[$index]);
            $traslado_vuelta_grua = floatval($traslado_vuelta[$index]);
            $descripcion_maniobra_grua = $conn->real_escape_string($descripcion_maniobra[$index]); // Escapar la descripción de la maniobra

            // Incluir la descripción del contrapeso en la consulta
            $sql_grua = "INSERT INTO cotizaciones_gruas (cotizacion_id, grua_id, minimo_horas, precio_por_hora, traslado_ida, traslado_vuelta, descripcion_maniobra, descrip_contrapeso) 
            VALUES ($last_id, $grua_id, $minimo_horas_grua, $precio_por_hora_grua, $traslado_ida_grua, $traslado_vuelta_grua, '$descripcion_maniobra_grua', '$descrip_contrapeso')";

            if (!$conn->query($sql_grua)) {
                error_log("Error al insertar grúa: " . $conn->error);
                echo json_encode(["success" => false, "message" => "Error al guardar la información de las grúas."]);
                exit();
            }
        }
    } else {
        // Manejar el caso donde no se enviaron datos de grúas (opcional)
        error_log("No se recibieron datos de grúas."); 
        // Puedes mostrar un mensaje al usuario o realizar alguna otra acción si lo necesitas
    }

    echo json_encode(["success" => true, "message" => "Cotización guardada exitosamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al guardar la cotización: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>