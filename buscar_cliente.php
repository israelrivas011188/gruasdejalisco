<?php
require_once '../../conexion.php';


if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT id, empresa FROM clientes WHERE empresa LIKE '%$search%' LIMIT 5";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = ['id' => $row['id'], 'empresa' => $row['empresa']];
        }
        echo json_encode($clientes[0]);
    } else {
        echo json_encode(['error' => 'No se encontraron resultados']);
    }
} else {
    echo json_encode(['error' => 'Búsqueda no válida']);
}

$conn->close();
?>
