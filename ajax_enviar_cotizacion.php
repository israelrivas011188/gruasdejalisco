<?php
// ajax_enviar_cotizacion.php

// Iniciar sesión si es necesario
// session_start();

// Configurar cabeceras para respuesta JSON
header('Content-Type: application/json');

// Incluir las dependencias necesarias
require '../../conexion.php';
require '../../vendor/autoload.php'; // Asegúrate de que la ruta es correcta

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Función para obtener las credenciales SMTP desde un archivo de configuración seguro
function get_smtp_config() {
    // Puedes almacenar estas configuraciones en un archivo JSON, INI o usar variables de entorno
    // Aquí usaremos un archivo INI como ejemplo

    $config = parse_ini_file('../../config/smtp_config.ini', true);
    if (!$config) {
        return null;
    }
    return $config['smtp'];
}

// Obtener y validar el ID de la cotización
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(["success" => false, "message" => "ID de cotización no válido."]);
    exit();
}

$id = intval($_POST['id']);

// Preparar y ejecutar la consulta usando sentencias preparadas
$stmt = $conn->prepare("SELECT * FROM cotizaciones WHERE id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta."]);
    exit();
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Cotización no encontrada."]);
    exit();
}

$row = $result->fetch_assoc();

// Verificar si hay un email asociado
$email = filter_var($row['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    echo json_encode(["success" => false, "message" => "No hay un email válido asociado a esta cotización."]);
    exit();
}

// Cargar la configuración SMTP
$smtp_config = get_smtp_config();
if (!$smtp_config) {
    echo json_encode(["success" => false, "message" => "Configuración SMTP no encontrada."]);
    exit();
}

// Generar el contenido HTML de la cotización usando un template seguro
// Asegúrate de que 'template_cotizacion.php' no envíe encabezados ni tenga espacios en blanco antes de <?php
ob_start();
include 'template_cotizacion.php';
$html = ob_get_clean();

// Inicializar PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = $smtp_config['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtp_config['username'];
    $mail->Password   = $smtp_config['password'];
    $mail->SMTPSecure = $smtp_config['encryption']; // 'ssl' o 'tls'
    $mail->Port       = $smtp_config['port'];

    // Remitente y destinatario
    $mail->setFrom($smtp_config['from_email'], $smtp_config['from_name']);
    $mail->addAddress($email, $row['atencion']); // Puedes ajustar el nombre del destinatario

    // Contenido del correo
    $mail->isHTML(true);
    $subject_prefix = ($row['estatus'] === 'Servicio') ? 'Orden de Servicio' : 'Cotizacion';
    $mail->Subject = "{$subject_prefix} #{$row['id']}";
    $mail->Body    = $html;
    $mail->AltBody = "Esta es una cotización. Por favor, utiliza un cliente de correo que soporte HTML para verla correctamente.";

    // Enviar el correo
    $mail->send();

    echo json_encode(["success" => true, "message" => "Cotización enviada exitosamente a {$email}."]);
} catch (Exception $e) {
    // Puedes registrar el error en un log para revisarlo posteriormente
    // error_log("Error al enviar el correo: " . $mail->ErrorInfo);
    echo json_encode(["success" => false, "message" => "Error al enviar el correo: " . $mail->ErrorInfo]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
