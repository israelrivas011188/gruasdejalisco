<?php
// ajax_obtener_cotizaciones.php

// Iniciar sesión y verificar autenticación si es necesario
session_start();

// Configuración de la conexión a la base de datos
$host = 'localhost'; // Cambia esto según tu configuración
$db   = 'gruasjalisco'; // Reemplaza con el nombre de tu base de datos
$user = 'root'; // Reemplaza con tu usuario de DB
$pass = 'w33QTylDEgoXIFmK2yGt1EI5Wb9'; // Reemplaza con tu contraseña de DB
$charset = 'utf8mb4';

// Establecer encabezados para JSON
header('Content-Type: application/json');

// Desactivar la visualización de errores en producción
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/ruta/a/tu/log_de_errores.log'); // Cambia la ruta según tu configuración

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Habilitar excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Fetch asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                // Deshabilitar emulación
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Enviar un mensaje de error en formato JSON
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

try {
    // Obtener las cotizaciones y la información de las grúas
    $stmt = $pdo->prepare("SELECT c.id, c.atencion, c.telefono_atencion, c.empresa, c.total, c.estatus, c.created_at,
                                 GROUP_CONCAT(g.marca, ' - ', g.tonelaje SEPARATOR ', ') AS gruas_info
                          FROM cotizaciones c
                          LEFT JOIN cotizaciones_gruas cg ON c.id = cg.cotizacion_id
                          LEFT JOIN gruas g ON cg.grua_id = g.id
                          GROUP BY c.id
                          ORDER BY c.id DESC");
    $stmt->execute();
    $cotizaciones = $stmt->fetchAll();

    // Verificar si se encontraron cotizaciones
    if ($cotizaciones) {
        echo json_encode($cotizaciones);
    } else {
        echo json_encode([]); // Retornar un array vacío si no hay cotizaciones
    }
} catch (Exception $e) {
    // Enviar un mensaje de error en formato JSON
    echo json_encode(['error' => 'Error al obtener las cotizaciones.']);
}
?>