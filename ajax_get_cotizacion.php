<?php
require '../../conexion.php';

$id = intval($_GET['id']);

// Obtener la cotización, las grúas asociadas y la descripción del contrapeso
$sql = "SELECT c.*, 
            IFNULL(JSON_ARRAYAGG(JSON_OBJECT(
                'grua_id', cg.grua_id,
                'minimo_horas', cg.minimo_horas,
                'precio_por_hora', cg.precio_por_hora,
                'traslado_ida', cg.traslado_ida,
                'traslado_vuelta', cg.traslado_vuelta,
                'descripcion_maniobra', cg.descripcion_maniobra 
            )), JSON_ARRAY()) AS gruas
        FROM cotizaciones c
        LEFT JOIN cotizaciones_gruas cg ON c.id = cg.cotizacion_id
        WHERE c.id = $id
        GROUP BY c.id";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    
    // Decodificar el JSON de las grúas para que JavaScript pueda usarlo directamente
    $row['gruas'] = json_decode($row['gruas'], true); 

    // Obtener la información de las grúas en un array asociativo
    $gruas = [];
    foreach ($row['gruas'] as $grua) {
        $gruas[$grua['grua_id']] = $grua; 
    }

    // Reemplazar el array de grúas original con el array asociativo
    $row['gruas'] = $gruas; 

    header('Content-Type: application/json');
    echo json_encode($row); 

} else {
    // Manejar el caso donde no se encuentra la cotización
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No se encontró la cotización.']); 
}
?>