<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Imprimir Cotización/Servicio</title>
  <link rel="stylesheet" href="style.css" type="text/css" media="all" />
  <style>
    /* General Styles */

    .watermark {
      position: absolute;
      top: 50%;
      left: 25%;
      transform: translate(-50%, -50%);
      z-index: -1; /* Asegura que esté detrás del contenido */
      opacity: 0.1; /* Controla la transparencia de la marca de agua */
      pointer-events: none; /* Permite que el contenido encima sea interactivo */
    }

    .watermark img {
      width: 300%; /* Ajusta el tamaño según sea necesario */
      height: auto;
    }


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
      position: relative;
      z-index: 1;
      page-break-inside: avoid;
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
      color:rgb(31, 1, 56);
      text-align: center;
      flex-grow: 1;
      margin-left: 20px;
      
    }
    .header-details {
      text-align: right;
      font-size: 14px;
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
      background-color:rgba(249, 249, 249, 0.46);
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

    @page {
    size: letter;
    margin: 20mm;
    }

  @media print {
    body {
      -webkit-print-color-adjust: exact;
    }

    .container {
      box-shadow: none;
      margin: 0;
      padding: 0;
      page-break-after: always; /* Asegura que el contenido se divida correctamente */
    }

    /* @media print {
      body {
        -webkit-print-color-adjust: exact;
      } */
      /* .container {
        box-shadow: none;
        margin: 0;
        padding: 0;
      } */
      footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
      }
    }



/* Mantener ancho uniforme para las columnas */
.details-table th, .details-table td {
  padding: 12px 15px;
  border: 1px solid #ddd;
  text-align: left;
  vertical-align: top;
  width: 25%; /* Ajusta este valor según tus necesidades */
  box-sizing: border-box;
}

/* Para columnas específicas, establece un ancho fijo */
.details-table th:nth-child(1), .details-table td:nth-child(1) {
  width: 40%; /* Ajusta este valor para la primera columna */
}
.details-table th:nth-child(2), .details-table td:nth-child(2) {
  width: 20%; /* Ajusta para la segunda columna */
}
.details-table th:nth-child(3), .details-table td:nth-child(3) {
  width: 20%;
}
.details-table th:nth-child(4), .details-table td:nth-child(4) {
  width: 20%;
}

/* Aplica la misma regla a otras tablas que usan `.table-uniform` */
.table-uniform th, .table-uniform td {
  width: auto; /* Para asegurar que no rompa el diseño */
}







.service-table-container table {
  width: 100%;
  border-collapse: collapse;
  font-family: sans-serif;
}

.service-table-container th, 
.service-table-container td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.service-table-container th {
  background-color: #6F4F8E;
  color: white;
  text-align: left;
}

.service-table-container tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.service-table-container tbody tr:first-child td {
  border-bottom: 2px solid #ccc; 
}

.table span.badge
{
 color:rgb(0, 0, 0)!important;

}
  </style>
</head>

<body >

  <?php
  require '../../conexion.php';
  $id = intval($_GET['id']);
// Obtener la cotización y las grúas asociadas
$sql = "SELECT
        c.*,
        GROUP_CONCAT(g.marca, ' - ', g.tonelaje SEPARATOR ', ') AS gruas_info
    FROM cotizaciones c
    LEFT JOIN cotizaciones_gruas cg ON c.id = cg.cotizacion_id
    LEFT JOIN gruas g ON cg.grua_id = g.id
    WHERE c.id = $id
    GROUP BY c.id";

$res = $conn->query($sql);
$row = $res->fetch_assoc();

$titulo = ($row['estatus'] === 'Servicio') ? 'Orden de Servicio' : 'COTIZACIÓN';

