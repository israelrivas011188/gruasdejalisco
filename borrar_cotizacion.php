<?php
require_once '../../conexion.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM cotizaciones WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Cotización eliminada con éxito.";
    } else {
        echo "Error al eliminar la cotización: " . $conn->error;
    }
} else {
    echo "ID de cotización no proporcionado.";
}

$conn->close();
header('Location: cotizaciones.php');
exit();
?>
