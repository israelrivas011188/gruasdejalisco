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

// Obtener la descripción del contrapeso (solo la primera si hay varias)
$sql_contrapeso = "SELECT descrip_contrapeso FROM cotizaciones_gruas WHERE cotizacion_id = $id LIMIT 1";
$res_contrapeso = $conn->query($sql_contrapeso);
$row_contrapeso = $res_contrapeso->fetch_assoc();
$descripcion_contrapeso = $row_contrapeso['descrip_contrapeso'] ?? ''; // Asignar una cadena vacía si no hay descripción
?>

<div class="modal-header">
    
</div>
<div class="modal-body">
    <table class="striped responsive-table">

        <tbody>
            <tr>
                <td>ID Cotización:</td>
                <td><?php echo $row['id']; ?></td>
            </tr>
            <tr>
                <td>Estatus:</td>
                <td><?php echo $row['estatus']; ?></td>
            </tr>
            <tr>
                <td>Atención:</td>
                <td><?php echo $row['atencion']; ?></td>
            </tr>
            <tr>
                <td>Teléfono Atención:</td>
                <td><?php echo $row['telefono_atencion']; ?></td>
            </tr>
            <tr>
                <td>Empresa:</td>
                <td><?php echo $row['empresa']; ?></td>
            </tr>
            <tr>
                <td>Teléfono Empresa:</td>
                <td><?php echo $row['telefono_empresa']; ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?php echo $row['email']; ?></td>
            </tr>
            <tr>
                <td>Domicilio Servicio:</td>
                <td><?php echo $row['domicilio_servicio']; ?></td>
            </tr>
            <tr>
                <td>Descripción del Servicio:</td>
                <td><?php echo $row['descripcion_servicio']; ?></td>
            </tr>
            <tbody>
            <tr>
                <td>Aplica Contrapeso:</td>
                <td><?php echo $row['aplica_contrapeso'] ? 'Sí' : 'No'; ?></td>
            </tr>
            <?php if ($row['aplica_contrapeso']): ?>
            <tr>
                <td>Traslado Contrapeso:</td>
                <td><?php echo '$' . number_format($row['traslado_contrapeso'], 2); ?></td>
            </tr>
            <tr>  <tr>
                <td>Descripción Contrapeso:</td>
                <td><?php echo $descripcion_contrapeso; ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tr>
                <td>Tiempo de Maniobra (horas):</td>
                <td><?php echo $row['tiempo_maniobra']; ?></td>
            </tr>
            <tr>
                <td>Subtotal:</td>
                <td><?php echo '$' . number_format($row['subtotal'], 2); ?></td>
            </tr>
            <tr>
                <td>IVA:</td>
                <td><?php echo '$' . number_format($row['iva'], 2); ?></td>
            </tr>
            <tr>
                <td>Total:</td>
                <td><?php echo '$' . number_format($row['total'], 2); ?></td>
            </tr>
            <tr>
                <td>Fecha Creación:</td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
            <tr>
                <td>Aplica IVA:</td>
                <td><?php echo $row['aplica_iva'] ? 'Sí' : 'No'; ?></td>
            </tr>
            <?php if($row['estatus'] === 'Servicio'): ?>
            <tr>
                <td>Número de Nota de Servicio:</td>
                <td><?php echo $row['numero_nota_servicio']; ?></td>
            </tr>
            <tr>
                <td>Salida Base:</td>
                <td><?php echo $row['salida_base']; ?></td>
            </tr>
            <tr>
                <td>Entrada Cliente:</td>
                <td><?php echo $row['entrada_cliente']; ?></td>
            </tr>
            <tr>
                <td>Salida Cliente:</td>
                <td><?php echo $row['salida_cliente']; ?></td>
            </tr>
            <tr>
                <td>Entrada Base:</td>
                <td><?php echo $row['entrada_base']; ?></td>
            </tr>
            <tr>
                <td>Tiempo Servicio:</td>
                <td><?php echo $row['tiempo_servicio']; ?></td>
            </tr>
            <tr>
                <td>Evidencia Servicio:</td>
                <td>
                    <?php if(!empty($row['evidencia_servicio'])): ?>
                        <a href="<?php echo $row['evidencia_servicio']; ?>" target="_blank">Ver Evidencia</a>
                    <?php else: ?>
                        Sin evidencia
                    <?php endif; ?>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h5>Grúas:</h5>
    <?php 
    // Mostrar la información de cada grúa
    $gruas = $conn->query("SELECT 
            cg.*, 
            g.marca, 
            g.tonelaje
        FROM cotizaciones_gruas cg
        JOIN gruas g ON cg.grua_id = g.id
        WHERE cg.cotizacion_id = $id");

    $i = 0; // Inicializar un contador para las descripciones de contrapeso
    while ($grua = $gruas->fetch_assoc()): 
    ?>
    <table class="striped responsive-table">
        <thead>
            <tr>
                <th colspan="2">Grúa <?= $grua['marca'] ?> - <?= $grua['tonelaje'] ?> Ton</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Mínimo Horas:</td>
                <td><?= $grua['minimo_horas'] ?></td>
            </tr>
            <tr>
                <td>Precio por Hora:</td>
                <td><?php echo '$' . number_format($grua['precio_por_hora'], 2); ?></td>
            </tr>
            <tr>
                <td>Traslado Ida (horas):</td>
                <td><?= $grua['traslado_ida'] ?></td>
            </tr>
            <tr>
                <td>Traslado Vuelta (horas):</td>
                <td><?= $grua['traslado_vuelta'] ?></td>
            </tr>
            <tr>
                <td>Descripción de la Maniobra:</td>
                <td><?= $grua['descripcion_maniobra'] ?></td>
            </tr>

        </tbody>
    </table>
    <?php 
        $i++; // Incrementar el contador en cada iteración
        endwhile; 
    ?>
</div>
