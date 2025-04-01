<?php
require '../../conexion.php';

// Obtener los datos del formulario
$id = intval($_POST['id']);
$numero_nota_servicio = $conn->real_escape_string($_POST['numero_nota_servicio']);
$salida_base = $_POST['salida_base'];
$entrada_cliente = $_POST['entrada_cliente'];
$salida_cliente = $_POST['salida_cliente'];
$entrada_base = $_POST['entrada_base'];
$tiempo_servicio = $conn->real_escape_string($_POST['tiempo_servicio']);

// Manejar la evidencia del servicio (similar al código original)
$evidencia_servicio = null;
if (!empty($_FILES['evidencia_servicio']['name'])) {
    $filename = time() . '_' . basename($_FILES['evidencia_servicio']['name']);
    $target = 'uploads/' . $filename;
    if (move_uploaded_file($_FILES['evidencia_servicio']['tmp_name'], $target)) {
        $evidencia_servicio = $conn->real_escape_string($target);
    }
}

// Actualizar la cotización a servicio
$sql = "UPDATE cotizaciones SET 
    estatus='Servicio',
    numero_nota_servicio='$numero_nota_servicio',
    evidencia_servicio='$evidencia_servicio',
    salida_base='$salida_base',
    entrada_cliente='$entrada_cliente',
    salida_cliente='$salida_cliente',
    entrada_base='$entrada_base',
    tiempo_servicio='$tiempo_servicio'
    WHERE id=$id";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}
?>