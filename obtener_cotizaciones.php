<?php
// Conexión a la base de datos
$host = 'localhost';
$user = 'root';
$password = 'w33QTylDEgoXIFmK2yGt1EI5Wb9';
$database = 'gruasjalisco';

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consulta para obtener las cotizaciones
$sql = "SELECT id, atencion, telefono_atencion, empresa, total, created_at FROM cotizaciones ORDER BY created_at DESC";
$result = $conn->query($sql);

$cotizaciones = [];

// Procesar resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cotizaciones[] = $row;
    }
}

// Enviar datos como JSON
header('Content-Type: application/json');
echo json_encode($cotizaciones);

// Cerrar conexión
$conn->close();
?>