// Obtener la descripción del contrapeso (solo la primera si hay varias)
$sql_contrapeso = "SELECT descrip_contrapeso FROM cotizaciones_gruas WHERE cotizacion_id = $id LIMIT 1";
$res_contrapeso = $conn->query($sql_contrapeso);
$row_contrapeso = $res_contrapeso->fetch_assoc();
$descripcion_contrapeso = $row_contrapeso['descrip_contrapeso'] ?? ''; // Asignar una cadena vacía si no hay descripción
  $proveedorInfo = <<<HTML
  <p><strong>Grúas Industriales de Jalisco</strong></p>
  <p>Calle Hidalgo No. 15, Col. Parques de Santa María<br>
  C.P. 45609 Tlaquepaque, Jal.<br>
  Tel. 3316542653 | Cel. 3314174105, 3317568580<br>
  ventas_facturacion@hotmail.com</p>
  HTML;


 
  ?>
 
  <div class="container">
    <div class="header">
    <div class="watermark">
    <img src="../../assets/img/logo.png" alt="Marca de agua">
  </div>
      <div class="logotype">
        <img src="../../assets/img/logo.png" alt="Logo" />
      </div>
      <div class="header-title">
        <?php echo $titulo; ?>
      </div>
      <div class="header-details">
        <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($row['created_at'])); ?></p>
        <p><strong><?php echo $titulo; ?> #:</strong> <?php echo $row['id']; ?></p>
      </div>
    </div>

    <div class="contact-details">
  <div class="contact-box">
    <h3>Proveedor</h3>
    <?php echo $proveedorInfo; ?>
  </div>
  <div class="contact-box">
    <h3> Facturar a</h3>
    <p><strong>Atención:</strong> <?php echo ($row['atencion']) ? $row['atencion'] : 'Cliente'; ?></p>
    
    <p><strong>Teléfono Atención:</strong> <?php echo ($row['telefono_atencion']) ? $row['telefono_atencion'] : 'Cliente'; ?></p>
    <p><strong>Empresa:</strong> <?php echo ($row['empresa']) ? $row['empresa'] : 'Cliente'; ?></p>
    <?php if($row['telefono_empresa']): ?>
      <p><strong>Tel. Empresa:</strong> <?php echo $row['telefono_empresa']; ?></p>
    <?php endif; ?>
    <?php if($row['email']): ?>
      <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
    <?php endif; ?>

    <?php
    // Obtener el RFC del cliente utilizando el nombre de la empresa
    $sql_rfc = "SELECT rfc FROM clientes WHERE empresa = '{$row['empresa']}' LIMIT 1";
    $res_rfc = $conn->query($sql_rfc);
    $row_rfc = $res_rfc->fetch_assoc();
    if ($row_rfc) {
        echo "<p><strong>RFC:</strong> " . $row_rfc['rfc'] . "</p>";
    }
    ?>
  </div>
</div>


<div class="service-address">
  <h3>Datos del Servicio</h3>
  <div class="service-table-container">
    <table>
      <thead>
        <tr>
          <th>Domicilio de servicio:</th>
          <th>Descripción general:</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $row['domicilio_servicio']; ?></td>
          <td><?php echo $row['descripcion_servicio']; ?></td>  
        </tr>

      </tbody>
    </table>
  </div>
