<?php
$host = 'localhost';
$user = 'root';
$password = 'w33QTylDEgoXIFmK2yGt1EI5Wb9';
$database = 'gruasjalisco';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT id, empresa, telefono_principal, email FROM clientes ORDER BY empresa ASC";
$result = $conn->query($sql);

$clientes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($clientes);

$conn->close();
?>
