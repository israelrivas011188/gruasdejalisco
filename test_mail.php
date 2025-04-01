<?php
// Aumentar el tiempo de ejecución de PHP
set_time_limit(120); // 120 segundos

// Incluir PHPMailer
require '../../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Crear una instancia de PHPMailer con depuración habilitada
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com'; // Servidor SMTP de Outlook
    $mail->SMTPAuth   = true;                    // Autenticación habilitada
    $mail->Username   = 'ventas_facturacion@hotmail.com';
    $mail->Password   = 'nvukhvtrtkzjvciy';    // Tu contraseña
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;                     // Puerto para TLS

    // Habilitar depuración para diagnosticar problemas
    $mail->SMTPDebug  = 2;                       // Nivel 2: muestra detalles
    $mail->Debugoutput = 'echo';                 // Mostrar salida en pantalla

    // Remitente y destinatario
    $mail->setFrom('ventas_facturacion@hotmail.com', 'Grúas Industriales de Jalisco');
    $mail->addAddress('israelrivas011188@gmail.com', 'Israel'); // Correo de prueba

    // Contenido del correo
    $mail->isHTML(true);                         // Correo en formato HTML
    $mail->Subject = 'Prueba de PHPMailer';
    $mail->Body    = '<h1>Este es un correo de prueba</h1><p>Si estás viendo esto, PHPMailer está funcionando correctamente.</p>';
    $mail->AltBody = 'Este es un correo de prueba. Si estás viendo esto, PHPMailer está funcionando correctamente.';

    // Enviar el correo
    $mail->send();
    echo 'Correo enviado exitosamente';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>