</div>
    <!-- <div class="service-address">
      <h3>Datos del Servicio</h3>
      <p><strong>Domicilio de servicio:</strong> <?php echo $row['domicilio_servicio']; ?></p>
      <p><strong>Descripción general:</strong> <?php echo $row['descripcion_servicio']; ?></p>
    </div> -->

    <table class="details-table table-uniform">
  <thead>
    <tr>
      <th>Unidad & Descripción</th>
      <th>Horas de servicio</th>
      <th>Precio/Hora</th>
    
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Obtener información de las grúas
    $gruas = $conn->query("SELECT cg.*, g.marca, g.tonelaje
                            FROM cotizaciones_gruas cg
                            JOIN gruas g ON cg.grua_id = g.id
                            WHERE cg.cotizacion_id = $id");
    while ($grua = $gruas->fetch_assoc()):

      // Calcular el subtotal de la grúa (CORREGIDO)
      $subtotal_grua = $grua['minimo_horas'] * $grua['precio_por_hora'];
    ?>
    <tr>
      <td>
        <?php echo $grua['marca'] . ' - ' . $grua['tonelaje'] . ' Toneladas'; ?><br>
        <span style="font-size: 12px; color: #555;"><?php echo $grua['descripcion_maniobra']; ?></span>
      </td>
      <td><?php echo number_format($grua['minimo_horas']); ?></td>
      <td><?php echo '$' . number_format($grua['precio_por_hora'], 2, '.', ','); ?></td>

   
      
      <td><?php echo '$' . number_format($subtotal_grua, 2, '.', ','); ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php if ($row['aplica_contrapeso']): ?>
    <table class="details-table table-uniform">
        <thead>
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #ccc;">Contrapeso</th>
                <th style="padding: 8px; border-bottom: 1px solid #ccc;">Cantidad</th>
                <th style="padding: 8px; border-bottom: 1px solid #ccc;">Precio</th>
                <th style="padding: 8px; border-bottom: 1px solid #ccc;">subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Primera columna -->
                <td style="padding: 8px; vertical-align: top;">
                   
                    <?php echo $row['aplica_contrapeso'] ? 'Sí' : 'No'; ?><br>
                   
                    <?php echo $descripcion_contrapeso; ?>
                </td>
                <!-- Segunda columna -->
                <td style="padding: 8px; vertical-align: top;">
                  
                    1
                </td>
                <!-- Tercera columna -->
                <td style="padding: 8px; vertical-align: top;">
                    
                    <?php echo '$' . number_format($row['traslado_contrapeso'], 2); ?>
                </td>

                <td style="padding: 8px; vertical-align: top;">
                    
                    <?php echo '$' . number_format($row['traslado_contrapeso'], 2); ?>
                </td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>


    <?php
    // ... (funciones para formato de moneda y número a letras) ... 
    function formatoMoneda($numero) {
      return '$' . number_format($numero, 2, '.', ','); 
  }

  // Función para convertir números a letras
  function convertirNumeroALetras($numero) {
      $formatter = new \NumberFormatter('es_MX', \NumberFormatter::SPELLOUT);
      $letras = strtoupper($formatter->format($numero));
      return $letras . ' MXN';
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
         <p>* El tiempo mínimo de servicio es de 3 horas para grúas tipo Titanes o articuladas, Mayores a 40 toneladas, el tiempo mínimo varía(se cobrará desde que sale de la base hasta que regrese a la base).</p>
         <p>* Se considera tiempo extra después de las 17:00 horas (costos adicionales).</p>
         <p>* La grúa incluye: Operadores, ayudantes, diesel, seguro del personal, seguro de grúa, DC3 del personal.</p>
         <p>* Pago 100% por adelantado.</p>
     </div>
     <div style="flex: 1; min-width: 300px;">
         <p><h3>Datos Bancarios:</h3></p>
         <p>
             NOMBRE: MARIA ELIZABETH VELAZQUEZ GONZALEZ<br>
             <strong>CUENTA BANORTE CON IVA</strong><br>
             NÚMERO DE CUENTA: 109038455<br>
             CLABE BANCARIA: 072 320 010903845510<br>
            
             <strong>CUENTA BANORTE SIN IVA</strong><br>
             NÚMERO DE CUENTA: 0592319519<br>
             CLABE BANCARIA:072 320 005923195190<br>

     </div>
 </div>
 HTML;

    ?>
    <div class="summary" style="width: 100%; display: flex; justify-content: flex-end; font-family: Arial, sans-serif;">
      <table class="summary-table" style="width: 300px; border-collapse: collapse; text-align: right;">
        <tr style="border-bottom: 1px solid #ddd;">
          <td style="padding: 10px; font-size: 14px;">Subtotal:</td>
          <td style="padding: 10px; font-size: 14px;">
            <?php echo formatoMoneda($row['subtotal']); ?> MXN
          </td>
        </tr>
        <?php if ($row['aplica_iva']): ?>
        <tr style="border-bottom: 1px solid #ddd;">
          <td style="padding: 10px; font-size: 14px;">IVA (16%):</td>
          <td style="padding: 10px; font-size: 14px;">
            <?php echo formatoMoneda($row['iva']); ?> MXN
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td colspan="2" style="padding: 0; margin: 0;">
            <div style="background-color: #6F4F8E; color: #fff; text-align: center; font-weight: bold; font-size: 18px; padding: 15px; margin-top: 10px;">
              TOTAL: <?php echo formatoMoneda($row['total']); ?> MXN
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div style="width: 800px; text-align: right; margin-top: 10px; font-size: 14px; font-weight: bold; text-transform: uppercase;">
      <?php echo convertirNumeroALetras($row['total']); ?>
    </div>
    <div class="terms">
      <?php echo $terminos; ?>
    </div>


    <div style="font-family: Arial, sans-serif; line-height: 1.6; padding: 20px;">
  <div style="margin-bottom: 20px;">
    <strong>Notas:</strong>
    <p>Esperamos seguir haciendo negocios con usted.</p>
  </div>
  
  <div style="margin-bottom: 20px;">
    <strong>Términos y condiciones</strong>
    <h3 style="margin: 10px 0;">Condiciones de Contratación:</h3>
    <p>Enviar orden de compra autorizada por la responsable de compras a fin de bloquear la fecha para su servicio y firma de contrato en caso de renta por mes, esto debe estar cerrado antes de enviar equipo.</p>
    
    <h3 style="margin: 10px 0;">Condiciones de Pago:</h3>
    <p>100% CONTADO, 3 días hábiles previos (sujeto a disponibilidad de equipos).</p>
  </div>
  
  <div style="margin-bottom: 20px;">
    <h3 style="margin: 10px 0;">Alcances Gruas de Jalisco:</h3>
    <ul>
      <li>Grasas, lubricantes y mantenimientos preventivos. SI APLICA</li>
      <li>Accesorios de izaje convencionales (estrobos, grilletes, eslingas).</li>
      <li>Realización de las maniobras, de acuerdo a la planeación realizada por el cliente.</li>
      <li>1 Operador de grúa.</li>
      <li>Certificado e inspección avalada por EMMA, OSHA, ASME y ANSI.</li>
      <li>Póliza de seguro daños a terceros como vehículo.</li>
    </ul>
  </div>
  
  <div style="margin-bottom: 20px;">
    <h3 style="margin: 10px 0;">Alcances Cliente en Caso de que Aplique Alguna:</h3>
    <ul>
      <li>Deberá estar libre de obstáculos aéreos y subterráneos, terracerías y caminos para el acceso y plataformas de anclaje de la grúa compactado y nivelados.</li>
      <li>Resguardar la grúa y equipos de líquidos, solventes, ácidos, tensiones eléctricas, pintura, chispas, etc. De ocurrir daños, el cliente absorberá el costo de la reparación.</li>
      <li>Elementos especiales para izaje tales como: balancines, separadores, marcos, anclajes, etc.</li>
      <li>Garantizar la integridad del equipo y nuestro personal en el sitio de trabajo.</li>
      <li>En caso de requerir un seguro de las piezas a mover, el cliente deberá pagarlo y contratarlo por separado.</li>
      <li>Todos los permisos necesarios para acceso, salida, trabajo y libre tránsito dentro de la zona de trabajo serán responsabilidad del cliente.</li>
      <li>Proveer personal necesario para la maniobra (montadores, topógrafos, instaladores, etc.).</li>
      <li>Suministrar combustible, 200 litros por semana.</li>
    </ul>
  </div>
  
  <div style="margin-bottom: 20px;">
    <h3 style="margin: 10px 0;">Condiciones Generales:</h3>
    <ol>
      <li>El equipo deberá trabajar dentro de sus capacidades y especificaciones técnicas. Equipo a izar deberá estar a pie de grúa.</li>
      <li>No nos hacemos responsables por daños al personal, muebles e inmuebles ajenos a nuestra empresa dentro del radio de trabajo.</li>
      <li>La maniobra no realizada por mala información o causas ajenas se contemplará como maniobra en falso, con un cargo del 100% de esta cotización.</li>
      <li>Horario de trabajo: Lunes a Viernes de 9:00 a 18:00 horas, Sábados de 9:00 a 14:00 horas.</li>
      <li>Validez de la cotización: 15 días.</li>
      <li>Al realizar un pago u orden de compra, se aceptan los términos y condiciones de esta cotización.</li>
      <li>Se firmará contrato en rentas iguales o superiores a 1 mes.</li>
      <li>Sujeto a disponibilidad de equipos.</li>
      <li>Las rentas mensuales se consideran por 1 mes o 30 días o 200 horas, lo que ocurra primero.</li>
    </ol>
  </div>
  
  <div>
    <p>Sin más por el momento, me es muy grato expresarle que sigo a sus órdenes en respuesta a su amable consideración.</p>
  </div>
</div>



    <footer>
      Grúas Industriales de Jalisco | ventas_facturacion@hotmail.com | +52-3316542653
    </footer>
  </div>
  <div style="text-align: center; margin-top: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; background-color: #6F4F8E; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
      Imprimir
    </button>
  </div>
</body>
</html>