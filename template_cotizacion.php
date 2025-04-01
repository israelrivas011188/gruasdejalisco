<?php
// template_cotizacion.php

// Asegúrate de que $row y $conn ya estén definidos antes de incluir este archivo
// $row = resultado de la consulta a la base de datos
// $conn = conexión a la base de datos

// Definir $titulo si no está definido
$titulo = isset($titulo) ? $titulo : 'Cotización';

// Función para convertir números a formato moneda
function formatoMoneda($numero) {
    return number_format($numero, 2, '.', ',');
}

// Función para convertir números a letras
function convertirNumeroALetras($numero) {
    if (!extension_loaded('intl')) {
        return 'Extensión intl no está disponible.';
    }
    $formatter = new \NumberFormatter('es_MX', \NumberFormatter::SPELLOUT);
    $letras = strtoupper($formatter->format($numero));
    return $letras . ' MXN';
}

// Escapar datos para prevenir XSS
function esc($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Verificar conexión a la base de datos
if (!$conn) {
    die("Conexión a la base de datos no establecida.");
}

// Establecer la codificación de caracteres a UTF-8
$conn->set_charset("utf8mb4");

// Obtener información de la grúa
$grua_id = isset($row['grua_id']) ? (int)$row['grua_id'] : 0;
$grua_res = $conn->query("SELECT marca, tonelaje FROM gruas WHERE id=$grua_id");
if ($grua_res && $grua_res->num_rows > 0) {
    $grua_data = $grua_res->fetch_assoc();
    $gruaInfo = esc($grua_data['marca']) . ' - ' . esc($grua_data['tonelaje']) . ' Toneladas';
} else {
    $gruaInfo = 'Grúa Desconocida';
}

// Información del proveedor
$proveedorInfo = <<<HTML
<p><strong>Grúas Industriales de Jalisco</strong></p>
<p>Calle Hidalgo No. 15, Col. Parques de Santa María<br>
C.P. 45609 Tlaquepaque, Jal.<br>
Tel. 3316542653 | Cel. 3314174105, 3317568580<br>
ventas_facturacion@hotmail.com</p>
HTML;

// Términos y condiciones
$terminos = <<<HTML
<div style="display: flex; flex-wrap: wrap; gap: 20px; font-family: Arial, sans-serif;">
    <div style="flex: 1; min-width: 300px;">
        <h3>Términos y Condiciones</h3>
        <p>* El tiempo mínimo son 3 horas (se cobrará desde que sale de la base hasta que regrese a la base).</p>
        <p>* Se considera tiempo extra después de las 17:00 horas (costos adicionales).</p>
        <p>* La grúa incluye: Operadores, ayudantes, diesel, seguro del personal, seguro de grúa, DC3 del personal.</p>
        <p>* Pago 100% por adelantado.</p>
    </div>
    <div style="flex: 1; min-width: 300px;">
        <p><strong>Datos Bancarios:</strong></p>
        <p>
            NOMBRE: MARIA ELIZABETH VELAZQUEZ GONZALEZ<br>
            CUENTA BANORTE CON IVA<br>
            CLABE BANCARIA: 072 320 010903845510<br>
            NÚMERO DE CUENTA: 109038455<br>
            CUENTA BANORTE SIN IVA<br>
            CUENTA: 4189-1430-5530-3989
        </p>
        <p><strong>LIC. MARIA ELIZABETH VELAZQUEZ GONZALEZ.</strong><br>
        DIRECTOR GENERAL</p>
    </div>
</div>
HTML;

// Obtener la URL absoluta para la imagen del logo
$logoUrl = "https://gruasdejalisco.com/assets/img/logo.png"; // Reemplaza "tudominio.com" con tu dominio real

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Imprimir Cotización/Servicio</title>
  <!-- Aunque los estilos en línea son recomendados para emails, se conservan los estilos en el head para la impresión -->
  <link rel="stylesheet" href="style.css" type="text/css" media="all" />
  <style>
    /* General Styles */
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 14px;
      color: #333;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 8px;
    }
    /* Header Styles */
    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 2px solid #6F4F8E;
      padding-bottom: 20px;
      margin-bottom: 20px;
    }
    .logotype {
      background:rgba(196, 92, 92, 0);
      color: #fff;
      width: 150px;
      height: 150px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
    }
    .logotype img {
      max-width: 70%;
      max-height: 70%;
    }
    .header-title {
      font-size: 32px;
      font-weight: bold;
      color: #000000;
      text-align: center;
      flex-grow: 1;
      margin-left: 20px;
      padding-left: 100px;
      margin-right: 200px;
    }
    .header-details {
    position: absolute; /* Para posicionar el elemento fuera del flujo normal */
    top: 0; /* Ajusta según el espacio superior que necesites */
    right: 0; /* Pega el elemento completamente a la derecha */
    max-width: 800px;
    text-align: right;
    font-size: 14px;
    flex-shrink: 0; /* Mantiene el tamaño del elemento */
}
    .header-details p {
      margin: 5px 0;
    }
    /* Contact Details */
    .contact-details {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .contact-box {
      width: 48%;
      background: #f1f5f9;
      padding: 20px;
      border-radius: 8px;
    }
    .contact-box h3 {
      margin-top: 0;
      color: #4a0c4c;
      font-size: 18px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .contact-box p {
      margin: 5px 0;
      font-size: 14px;
    }
    /* Service Address */
    .service-address {
      margin-bottom: 20px;
    }
    .service-address h3 {
      color: #4a0c4c;
      font-size: 18px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .service-address p {
      margin: 5px 0;
      font-size: 14px;
    }
    /* Details Table */
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .details-table th, .details-table td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: left;
      vertical-align: top;
    }
    .details-table th {
      background-color: #6F4F8E;
      color: #fff;
      font-size: 16px;
    }
    .details-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    /* Summary Section */
    .summary {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }
    .summary-table {
      width: 300px;
      border-collapse: collapse;
    }
    .summary-table td {
      padding: 10px;
      font-size: 14px;
    }
    .summary-table tr td:first-child {
      text-align: left;
    }
    .summary-table tr td:last-child {
      text-align: right;
      font-weight: bold;
    }
    /* Terms and Conditions */
    .terms {
      background: #f1f5f9;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .terms h3 {
      color: #4a0c4c;
      font-size: 18px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .terms p {
      margin: 10px 0;
      font-size: 14px;
      line-height: 1.6;
    }
    /* Footer */
    footer {
      text-align: center;
      font-size: 12px;
      color: #555;
      border-top: 1px solid #ddd;
      padding-top: 10px;
      position: relative;
      bottom: 0;
      width: 100%;
    }
    /* Responsive */
    @media (max-width: 600px) {
      .contact-details {
        flex-direction: column;
      }
      .contact-box {
        width: 100%;
        margin-bottom: 15px;
      }
      .header {
        flex-direction: column;
        align-items: flex-start;
      }
      .header-title {
        margin-left: 0;
        text-align: left;
        margin-top: 10px;
      }
      .header-details {
        text-align: left;
        margin-top: 10px;
      }
      .summary {
        justify-content: center;
      }
    }
    /* Print Styles */
    @media print {
      body {
        -webkit-print-color-adjust: exact;
      }
      .container {
        box-shadow: none;
        margin: 0;
        padding: 0;
      }
      footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
      }
    }


    .im {
    color: #000000!important;
}
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <div class="logotype">
        <!-- Usar URL absoluta para la imagen del logo -->
        <img src="<?php echo esc($logoUrl); ?>" alt="Logo" />
      </div>
      <div class="header-title">
        <?php echo esc($titulo); ?>
      </div>
      <div class="header-details">
        <p><strong>Fecha:</strong> <?php echo esc(date('d/m/Y', strtotime($row['created_at'] ?? ''))); ?></p>
        <p><strong><?php echo esc($titulo); ?> #:</strong> <?php echo esc($row['id'] ?? 'N/A'); ?></p>
      </div>
    </div>

    <!-- Contact Details -->
    <div class="contact-details">
      <div class="contact-box">
        <h3>Proveedor</h3>
        <?php echo $proveedorInfo; ?>
      </div>
      <div class="contact-box">
        <h3>Cliente</h3>

        <p><strong>Atención:</strong> <?php echo esc($row['atencion'] ?? 'Cliente'); ?></p>
        <p><strong>Empresa:</strong> <?php echo esc($row['empresa'] ?? 'Cliente'); ?></p>
        <p><strong>Teléfono Atención:</strong> <?php echo esc($row['telefono_atencion'] ?? 'Cliente'); ?></p>
        <?php if (!empty($row['telefono_empresa'])): ?>
          <p><strong>Tel. Empresa:</strong> <?php echo esc($row['telefono_empresa']); ?></p>
        <?php endif; ?>

        <?php if (!empty($row['email'])): ?>
          <p><strong>Email:</strong> <?php echo esc($row['email']); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Dirección de Servicio -->
    <div class="service-address">
      <h3>Datos del Servicio</h3>
    </div>

    <!-- Details Table -->
    <table class="details-table">
      <thead>
        <tr>
          <th>Información</th>
          <th>Datos</th>
        </tr>
      </thead>
      <tbody>

        <tr>
          <td>Grúa</td>
          <td><?php echo esc($gruaInfo); ?></td>
        </tr>
        <tr>
          <td>Descripción Servicio</td>
          <td><?php echo esc($row['descripcion_servicio'] ?? 'Sin descripción'); ?></td>
        </tr>
        <tr>
          <td>Precio/Hora</td>
          <td><?php echo esc(formatoMoneda($row['precio_por_hora'] ?? 0)); ?> MXN</td>
        </tr>
        <tr>
          <td>Mínimo Horas</td>
          <td><?php echo esc($row['minimo_horas'] ?? 0); ?> horas</td>
        </tr>
        <tr>
          <td>Traslado Ida (hrs)</td>
          <td><?php echo esc($row['traslado_ida'] ?? 0); ?> horas</td>
        </tr>
        <tr>
          <td>Traslado Vuelta (hrs)</td>
          <td><?php echo esc($row['traslado_vuelta'] ?? 0); ?> horas</td>
        </tr>
        <tr>
          <td>Traslado Contrapeso</td>
          <td><?php echo esc(formatoMoneda($row['traslado_contrapeso'] ?? 0)); ?> MXN</td>
        </tr>
        <tr>
          <td>Desc. Contrapeso</td>
          <td><?php echo esc($row['descripcion_contrapeso'] ?? 'Sin descripción'); ?></td>
        </tr>
        <?php if (isset($row['estatus']) && $row['estatus'] === 'Servicio'): ?>
          <tr>
            <td>Número Nota Servicio</td>
            <td><?php echo esc($row['numero_nota_servicio'] ?? 'N/A'); ?></td>
          </tr>
          <tr>
            <td>Salida Base</td>
            <td><?php echo esc($row['salida_base'] ?? 'N/A'); ?></td>
          </tr>
          <tr>
            <td>Entrada Cliente</td>
            <td><?php echo esc($row['entrada_cliente'] ?? 'N/A'); ?></td>
          </tr>
          <tr>
            <td>Salida Cliente</td>
            <td><?php echo esc($row['salida_cliente'] ?? 'N/A'); ?></td>
          </tr>
          <tr>
            <td>Entrada Base</td>
            <td><?php echo esc($row['entrada_base'] ?? 'N/A'); ?></td>
          </tr>
          <tr>
            <td>Tiempo Servicio</td>
            <td><?php echo esc($row['tiempo_servicio'] ?? 0); ?> horas</td>
          </tr>
          <?php if (!empty($row['evidencia_servicio'])): ?>
            <tr>
              <td>Evidencia Servicio</td>
              <td><a href="<?php echo esc($row['evidencia_servicio']); ?>" target="_blank">Ver Evidencia</a></td>
            </tr>
          <?php endif; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Resumen de Costos -->
    <div class="summary">
        <table class="summary-table">
            <tr>
                <td>Subtotal:</td>
                <td><?php echo esc(formatoMoneda($row['subtotal'] ?? 0)); ?> MXN</td>
            </tr>
            <tr>
                <td>IVA:</td>
                <td><?php echo esc(formatoMoneda($row['iva'] ?? 0)); ?> MXN</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="background-color: #6F4F8E; color: #fff; text-align: center; font-weight: bold; font-size: 18px; padding: 15px; margin-top: 10px;">
                        TOTAL: <?php echo esc(formatoMoneda($row['total'] ?? 0)); ?> MXN
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="width: 800px; text-align: left; margin-top: 10px; font-size: 14px; font-weight: bold; text-transform: uppercase;">
        <?php echo esc(convertirNumeroALetras($row['total'] ?? 0)); ?>
    </div>

    <!-- Términos y Condiciones -->
    <div class="terms">
      <?php echo $terminos; ?>
    </div>

    <!-- Footer -->
    <footer>
      Grúas Industriales de Jalisco | ventas_facturacion@hotmail.com | +52-3316542653
    </footer>
  </div><!-- container -->

  <!-- Botones al final -->
  <div style="text-align: center; margin-top: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; background-color: #6F4F8E; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
      Imprimir
    </button>
  </div>

  <!-- Si deseas imprimir automáticamente al cargar, descomenta la línea siguiente -->
  <!-- <script>window.onload=function(){ window.print(); }</script> -->
</body>
</html>
