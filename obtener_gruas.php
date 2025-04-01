<?php
// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = 'w33QTylDEgoXIFmK2yGt1EI5Wb9';
$database = 'gruasjalisco';

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}

// Establecer codificación UTF-8
$conn->set_charset('utf8mb4');

// Consulta SQL
$sql = "SELECT id, marca, tonelaje FROM gruas ORDER BY marca ASC";
$result = $conn->query($sql);

$gruas = [];
if ($result === false) {
    // Error en la consulta
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
    $conn->close();
    exit;
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gruas[] = $row;
    }
} else {
    // No hay grúas disponibles
    $gruas = []; // O podrías devolver un mensaje como ['message' => 'No hay grúas disponibles']
}

// Configurar encabezado y devolver respuesta
header('Content-Type: application/json; charset=utf-8');
echo json_encode($gruas);

// Cerrar conexión
$conn->close();
?>