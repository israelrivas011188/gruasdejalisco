<?php
error_reporting(E_ALL); // Mostrar todos los errores para depuración
ini_set('display_errors', 1); // Mostrar errores en la salida
ini_set('error_log', '../../log.txt'); 

// Iniciar sesión
session_start();

// Incluir archivos compartidos con rutas correctas
include_once('../../includes/header.php');
include_once('../../includes/navbar.php');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Cotización</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <style>
        /* Estilos CSS para la página */
        body {
            font-family: 'Roboto', sans-serif;
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .input-field>label {
            top: 10px;
        }
        /* Estilos para los botones de acción */
        .btn-ver { background-color: #42a5f5; /* Azul */ }

        .btn-imprimir { background-color: #26a69a; /* Verde azulado */ }
        .btn-enviar { background-color: #66bb6a; /* Verde */ }
        .btn-convertir { background-color: #ab47bc; /* Púrpura */ }
        .btn-cancelar { background-color: #e53935; /* Rojo */ }
        /* ... otros estilos ... */
    </style>
</head>
<body class="grey lighten-3">

<div class="main-content">
    <h4>Gestión de Cotización</h4>
    <?php include_once('../../includes/sidebar.php'); ?>
    <div>
        <button id="btnMostrarFormulario" class="btn btn-create-user blue waves-effect waves-light">Generar Cotización</button>

        <div id="formularioCotizacion" style="display:none; margin-top: 20px;">
            <form id="formCotizacion" class="col s12">
                <input type="hidden" name="id_edit" id="id_edit" value="">
                <input type="hidden" name="esCliente" id="hiddenEsCliente">

                <div class="switch">
                    <label>
                        ¿Es cliente?
                        No
                        <input type="checkbox" id="esCliente">
                        <span class="lever"></span>
                        Sí
                    </label>
                </div>

                <div id="datosCliente">
                    <div class="input-field">
                        <input type="text" id="atencion" name="atencion">
                        <label for="atencion">Atención</label>
                    </div>
                    <div class="input-field">
                        <input type="text" id="telefono_atencion" name="telefono_atencion">
                        <label for="telefono_atencion">Teléfono Atención</label>
                    </div>

                    <div class="input-field" id="wrapperEmpresaSelect" style="display:none;">
                        <select id="empresaSelect" class="browser-default">
                            <option value="" disabled selected>Selecciona una empresa</option>
                        </select>
                        <label for="empresaSelect">Seleccionar Empresa (Cliente)</label>
                    </div>

                    <div class="input-field" id="wrapperEmpresaInput">
                        <input type="text" id="empresa" name="empresa">
                        <label for="empresa">Empresa</label>
                    </div>

                    <div class="input-field">
                        <input type="text" id="telefono_empresa" name="telefono_empresa">
                        <label for="telefono_empresa">Teléfono Empresa</label>
                    </div>
                    <div class="input-field">
                        <input type="email" id="email" name="email">
                        <label for="email">Email</label>
                    </div>
                </div>

                <div id="gruasContainer">
                    <div class="grua-item">
                        <div class="input-field">
                            <select id="grua1" name="grua[]" class="browser-default gruaSelect"></select>
                            <label for="grua1">Grúa 1</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="minimo_horas1" name="minimo_horas[]" class="minimoHoras">
                            <label for="minimo_horas1">Mínimo Horas</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="precio_por_hora1" name="precio_por_hora[]" step="0.01" class="precioPorHora">
                            <label for="precio_por_hora1">Precio por Hora (antes de IVA)</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="traslado_ida1" name="traslado_ida[]" step="0.01" class="trasladoIda">
                            <label for="traslado_ida1">Horas Traslado Ida</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="traslado_vuelta1" name="traslado_vuelta[]" step="0.01" class="trasladoVuelta">
                            <label for="traslado_vuelta1">Horas Traslado Vuelta</label>
                        </div>
                        <div class="input-field">
    <textarea id="descripcion_maniobra1" name="descripcion_maniobra[]" class="materialize-textarea descripcionManiobra"></textarea>
    <label for="descripcion_maniobra1">Descripción de la Maniobra</label>
  </div>
                    </div>
                </div>
                <button type="button" id="btnAgregarGrua" class="btn">Agregar Grúa</button>

                <div class="input-field">
                    <input type="text" id="domicilio_servicio" name="domicilio_servicio">
                    <label for="domicilio_servicio">Domicilio Servicio</label>
                </div>
                <div class="input-field">
                    <textarea id="descripcion_servicio" name="descripcion_servicio" class="materialize-textarea"></textarea>
                    <label for="descripcion_servicio">Descripción del Servicio</label>
                </div>

                <div class="switch">
    <label>
        ¿Aplica Contrapeso?
        No
        <input type="checkbox" id="aplicaContrapeso" name="aplicaContrapeso" value="1"> 
        <span class="lever"></span>
        Sí
    </label>
</div>
                <div id="contrapesoCampos" style="display:none;">
                    <div class="input-field">
                        <input type="number" id="traslado_contrapeso" name="traslado_contrapeso" step="0.01">
                        <label for="traslado_contrapeso">Monto Traslado Contrapeso</label>
                    </div>
                    <div class="input-field">
                    <textarea id="descripcion_contrapeso" name="descripcion_contrapeso" class="materialize-textarea"></textarea>

                        
                        <label for="descripcion_contrapeso">Descripción del Contrapeso</label>
                    </div>
                </div>

                <div class="switch">
    <label>
        ¿Aplicar IVA?
        No
        <input type="checkbox" id="aplicaIVA" name="aplicaIVA" value="1" checked> 
        <span class="lever"></span>
        Sí
    </label>
</div>
                <div class="input-field">
                    <input type="number" id="tiempo_maniobra" name="tiempo_maniobra" readonly>
                    <label for="tiempo_maniobra">Tiempo Maniobra (horas)</label>
                </div>
                <div class="input-field">
                    <input type="number" id="subtotal" name="subtotal" readonly>
                    <label for="subtotal">Subtotal</label>
                </div>
                <div class="input-field">
                    <input type="number" id="iva" name="iva" readonly>
                    <label for="iva">IVA (16%)</label>
                </div>

                <div class="input-field">
    <input type="text" id="total_display" readonly>
    <label for="total_display">Total</label>
    <input type="hidden" id="total" name="total"> <!-- Campo oculto para el valor numérico -->
</div>

                <button type="submit" class="btn btn-create-user blue waves-effect waves-light" id="btnGuardarCambios">Guardar Cotización</button>
            </form>
        </div>

        <div class="row" style="margin-top:20px;">
            <div class="input-field col s12">
                <input type="text" id="buscadorCotizaciones" placeholder="Buscar cotizaciones...">
                <label for="buscadorCotizaciones">Buscar</label>
            </div>
        </div>

        <h5 class="center-align">Cotizaciones Capturadas</h5>
        <table class="highlight centered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Atención</th>
                    <th>Teléfono Atención</th>
                    <th>Empresa</th>
                    <th>Total</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaCotizaciones">
            </tbody>
        </table>
    </div>

    <div id="modalVer" class="modal">
        <div class="modal-content">
            <h5>Detalle de Cotización/Servicio</h5>
            <div id="detalleCotizacionContenido"></div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close btn">Cerrar</a>
        </div>
    </div>

    <div id="modalServicio" class="modal">
        <div class="modal-content">
            <h5>Convertir en Servicio</h5>
            <form id="formConvertirServicio" enctype="multipart/form-data">
                <input type="hidden" name="id" id="serv_id">
                <div class="input-field">
                    <input type="text" name="numero_nota_servicio" id="serv_numero_nota_servicio">
                    <label for="serv_numero_nota_servicio">Número de Nota de Servicio</label>
                </div>
                <div class="file-field input-field">
                    <div class="btn">
                        <span>Evidencia de Servicio</span>
                        <input type="file" name="evidencia_servicio" id="serv_evidencia_servicio">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Sube un PDF o Imagen">
                    </div>
                </div>
                <div class="input-field">
                    <input type="time" name="salida_base" id="serv_salida_base">
                    <label for="serv_salida_base">Salida de Base</label>
                </div>
                <div class="input-field">
                    <input type="time" name="entrada_cliente" id="serv_entrada_cliente">
                    <label for="serv_entrada_cliente">Entrada Cliente</label>
                </div>
                <div class="input-field">
                    <input type="time" name="salida_cliente" id="serv_salida_cliente">
                    <label for="serv_salida_cliente">Salida Cliente</label>
                </div>
                <div class="input-field">
                    <input type="time" name="entrada_base" id="serv_entrada_base">
                    <label for="serv_entrada_base">Entrada a Base</label>
                </div>
                <div class="input-field">
                    <input type="text" name="tiempo_servicio" id="serv_tiempo_servicio">
                    <label for="serv_tiempo_servicio">Tiempo (horas)</label>
                </div>
                <button type="submit" class="btn">Convertir</button>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close btn">Cerrar</a>
        </div>
    </div>

    <script>
        // Inicializar los modales de Materialize
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            M.Modal.init(elems);
        });
    </script>

    <script>
        // Declarar editMode como una variable global
let editMode = false; 

        $(document).ready(function() {
            let gruaCount = 1; // Contador para el número de grúas
            let clientsData = []; // Array para almacenar los datos de los clientes

            // Mostrar/ocultar el formulario de cotización
            $('#btnMostrarFormulario').on('click', function() {
                $('#formularioCotizacion').toggle();
            });



            // Actualizar campos ocultos al cambiar los checkboxes
$('#aplicaContrapeso').on('change', function() {
    if ($(this).is(':checked')) {
        $('#contrapesoCampos').show();
        $('#hiddenAplicaContrapeso').val('1'); // Actualizar campo oculto
    } else {
        $('#contrapesoCampos').hide();
        $('#hiddenAplicaContrapeso').val('0'); // Actualizar campo oculto
    }
});

$('#aplicaIVA').on('change', function() {
    if ($(this).is(':checked')) {
        $('#hiddenAplicaIVA').val('1'); // Actualizar campo oculto
    } else {
        $('#hiddenAplicaIVA').val('0'); // Actualizar campo oculto
    }
});

            // Mostrar/ocultar el selector de empresa y cargar clientes si es necesario
            $('#esCliente').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#hiddenEsCliente').val('on');
                    $('#wrapperEmpresaSelect').show();
                    cargarClientes(); // Cargar la lista de clientes
                } else {
                    $('#hiddenEsCliente').val('');
                    $('#wrapperEmpresaSelect').hide();
                }
            });

            // Autocompletar los campos de empresa al seleccionar un cliente
            $('#empresaSelect').on('change', function() {
                var selectedId = $(this).val();
                var selectedClient = clientsData.find(c => c.id == selectedId);
                if (selectedClient) {
                    $('#empresa').val(selectedClient.empresa);
                    $('#telefono_empresa').val(selectedClient.telefono_principal);
                    $('#email').val(selectedClient.email);
                    M.updateTextFields(); // Actualizar los campos de Materialize
                }
            });

// Delegar el evento "input" al contenedor #gruasContainer
$('#gruasContainer').on('input', '.minimoHoras, .precioPorHora, .trasladoIda, .trasladoVuelta', function() {
  calcularTiempoManiobra();
  calcularTotales();
});

// Actualizar los totales al agregar una nueva grúa
// $('#btnAgregarGrua').on('click', function() {
//   gruaCount++;
//   const nuevaGrua = `
//     <div class="grua-item">
//       <div class="input-field">
//         <select id="grua${gruaCount}" name="grua[]" class="browser-default gruaSelect"></select>
//         <label for="grua${gruaCount}">Grúa ${gruaCount}</label>
//       </div>
//       <div class="input-field">
//         <input type="number" id="minimo_horas${gruaCount}" name="minimo_horas[]" class="minimoHoras">
//         <label for="minimo_horas${gruaCount}">Mínimo Horas</label>
//       </div>
//       <div class="input-field">
//         <input type="number" id="precio_por_hora${gruaCount}" name="precio_por_hora[]" step="0.01" class="precioPorHora">
//         <label for="precio_por_hora${gruaCount}">Precio por Hora (antes de IVA)</label>
//       </div>
//       <div class="input-field">
//         <input type="number" id="traslado_ida${gruaCount}" name="traslado_ida[]" step="0.01" class="trasladoIda">
//         <label for="traslado_ida${gruaCount}">Horas Traslado Ida</label>
//       </div>
//       <div class="input-field">
//         <input type="number" id="traslado_vuelta${gruaCount}" name="traslado_vuelta[]" step="0.01" class="trasladoVuelta">
//         <label for="traslado_vuelta${gruaCount}">Horas Traslado Vuelta</label>
//       </div>
//       <div class="input-field">
//         <textarea id="descripcion_maniobra${gruaCount}" name="descripcion_maniobra[]" class="materialize-textarea descripcionManiobra"></textarea>
//         <label for="descripcion_maniobra${gruaCount}">Descripción de la Maniobra</label>
//       </div>
//     </div>
//   `;

//   // Agregar la nueva grúa después de la última grúa existente
//   $('#gruasContainer .grua-item:last').after(nuevaGrua);

//   cargarGruas(); // Cargar las opciones de grúas en el nuevo select

//   // Actualizar los cálculos después de agregar la grúa
//   calcularTiempoManiobra();
//   calcularTotales();
// });
$('#btnAgregarGrua').on('click', function() {
    // Guardar las selecciones actuales de todos los <select> existentes
    let seleccionesExistentes = {};
    $('.gruaSelect').each(function() {
        let id = $(this).attr('id');
        seleccionesExistentes[id] = $(this).val();
    });

    gruaCount++;
    const nuevaGrua = `
        <div class="grua-item">
            <div class="input-field">
                <select id="grua${gruaCount}" name="grua[]" class="browser-default gruaSelect"></select>
                <label for="grua${gruaCount}">Grúa ${gruaCount}</label>
            </div>
            <div class="input-field">
                <input type="number" id="minimo_horas${gruaCount}" name="minimo_horas[]" class="minimoHoras">
                <label for="minimo_horas${gruaCount}">Mínimo Horas</label>
            </div>
            <div class="input-field">
                <input type="number" id="precio_por_hora${gruaCount}" name="precio_por_hora[]" step="0.01" class="precioPorHora">
                <label for="precio_por_hora${gruaCount}">Precio por Hora (antes de IVA)</label>
            </div>
            <div class="input-field">
                <input type="number" id="traslado_ida${gruaCount}" name="traslado_ida[]" step="0.01" class="trasladoIda">
                <label for="traslado_ida${gruaCount}">Horas Traslado Ida</label>
            </div>
            <div class="input-field">
                <input type="number" id="traslado_vuelta${gruaCount}" name="traslado_vuelta[]" step="0.01" class="trasladoVuelta">
                <label for="traslado_vuelta${gruaCount}">Horas Traslado Vuelta</label>
            </div>
            <div class="input-field">
                <textarea id="descripcion_maniobra${gruaCount}" name="descripcion_maniobra[]" class="materialize-textarea descripcionManiobra"></textarea>
                <label for="descripcion_maniobra${gruaCount}">Descripción de la Maniobra</label>
            </div>
        </div>
    `;

    // Agregar la nueva grúa al DOM
    $('#gruasContainer .grua-item:last').after(nuevaGrua);

    // Cargar las opciones solo en el nuevo <select>
    cargarGruas(`#grua${gruaCount}`);

    // Restaurar las selecciones previas
    Object.keys(seleccionesExistentes).forEach(id => {
        $(`#${id}`).val(seleccionesExistentes[id]);
    });

    // Actualizar cálculos
    calcularTiempoManiobra();
    calcularTotales();
});



            // Calcular el tiempo de maniobra
            function calcularTiempoManiobra() {
  let tiempoManiobraTotal = 0;

  $('.grua-item').each(function(index) {
    const gruaIndex = index + 1;
    const minimoHoras = parseFloat($(`#minimo_horas${gruaIndex}`).val()) || 0;
    const trasladoIda = parseFloat($(`#traslado_ida${gruaIndex}`).val()) || 0;
    const trasladoVuelta = parseFloat($(`#traslado_vuelta${gruaIndex}`).val()) || 0;

    // Sumar el tiempo de maniobra de esta grúa al total
    tiempoManiobraTotal += minimoHoras - trasladoIda - trasladoVuelta; 
  });

  $('#tiempo_maniobra').val(tiempoManiobraTotal > 0 ? tiempoManiobraTotal.toFixed(2) : 0);
}

// Calcular los totales de la cotización
 
function calcularTotales() {
    let subtotal = 0;
    const aplicaContrapeso = $('#aplicaContrapeso').is(':checked');
    const trasladoContrapeso = aplicaContrapeso ? (parseFloat($('#traslado_contrapeso').val()) || 0) : 0;

    subtotal += trasladoContrapeso;

    $('.grua-item').each(function(index) {
        const gruaIndex = index + 1;
        const precioPorHora = parseFloat($(`#precio_por_hora${gruaIndex}`).val()) || 0;
        const minimoHoras = parseFloat($(`#minimo_horas${gruaIndex}`).val()) || 0;
        subtotal += precioPorHora * minimoHoras;
    });

    const iva = $('#aplicaIVA').is(':checked') ? subtotal * 0.16 : 0;
    const total = subtotal + iva;

    // Asignar valores
    $('#subtotal').val(subtotal.toFixed(2)); // Subtotal sin formato (puedes ajustarlo también si quieres)
    $('#iva').val(iva.toFixed(2)); // IVA sin formato (puedes ajustarlo también)
    $('#total').val(total.toFixed(2)); // Valor numérico en el campo oculto
    $('#total_display').val(total.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })); // Valor formateado visible

    // Actualizar etiquetas de Materialize
    M.updateTextFields();
}


// Actualizar los cálculos al cambiar los valores relevantes
$('#traslado_contrapeso, #aplicaContrapeso, .minimoHoras, .precioPorHora, .trasladoIda, .trasladoVuelta, #aplicaIVA').on('input', function() {
    calcularTiempoManiobra();
    calcularTotales();
});




            // Cargar la lista de clientes desde el servidor
            function cargarClientes() {
                $.ajax({
                    url: '../../modules/cotizaciones/obtener_clientes.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        clientsData = data;
                        clientsData.sort((a, b) => a.empresa.localeCompare(b.empresa));

                        $('#empresaSelect').empty();
                        $('#empresaSelect').append('<option value="" disabled selected>Selecciona una empresa</option>');
                        clientsData.forEach(cliente => {
                            $('#empresaSelect').append(`<option value="${cliente.id}">${cliente.empresa}</option>`);
                        });
                    }
                });
            }

            // Cargar la lista de grúas desde el servidor

           /*
            function cargarGruas() {
    $.ajax({
        url: '../../modules/cotizaciones/obtener_gruas.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('.gruaSelect').each(function () {
                $(this).empty();
                data.forEach(grua => {
                    $(this).append(`<option value="${grua.id}">${grua.marca} - ${grua.tonelaje} Ton</option>`);
                });
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error al cargar la lista de grúas:", textStatus, errorThrown);
            M.toast({html: 'Error al cargar la lista de grúas.', classes: 'red'});
        }
    });
}
*/
function cargarGruas(targetSelect = null) {
    $.ajax({
        url: '../../modules/cotizaciones/obtener_gruas.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (targetSelect) {
                $(targetSelect).empty();
                $(targetSelect).append('<option value="">Selecciona una grúa</option>');
                data.forEach(grua => {
                    $(targetSelect).append(`<option value="${grua.id}">${grua.marca} - ${grua.tonelaje} Ton</option>`);
                });
            } else {
                $('.gruaSelect').each(function () {
                    $(this).empty();
                    $(this).append('<option value="">Selecciona una grúa</option>');
                    data.forEach(grua => {
                        $(this).append(`<option value="${grua.id}">${grua.marca} - ${grua.tonelaje} Ton</option>`);
                    });
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error al cargar la lista de grúas:", textStatus, errorThrown);
            M.toast({html: 'Error al cargar la lista de grúas.', classes: 'red'});
        }
    });
}

// Validar la descripción del contrapeso
function validarContrapeso() {
  if ($('#aplicaContrapeso').is(':checked')) {
    const descripcionContrapeso = $('#descripcion_contrapeso').val().trim();
    if (descripcionContrapeso === '') {
      console.error('Error: Debes ingresar una descripción del contrapeso.');
      return false;
    } else {
      console.log('Descripción del contrapeso:', descripcionContrapeso);
      return true;
    }
  }
  return true; // Si no se aplica contrapeso, no se requiere validación
}

// Guardar la cotización en el servidor
function guardarCotizacion(event) {
  event.preventDefault();

  // Validar la empresa y la descripción del contrapeso
  if ($('#empresa').val().trim() === '') {
    alert('Por favor ingresa o selecciona una empresa.');
    return;
  }

  if (!validarContrapeso()) {
    return; // Detener el envío si la validación falla
  }

    // Determinar la URL según el modo (editar o guardar)
    var url = editMode ? '../../modules/cotizaciones/ajax_editar_cotizacion.php' : '../../modules/cotizaciones/guardar_cotizacion.php'; 

    $.ajax({
        url: url, 
        method: 'POST',
        dataType: 'json',
        data: $('#formCotizacion').serialize(),
        success: function(response) {
            if (response.success) {
                alert(editMode ? 'Cotización actualizada' : response.message);
                cargarCotizaciones(); // Recargar la tabla de cotizaciones

                // Restablecer el formulario
                editMode = false; // Asegurarse de que editMode vuelve a false después de guardar
                $('#id_edit').val('');
                $('#btnMostrarFormulario').text('Generar Cotización');
                $('#formCotizacion')[0].reset();
                $('#gruasContainer .grua-item:not(:first)').remove(); // Eliminar grúas adicionales
                gruaCount = 1; // Restablecer el contador
                M.updateTextFields();
            } else {
                alert('No se pudo guardar la cotización: ' + response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error al guardar la cotización:", textStatus, errorThrown);
            console.log("Respuesta del servidor:", jqXHR.responseText); 
        }
    });
}


            // Manejar el envío del formulario de cotización
            $('#formCotizacion').on('submit', guardarCotizacion);

            

            // // Cargar la lista de cotizaciones desde el servidor

            function cargarCotizaciones() {
    $.ajax({
        url: '../../modules/cotizaciones/ajax_obtener_cotizaciones.php',
        dataType: 'json',
        success: function(data) {
            $('#tablaCotizaciones').empty();
            if (Array.isArray(data)) {
                data.forEach(c => {
                    // Formatear el total con separadores de miles
                    let totalFormateado = parseFloat(c.total).toLocaleString('es-MX', { 
                        minimumFractionDigits: 2, 
                        maximumFractionDigits: 2 
                    });

                    let badge = '';
                    if (c.estatus === 'Cotización') badge = '<span class="badge yellow">Cotización</span>';
                    else if (c.estatus === 'Servicio') badge = '<span class="badge green">Servicio</span>';
                    else if (c.estatus === 'Cancelado') badge = '<span class="badge red">Cancelado</span>';

                    let btnVer = `<button class="btn-small blue lighten-2" onclick="verCotizacion(${c.id})">Ver</button>`;
                    let btnImprimir = `<button class="btn-small teal" onclick="imprimirCotizacion(${c.id})">Imprimir</button>`;
                    let btnEnviar = `<button class="btn-small green" onclick="enviarCotizacion(${c.id})">Enviar</button>`;
                    let btnConvertir = c.estatus === 'Cotización' ? `<button class="btn-small purple" onclick="convertirServicio(${c.id})">Convertir</button>` : '';
                    let btnCancelar = c.estatus === 'Cotización' ? `<button class="btn-small red" onclick="cancelarCotizacion(${c.id})">Cancelar</button>` : '';

                    $('#tablaCotizaciones').append(`
                        <tr>
                            <td>${c.id}</td>
                            <td>${c.atencion}</td>
                            <td>${c.telefono_atencion}</td>
                            <td>${c.empresa || ''}</td>
                            <td>${totalFormateado}</td> <!-- Total con separadores de miles -->
                            <td>${badge}</td>
                            <td>
                                ${btnVer} ${btnImprimir} ${btnEnviar} ${btnConvertir} ${btnCancelar}
                            </td>
                        </tr>
                    `);
                });
            } else {
                console.error('La respuesta no es un arreglo:', data);
                $('#tablaCotizaciones').append('<tr><td colspan="7">Error al cargar los datos.</td></tr>');
                M.toast({ html: 'Error: Formato de datos inválido.', classes: 'red' });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
            $('#tablaCotizaciones').empty().append('<tr><td colspan="7">Error al conectar con el servidor.</td></tr>');
            M.toast({ html: 'Error al conectar con el servidor.', classes: 'red' });
        }
    });
}

            // Cargar las grúas al iniciar la página
            cargarGruas();
            // Cargar las cotizaciones al iniciar la página
            cargarCotizaciones();

            // Funciones para manejar las acciones de la tabla (ver, editar, imprimir, etc.)


            // Funcionalidad de búsqueda en la tabla
            $('#buscadorCotizaciones').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#tablaCotizaciones tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });


// 
window.verCotizacion = function(id) {
  $.ajax({
    url: '../../modules/cotizaciones/ajax_detalle_cotizacion.php',
    type: 'GET',
    data: { id: id },
    success: function(html) {
      $('#detalleCotizacionContenido').html(html);
      var instance = M.Modal.getInstance(document.getElementById('modalVer'));
      instance.open();
    }
  });
}

// 

window.imprimirCotizacion = function(id) {
  // Obtener la configuración de IVA
  const aplicaIVA = $('#aplicaIVA').is(':checked');

  // Construir la URL con la información de IVA
  const url = `imprimir_cotizacion.php?id=${id}&aplicaIVA=${aplicaIVA}`;

  window.open(url, '_blank');
}


//parte3
window.enviarCotizacion = function(id) {
  if (confirm('¿Estás seguro de que deseas enviar esta cotización por correo electrónico?')) {
    $.ajax({
      url: '../../modules/cotizaciones/ajax_enviar_cotizacion.php',
      type: 'POST',
      dataType: 'json',
      data: { id: id },
      success: function(resp) {
        if (resp.success) {
          M.toast({ html: resp.message, classes: 'green' });
        } else {
          M.toast({ html: 'Error: ' + resp.message, classes: 'red' });
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("Error al enviar la cotización:", textStatus, errorThrown);
        M.toast({ html: 'Error al enviar la cotización.', classes: 'red' });
      }
    });
  }
}
//parte5
window.borrarCotizacion = function(id) {
  if (confirm('¿Estás seguro de que deseas borrar esta cotización?')) {
    $.ajax({
      url: '../../modules/cotizaciones/borrar_cotizacion.php', // Asegúrate de que la ruta sea correcta
      type: 'POST',
      dataType: 'json',
      data: { id: id },
      success: function(resp) {
        if (resp.success) {
          M.toast({ html: resp.message, classes: 'green' });
          cargarCotizaciones(); // Recargar la tabla de cotizaciones
        } else {
          M.toast({ html: 'Error: ' + resp.message, classes: 'red' });
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("Error al borrar la cotización:", textStatus, errorThrown);
        M.toast({ html: 'Error al borrar la cotización.', classes: 'red' });
      }
    });
  }
}


window.convertirServicio = function(id) {
  // Obtener los datos de la cotización
  $.ajax({
    url: '../../modules/cotizaciones/ajax_get_cotizacion.php',
    type: 'GET',
    dataType: 'json',
    data: { id: id },
    success: function(data) {
      // Rellenar el modal con la información de la cotización
      $('#serv_id').val(data.id);

      // ... (Aquí puedes agregar código para rellenar otros campos del modal con la información de la cotización, si es necesario) ...

      // Abrir el modal
      var instance = M.Modal.getInstance(document.getElementById('modalServicio'));
      instance.open();
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error("Error al obtener la cotización:", textStatus, errorThrown);
      M.toast({ html: 'Error al obtener la cotización.', classes: 'red' });
    }
  });
}



$('#formConvertirServicio').on('submit', function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    url: '../../modules/cotizaciones/ajax_convertir_servicio.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    success: function(resp) {
      if (resp.success) {
        alert('Se ha convertido en servicio. Ya no podrá modificarse.');
        cargarCotizaciones();
        var instance = M.Modal.getInstance(document.getElementById('modalServicio'));
        instance.close();
      } else {
        alert('Error: ' + resp.message);
      }
    }
  });
});


window.cancelarCotizacion = function(id) {
    if (confirm('¿Estás seguro de que deseas cancelar esta cotización?')) {
        $.ajax({
            url: '../../modules/cotizaciones/ajax_cancelar_cotizacion.php',
            type: 'POST',
            dataType: 'json',
            data: { id: id },
            success: function(resp) {
                if (resp.success) {
                    // Mostrar alert
                    alert("Cotización cancelada"); 

                    // Refrescar la página
                    location.reload(); 
                } else {
                    M.toast({ html: 'Error: ' + resp.message, classes: 'red' });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error al cancelar la cotización:", textStatus, errorThrown);
                M.toast({ html: 'Error al cancelar la cotización.', classes: 'red' });
            }
        });
    }
}

    </script>
</body>
</html>