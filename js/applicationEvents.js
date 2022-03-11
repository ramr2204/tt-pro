/**
* applicationEvents.js
* Ruta:              /estampillas/js/applicationEvents.js
* Fecha Creación:    10/Nov/2014
*
* Contiene los eventos de los objetos del DOM contenidos en las vistas 
* con sus respectivas funciones
*
*
* @author           Michael Ortiz <michael.ortiz@turrisystem.com>
* @copyright        2014 Michael Ortiz
* @license          GPL 2 or later
* @version          2014-11-10
*
*/


$(window).ready(inicial);

//variable global que guarda el total de los
//usuarios hallados en el autocomplete
//actual
var usuariosActual=[];
usuariosActual['nombre']=[];
usuariosActual['id']=[];
var base_url;


function inicial () 
{
    base_url = $('#base').val();

    //Eventos Ingresar Papeleria
    $('.responsable').keyup(solicitarUsuarios);
	$('.responsable').focusout(cargarId);
	$('#codigoinicial').focusout(solicitarCodigos);

    //Eventos Reasignar Papeleria
    $('#btn-confirmarReassign').click(establecerDatosElegidos);

    //Eventos Importar Contratos
    $('#cargaImportacion').click(iniciarCarga);

    //Eventos Cargar Archivo conciliacion pagos
    $('#form-conciliacion').submit(renderizarInicioCarga);

    //Eventos informes vista consultar
    $('#btn-detalle-pdf').click(generarInformeDetallado);
    $('#btn-detalle-excel').click(generarInformeDetallado);

    $('#btn-relacion').click(generarInformeRelacion);
    $('#btn-rango').click(solicitarRango);
    $('#departamentoid_tramites').change(consultarMunicipios);
    $('#btn-consultar').click(generarInformeRangoImpresiones);
    $('#btn-consultar-detalle-pdf').click(generarInformeRangoImpresiones);
    $('#btn-consultar-detalle-excel').click(generarInformeRangoImpresiones);

    /*
    * Evento para solicitar recargar el formulario de adicionar papelería
    * con o sin la variable de contingencia
    */
    $('#chk_contingencia').click(solicitarRecargaPagina);

    /*
    * Eventos para checkbox de agrupamiento de información
    * en informes de impresiones
    */
    $('#group_mes').click(solicitarMarcarAnio);
    $('#group_anio').click(solicitarDesmarcarMes);
    $('#archivo_asobancaria').change(cambiarNombreInputFile);
    $('#imagen_tramite').change(cambiarNombreInputFile);
    $('#tramite_existe').change(ponerDisabledNombre);

    $('#tramite_vigencia').change(consultarTramites);
    $('#tramite_vigencia_totalizado').change(consultarTramites);
    $('#select-tipo-tramite-total').change(filtrarTotalizado);
    $('#btn-reestablecer-totalizado').click(eliminarFiltrosTotalizados);
    $('#btn-exportar-excel-tra-total').click(exportarExcelTotalizado);

    $('#desde_fecha_creacion_totalizado').change(filtrarTotalizado);
    $('#desde_fecha_final_totalizado').change(filtrarTotalizado);


    ////funciones cortas

    $('#desde_fecha_creacion').change(function(){
        var oSettings = $('#tabla_informe_pagos_tramites').dataTable().fnSettings();
        oSettings.sAjaxSource  = base_url +"index.php/informesPagosTramites/dataTable?fecha_ini="+ String($('#desde_fecha_creacion').val())+"&fecha_fin="+String($('#desde_fecha_final').val());
        $('#tabla_informe_pagos_tramites').dataTable().fnReloadAjax();
    });

    $('#desde_fecha_final').change(function(){
       var oSettings = $('#tabla_informe_pagos_tramites').dataTable().fnSettings();
        oSettings.sAjaxSource  = base_url +"index.php/informesPagosTramites/dataTable?fecha_ini="+ String($('#desde_fecha_creacion').val())+"&fecha_fin="+String($('#desde_fecha_final').val());
        $('#tabla_informe_pagos_tramites').dataTable().fnReloadAjax();
    });

    $('#select-tipo-tramite').change(function(){
       var oSettings = $('#tabla_informe_pagos_tramites').dataTable().fnSettings();
        oSettings.sAjaxSource  = base_url +"index.php/informesPagosTramites/dataTable?fecha_ini="+ String($('#desde_fecha_creacion').val())+"&fecha_fin="+String($('#desde_fecha_final').val() + "&tipo_tramite="+String($('#select-tipo-tramite').val()));
        $('#tabla_informe_pagos_tramites').dataTable().fnReloadAjax();
    });

    $('.filtro_pago_tramite').change(function(){
        $('#tabla_informe_pagos_tramites').dataTable().fnFilter($("input:radio[name=filtro_pago_tramite]:checked").val(), 12); 
    });
    
    $('#btn-reestablecer').click(function(){
        $('.filtro_pago_tramite').prop("checked", false);
        $('#desde_fecha_final').val('');
        $('#desde_fecha_creacion').val('');
        $('#tramite_vigencia').val('');
        $('#select-tipo-tramite').val('');
        $('.select-tipo-tramite option').remove();
        $('#tramite_vigencia').val('');
        $('#tabla_informe_pagos_tramites').dataTable().fnFilter('', 12);

        var oSettings = $('#tabla_informe_pagos_tramites').dataTable().fnSettings();
        oSettings.sAjaxSource  = base_url +"index.php/informesPagosTramites/dataTable";
        $('#tabla_informe_pagos_tramites').dataTable().fnReloadAjax();
    });

    $('#btn-exportar-excel-tra').click(function(){
        var pagado    = $("input:radio[name=filtro_pago_tramite]:checked").val();
        var fecha_ini = $("#desde_fecha_creacion").val();
        var fecha_fin = $("#desde_fecha_final").val();
        var tipo_tramite = $("#select-tipo-tramite").val();
        window.open(base_url +"informesPagosTramites/exportarExcelFacturacion?pagado="+pagado+"&fecha_ini="+String(fecha_ini)+"&fecha_fin="+String(fecha_fin)+"&tipo_tramite="+String(tipo_tramite));
    });

    $('#agregarConceptos').click(mostrarConceptos);

    $('#validarTramitesConceptos').click(validarTramitesConceptos);
    $('#validarTramitesConceptosEdit').click(validarTramitesConceptosEdit);

    $('#table-concepto-tramites tbody').on('click', '.btn-consultar-modal-conceptos', consultatTramitesConceptos);


    /*
    * Solicita la identificacion de la vista de auditoria
    * para enlazar los eventos
    */
    identificarVistaAuditoria();
 
    //Solicita la identificacion de vistas
    //con controles timepicker
    //(consulta rango, cargar archivo pago)
    identificarVistaDatetimepicker();

    //Solicita la identificacion de vistas
    //con controles chosen
    identificarVistaChosen();

    //Solicita la identificacion de la vista
    //listado de conciliaciones para eliminar
    //el css del container en esa vista
    identificarVistaListadoConciliaciones();
}

/**
 * Funcion de apoyo que valida si enviar o no la impresion
 *  de la estampilla segun verificacion de rotulo
 */
async function validarNumeroRotuloLiquidador(event) 
{
    var objEvento = $(this);
    event.preventDefault();

    /*
    * Solicita el ultimo rotulo para la impresion
    */
    await solicitarUltimoRotulo().then(
        function exito(siguienteEstampilla)
        {
            var bandEnviarImpresion = true;
            if (!confirm('.::SIGUIENTE ESTAMPIILLA A IMPRIMIRSE => No. ' + siguienteEstampilla + '::.\n\n'
                + 'Esta seguro de generar la impresión?'
                + ' Recuerde que será modificado el consecutivo de la papeleria asignada a usted!')) {
                bandEnviarImpresion = false;
            }
        
            if(bandEnviarImpresion)
            {
                for (var i = 1; i <= 3; i++) {
                    var inputTeclado = prompt(".::POR FAVOR CONFIRME EL NÚMERO DE ROTULO FÍSICO A IMPRIMIR::.",
                        Math.floor(Math.random() * 10000));
                    if (inputTeclado != siguienteEstampilla) {
                        alert('.::EL NÚMERO DE ROTULO FÍSICO ESPECIFICADO POR USTED NO CORRESPONDE CON EL ROTULO FÍSICO '
                            + 'SIGUIENTE EN EL SISTEMA::.');
                        bandEnviarImpresion = false;
                        break;
                    }
                }
            }
        
            if (bandEnviarImpresion) {
                objEvento.attr('disabled', 'disabled');
                window.open(objEvento.attr('href'), '_blank');
            }
        },
        function fallo(mensajeError)
        {
            alert(".:: " + mensajeError +" ::.");
        }
    );
}

function filtrarTotalizado()
{
    var oSettings = $('#tabla_informe_totalizado_tramites').dataTable().fnSettings();

    oSettings.sAjaxSource  = base_url +"index.php/totalizadoPersonaTramite/dataTable?fecha_ini="+
    String($('#desde_fecha_creacion_totalizado').val())+"&fecha_fin="+String($('#desde_fecha_final_totalizado').val())
    +"&tipo_tramite="+ String($('#select-tipo-tramite-total').val());

    $('#tabla_informe_totalizado_tramites').dataTable().fnReloadAjax();
}

function cambiarNombreInputFile()
{
    var file = $(this)[0].files[0].name;
    $(this).parent().find('span').text(file);
}

function ponerDisabledNombre()
{
    if($(this).val() != 0)
    {
        $('#nombre_tramite_concepto').prop('disabled', true);
    }
    else
    {
        $('#nombre_tramite_concepto').prop('disabled', false);
    }
}

function consultarTramites(e)
{
    e.preventDefault();
    e.stopImmediatePropagation();
    $('.select-tipo-tramite option').remove();
    $.ajax({
        'url': base_url + "liquidacionTramite/consultarTramite?vigencia_tramite="+$(this).val(),
        'method': 'GET',
        success: function(data)
        {
            $('.select-tipo-tramite').append('<option value="">Seleccione Opción</option>');
            data = JSON.parse(data);
            data.forEach(function(dato)
            {
                $('.select-tipo-tramite').append('<option value="'+dato.id+'">'+dato.nombre+'</option>');
            })
        }

    })
}

function reloadDatatableTotalizado()
{
    var oSettings = $('#tabla_informe_totalizado_tramites').dataTable().fnSettings();
    oSettings.sAjaxSource  = base_url +"index.php/totalizadoPersonaTramite/dataTable?tipo_tramite="+ String($('#select-tipo-tramite-total').val());
    $('#tabla_informe_totalizado_tramites').dataTable().fnReloadAjax();
}

function eliminarFiltrosTotalizados()
{
    $('.select-tipo-tramite option').remove();
    $('#tramite_vigencia_totalizado').val('');
    $('#desde_fecha_creacion_totalizado').val('');
    $('#desde_fecha_final_totalizado').val('');
    
    var oSettings = $('#tabla_informe_totalizado_tramites').dataTable().fnSettings();
    oSettings.sAjaxSource  = base_url +"index.php/totalizadoPersonaTramite/dataTable";
    $('#tabla_informe_totalizado_tramites').dataTable().fnReloadAjax();
}

function exportarExcelTotalizado()
{
    window.open(base_url +"totalizadoPersonaTramite/exportarExcelFacturacion?tipo_tramite="+String($('#select-tipo-tramite-total').val()));
}

function mostrarConceptos()
{
    $('.conceptos').append(
        '<div>'+
            '<hr>'+
            '<button type="button" id="eliminarConceptos" class="btn btn-danger btn-sm" style="float: right;margin-bottom: 10px" onclick="$(this).parent().remove()"><i class="fa fa-trash-o"></i></button>'+
            '<div class="form-group">'+
                '<label for="valor">Nombre Concepto</label>'+
                '<input class="form-control" name="nombre_concepto[]" required="required" maxlength="128" />'+
            '</div>'+
            '<div class="form-group">'+
                '<label for="valor">Valor Concepto</label>'+
                '<input class="form-control" name="valor_concepto[]" required="required" maxlength="128" />'+
            '</div>'+
        '</div>'
    );
}

function validarTramitesConceptos()
{
    var arrayErrores = [];
    var band = true;

    if($('#vigencia_concepto').val() == '')
    {
        arrayErrores.push('La vigencia concepto es requerida');
    }

    if($('#nombre_tramite_concepto').val() == '' && $('#tramite_existe').val() == 0)
    {
        arrayErrores.push('El nombre trámite es requerido');
    }


    if($('input[name^="nombre_concepto"]').val() == undefined)
    {
        arrayErrores.push('Debe existir por lo menos un nombre concepto');
    }
    else
    {
        $('input[name^="nombre_concepto"]').each(function(index, data) {
            if($(this).val() == '')
            {
                arrayErrores.push('El nombre trámite del concepto es requerido en la posición ' + (index+1));
            }
        });
    }

    if($('input[name^="valor_concepto"]').val() == undefined)
    {
        arrayErrores.push('Debe existir por lo menos un valor concepto');
    }
    else
    {

        $('input[name^="valor_concepto"]').each(function(index, data) {
            if($(this).val() == '')
            {
                arrayErrores.push('El valor concepto es requerido en la posición ' + (index+1));
            }
        });

    }

    if(arrayErrores.length > 0)
    {
        band = false;
    }

    if(band)
    {
        $('.alert-danger-conceptos').css('display', 'none');

        $('.alert-conceptos').html('');

        $('#formulario_conceptos_tramites').submit();
    }
    else
    {
        $('.alert-danger-conceptos').css('display', 'block');
        $('.alert-conceptos').html('');
        arrayErrores.forEach(function(data, index)
        { 
            $('.alert-conceptos').append(
                '<li>'+data+'</li>'
            );
        })
    }

}

function validarTramitesConceptosEdit()
{
    var arrayErrores = [];
    var band = true;

    if($('#nombre_tramite_edit').val() == '')
    {
        arrayErrores.push('El nombre trámite es requerido');
    }


    if($('input[name^="nombre_concepto"]').val() == undefined)
    {
        arrayErrores.push('Debe existir por lo menos un nombre concepto');
    }
    else
    {
        $('input[name^="nombre_concepto"]').each(function(index, data) {
            if($(this).val() == '')
            {
                arrayErrores.push('El nombre trámite del concepto es requerido en la posición ' + (index+1));
            }
        });
    }

    if($('input[name^="valor_concepto"]').val() == undefined)
    {
        arrayErrores.push('Debe existir por lo menos un valor concepto');
    }
    else
    {

        $('input[name^="valor_concepto"]').each(function(index, data) {
            if($(this).val() == '')
            {
                arrayErrores.push('El valor concepto es requerido en la posición ' + (index+1));
            }
        });

    }

    if(arrayErrores.length > 0)
    {
        band = false;
    }

    if(band)
    {
        $('.alert-danger-conceptos').css('display', 'none');

        $('.alert-conceptos').html('');

        $('#formulario_conceptos_tramites_edit').submit();
    }
    else
    {
        $('.alert-danger-conceptos').css('display', 'block');
        $('.alert-conceptos').html('');
        arrayErrores.forEach(function(data, index)
        { 
            $('.alert-conceptos').append(
                '<li>'+data+'</li>'
            );
        })
    }
}

function consultatTramitesConceptos()
{
    $('#conceptos-tramites-modal').modal('show');

    var oSettings = $('#table-concepto-tramites-modal').dataTable().fnSettings();
    oSettings.sAjaxSource  = base_url +"index.php/tipoLiquidacionTramite/dataTableConceptos?id="+$(this).val();
    $('#table-concepto-tramites-modal').dataTable().fnReloadAjax();
}

/**
* Funcion de Apoyo que solicita el ultimo rotulo impreso
* del usuario liquidador
*/
function solicitarUltimoRotulo() 
{
    return new Promise(function (exito, fallo) {
        var usuario = $('[rol="usuario_rotulo"]').html();
        $.ajax({
            type: "POST",
            dataType: "json",
            data: { usuario: usuario },
            url: base_url + "index.php/liquidaciones/solicitarUltimoRotuloImpreso",
            success: function (data) {
                if (data.hayRotuloDisponible == 'SI')
                {
                    exito(data.numeroRotulo);
                }else if(data.hayRotuloDisponible == 'NO')
                    {
                        fallo(data.notificacionErr);
                    }
            }
        });
    });
}

/*
* Funcion que identifica si el checkbox de contingencia
* está o no seleccionado para solicitar recargar
* la pagina con los datos de contingencia
*/
function solicitarRecargaPagina(e)
{
    if($(this).is(':checked'))
    {
        window.location.assign(base_url+'index.php/papeles/add?contin=SI');
    }else
        {
            window.location.assign(base_url+'index.php/papeles/add?contin=NO');
        }
}

/*
* Función que identifica si el checkbox de agrupamiento
* por meses fue seleccionado en informe de impresiones
* para seleccionar tambien el checkbox de año
*/
function solicitarMarcarAnio(e)
{
    if($(this).is(':checked'))
    {
        $('#group_anio').prop('checked', true);
    }
}

/*
* Función que identifica si el checkbox de agrupamiento
* por años fue de-seleccionado en informe de impresiones
* para de-seleccionar tambien el checkbox de mes
*/
function solicitarDesmarcarMes(e)
{
    if(!$(this).is(':checked'))
    {
        $('#group_mes').prop('checked', false);
    }
}

/*
* Funcion de apoyo que identifica si se está
* en la vista listado de conciliaciones
*/
function identificarVistaListadoConciliaciones()
{
    /*
    * Valida si en la vista hay clases
    * chosen
    */
    var n = 0;
    $('body').find('#tabla_conciliaciones').each(function()
        {
            n++;     
        });

    if(n > 0)
    {        
        $('body').find('#cont_contenidogeneral').removeClass('container');
        $('body').find('#cont_contenidogeneral').addClass('col-custom');
    }
}

/*
* Funcion de apoyo que valida si los campos
* del formulario están diligenciados
* para activar la carga del ladda
*/
function renderizarInicioCarga(e)
{
    $('#not_conciliacion').show(); 
    $('.btn').attr('disabled','disabled');
}

//Funcion que activa el boton ladda
//para simulacion de carga
function iniciarCarga (e) 
{    
    var l = Ladda.create(this);
    l.start();
}

/*
* Funcion de apoyo que identifica si se está
* en una vista con controles chosen
*/
function identificarVistaChosen()
{
    /*
    * Valida si en la vista hay clases
    * chosen
    */
    var n = 0;
    $('body').find('.chosen').each(function()
        {
            n++;     
        });

    if(n > 0)
    {
        //Evento para los select con chosen
        $('.chosen').chosen({no_results_text: "No se encuentra"});
    }
}

function consultarMunicipios()
{
    var departamento_tramites = $('#departamentoid_tramites').val();
    $.ajax({
        'method': 'GET',
        'url': base_url+'liquidacionTramite/consultarMunicipios?depto='+departamento_tramites,
        success: function(data)
        {
            var data = JSON.parse(data);
            $('#municipioid_tramites option').remove();
            data.forEach(function(datos)
            {
                $('#municipioid_tramites').append("<option value='"+datos.muni_id+"'>"+datos.muni_nombre+"</option>");
            })
             $('#municipioid_tramites').trigger("chosen:updated");
        }
    });
}

/*
* Funcion de apoyo que solicita la generacion del informe
* de impresiones por rango de fecha
*/
function generarInformeRangoImpresiones(e)
{
    var fecha_inicial_impr = $('#m_rango').find('[name="f_inicial_impr"]').val();
    var fecha_final_impr   = $('#m_rango').find('[name="f_final_impr"]').val();
    var fecha_inicial_pago = $('#m_rango').find('[name="f_inicial_pago"]').val();
    var fecha_final_pago   = $('#m_rango').find('[name="f_final_pago"]').val();
    var fecha_inicial_liquidacion = $('#m_rango').find('[name="f_inicial_liquidacion"]').val();
    var fecha_final_liquidacion   = $('#m_rango').find('[name="f_final_liquidacion"]').val();
    var tipoEst       = $('#tipoEst').val();
    var tipoActo      = $('#tipoActo').val();
    var subTipoActo   = $('#subTipoActo').val();
    var contribuyente = $('#contribuyente').val();
    var contratante   = $('#contratante').val();
    var municipio     = $('#municipio').val();
    
    /*
    * Se valida numericamente que las fechas tengan valor
    * distinto de vacio
    */
    if(fecha_inicial_impr != '')
    {
        var fe_i_impr = 1;
    }else
        {
            var fe_i_impr = 0;
        }
    
    if(fecha_final_impr != '')
    {
        var fe_f_impr = 1;
    }else
        {
            var fe_f_impr = 0;
        }
    
    if(fecha_inicial_pago != '')
    {
        var fe_i_pago = 1;
    }else
        {
            var fe_i_pago = 0;
        }

    if(fecha_final_pago != '')
    {
        var fe_f_pago = 1;
    }else
        {
            var fe_f_pago = 0;
        }
    
    if(fecha_inicial_liquidacion != '')
    {
        var fe_i_liquidacion = 1;
    }else
        {
            var fe_i_liquidacion = 0;
        }

    if(fecha_final_liquidacion != '')
    {
        var fe_f_liquidacion = 1;
    }else
        {
            var fe_f_liquidacion = 0;
        }

    var sumBandFechasImpr = fe_i_impr + fe_f_impr;
    var sumBandFechasPago = fe_i_pago + fe_f_pago;
    var sumBandFechasLiqu = fe_i_liquidacion + fe_f_liquidacion;

    /*
    * Se validan los checkbox de agrupación
    */
    var bandAgrupar = 0;

    var group_anio = 0;
    if($('#group_anio').prop('checked'))
    {
        group_anio  = 1;
        bandAgrupar = 1;
    }

    var group_mes = 0;
    if($('#group_mes').prop('checked'))
    {
        group_mes   = 1;
        bandAgrupar = 1;
    }

    var group_contribuyente = 0;
    if($('#group_contribuyente').prop('checked'))
    {
        group_contribuyente = 1;
        bandAgrupar         = 1;
    }

    var group_tipoacto = 0;
    if($('#group_tipoacto').prop('checked'))
    {
        group_tipoacto = 1;
        bandAgrupar    = 1;
    }

    var group_subtipoacto = 0;
    if($('#group_subtipoacto').prop('checked'))
    {
        group_subtipoacto = 1;
        bandAgrupar       = 1;
    }

    if(sumBandFechasImpr > 0 || sumBandFechasPago > 0 || sumBandFechasLiqu > 0)
    {
        /*
        * Valida cual boton generó el evento si pdf o excel
        * para redireccionar respectivamente
        */
        var tipoInforme = $(this).attr('documento');
        if(tipoInforme == 'pdf')
        {
            window.open(base_url+'index.php/liquidaciones/renderizarDetalleRangoPDF?'
                +'fecha_I_impr='+fecha_inicial_impr
                +'&fecha_F_impr='+fecha_final_impr
                +'&fecha_I_pago=' + fecha_inicial_pago
                +'&fecha_F_pago=' + fecha_final_pago
                +'&fecha_I_liqu=' + fecha_inicial_liquidacion
                +'&fecha_F_liqu=' + fecha_final_liquidacion
                +'&est='+tipoEst
                +'&acto='+tipoActo
                +'&subtipo='+subTipoActo
                +'&contribuyente='+contribuyente
                +'&contratante='+contratante
                +'&municipio='+municipio
                +'&agrupar=0');
        }else if(tipoInforme == 'excel')
            {
                window.open(base_url+'index.php/liquidaciones/renderizarDetalleRangoExcel?'
                    +'fecha_I_impr=' + fecha_inicial_impr
                    +'&fecha_F_impr=' + fecha_final_impr
                    +'&fecha_I_pago=' + fecha_inicial_pago
                    +'&fecha_F_pago=' + fecha_final_pago
                    +'&fecha_I_liqu=' + fecha_inicial_liquidacion
                    +'&fecha_F_liqu=' + fecha_final_liquidacion
                    +'&est='+tipoEst
                    +'&acto='+tipoActo
                    +'&subtipo='+subTipoActo
                    +'&contribuyente='+contribuyente
                    +'&contratante='+contratante
                    +'&municipio='+municipio
                    +'&agrupar=0');
            }else if(tipoInforme == 'consolidado_pdf')
                {
                    window.open(base_url+'index.php/liquidaciones/renderizarConsolidadoRangoImpresionesPDF?'
                        +'fecha_I_impr=' + fecha_inicial_impr
                        +'&fecha_F_impr=' + fecha_final_impr
                        +'&fecha_I_pago=' + fecha_inicial_pago
                        +'&fecha_F_pago=' + fecha_final_pago
                        +'&fecha_I_liqu=' + fecha_inicial_liquidacion
                        +'&fecha_F_liqu=' + fecha_final_liquidacion
                        +'&est='+tipoEst
                        +'&acto='+tipoActo
                        +'&subtipo='+subTipoActo
                        +'&contribuyente='+contribuyente
                        +'&contratante='+contratante
                        +'&municipio='+municipio
                        +'&group_anio='+group_anio
                        +'&group_mes='+group_mes
                        +'&group_contribuyente='+group_contribuyente
                        +'&group_tipoacto='+group_tipoacto
                        +'&group_subtipoacto='+group_subtipoacto
                        +'&agruparvista='+bandAgrupar
                        +'&agrupar=1');
                }
    }
}

/*
* Funcion de apoyo que identifica si se encuentra en la vista
* de la tabla de auditoria de liquidaciones
*/
function identificarVistaAuditoria()
{
    var n = 0;
    $('body').find('#tabla_audit').each(function()
        {
            n++;
        });

    /*
    * Valida si encontró por lo menos una coincidencia
    * lo que indica que es la vista de auditoria
    */
    if(n > 0)
    {
        /*
        * Enlaza el evento que genera la datatable
        */
        var oTable = $('#tabla_audit').dataTable(objParametrosAudit()).columnFilter(objFiltrosAudit()).fnSearchHighlighting();
    }
}

/*
* Funcion de apoyo que construye el objeto parametro para la tabla de auditoria
*/
function objParametrosAudit()
{
    var parametros = {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url+"index.php/liquidaciones/liquidaciones_regimenotros",
        "sServerMethod": "POST",
        "iDisplayLength": 5,
        "aoColumns": [ 
                    { "sClass": "center","bVisible": false}, /*id 0*/
                    { "sClass": "center","sWidth": "6%" }, 
                    { "sClass": "center","sWidth": "6%" }, 
                    { "sClass": "item","sWidth": "14%" },
                    { "sClass": "item","sWidth": "14%" },
                    { "sClass": "item","sWidth": "45%" },  
                    { "sClass": "item","sWidth": "6%"},
                    { "sClass": "item","sWidth": "6%"},
                    { "sClass": "center","bSearchable": false},
                    { "sClass": "center","bSearchable": false},
                    { "sClass": "center","bVisible": false},
                    { "sClass": "center","bVisible": false},
                    { "sClass": "center","bVisible": false},
                    { "sClass": "center","bVisible": false},
            ],
        "fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) 
            {
                /*
                * Se formatean los valores
                */
                $("td:eq(2)", nRow).html('<span class="auto_num">' + aData[3] + '</span>');
                $("td:eq(3)", nRow).html('<span class="auto_num">' + aData[4] + '</span>');

                /*
                * Se solicitan los valores de las estampillas y sus porcentajes
                */
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: {id : aData[5]},
                    url: base_url+"index.php/liquidaciones/extraerFacturas",
                    success: function(data) {
                        $("td:eq(4)", nRow).html('<div class="text-left">' + data.estampillas + '</div>');                                        
                    }
                });

                /*
                * Se dibuja el nombre del usuario que liquidó
                * validando si el valor del campo en la tabla liquidaciones
                * no es nulo, si es nulo se establece la cadena N/R
                */
                var nombreUsuarioLiquida = 'N/R';
                if(aData[12] != null)
                {
                    nombreUsuarioLiquida = aData[10]+' '+aData[11];
                }
                $("td:eq(6)", nRow).html(nombreUsuarioLiquida);

                /*
                * Se dibuja el link para visualizar el soporte del contrato
                */
                $("td:eq(7)", nRow).html("<a href='"+ base_url + aData[7] +"' target='_blank'><img src='"+ base_url + aData[7] +"' class='file-preview-image' alt='Soporte contrato' title='Soporte contrato'  height='42' width='42'></a>");
                
                /*
                * Valida si la liquidación tiene estado liquidacion ok
                * para modificar el estilo del boton a renderizar
                */
                var datosVisualesBoton = {claseCss : 'success', nombre : 'Auditar'};
                if(aData[9] == 1)
                {
                    datosVisualesBoton.claseCss = 'primary';
                    datosVisualesBoton.nombre = 'Ver';
                }

                /*
                * Se dibuja el boton para solicitar el registro de observaciones de liquidación
                */
                $("td:eq(8)", nRow).html("<input type='button' id='aud_"+ aData[5] +"' owner='"+ aData[5] +"' class='btn btn-"+ datosVisualesBoton.claseCss +" auditar' value='"+ datosVisualesBoton.nombre +"' />");

                /*
                * Valida si ya fué auditada la liquidación
                * para sombrear la fila
                */
                if(aData[8] == 1)
                {
                    $("td:eq(8)", nRow).parent().addClass('warning');
                }

                /*
                * Se establece el id a la primer casilla de la fila
                * para resaltarla cuando se haya auditado
                */
                $("td:eq(8)", nRow).attr('id','liqu_'+aData[5]);

            },
        "fnDrawCallback": function( oSettings ) 
            {
                $(".auto_num").autoNumeric('init',{aSep:'.',aDec:',',aSign:'$ '});
                $(".auditar").click(solicitarAuditoria);
            }
        };
    return parametros;
}

/*
* Funcion de apoyo que retorna el objeto de configuracion de filtros para la tabla
* de auditoria
*/
function objFiltrosAudit()
{
    var filtros = {
        aoColumns: [
            {
                type: "number",
                sSelector: "#buscarnumero"
            },
            {
                type: "number",
                sSelector: "#buscarnit"
            },
            null,
            null,
            null,
            {    
                sSelector: "#buscarano", 
                type:"text",
                bSmart: false
            },
            null,
            null,
            null,
        ]
        };
    return filtros;
}

/*
* Funcion que resuelve la peticion del listado de auditoria para iniciar
* auditoría en las liquidaciones con régimen AUI
*/
function solicitarAuditoria(e)
{
    var liquId = $(this).attr('owner');
    if(liquId != '')
    {
        /*
        * Solicita las posibles observaciones previas
        * de auditoría y el estado de la auditoria (liqu_ok)
        */
        $.ajax({
               type: "POST",
               dataType: "json",
               data: {liquId : liquId},
               url: base_url+"index.php/liquidaciones/datosAuditoria",
               success: function(objResponse) {
                    console.log(objResponse);
                    /*
                    * Solicita la renderización de la modal para las
                    * observaciones
                    */
                    solicitarModalAuditoria(objResponse);
               }
            });
    }
}

/*
* Funcion que renderiza la modal con contenido para
* registrar nueva informacion
*/
function solicitarModalAuditoria(objResponse)
{
    /*
    * Establece el objeto de la modal
    */
    var objModal = $('#modal_auditoria');

    /*
    * Valida si el valor de las observaciones previas es nulo
    * para establecer el valor como vacio
    */
    obsAuditoria = objResponse.liqu_observacionesaudit;
    if(objResponse.liqu_observacionesaudit == null)
    {
        obsAuditoria = '';
    }

    /*
    * Establece el titulo en la modal
    */
    objModal.find('.modal-title').html('<span class="fa fa-pencil-square-o" aria-hidden="true"></span> Agregar observaciones auditoría');

    /*
    * Valida si la liquidacion tiene estado liqu_ok
    * para inhabilitar el campo de observaciones
    * y agregar la propiedad checked al check
    */
    var datosVisualesLiquidacion = {propiedadChk : '', propiedadTextA : ''};
    if(objResponse.liqu_ok == 1)
    {
        datosVisualesLiquidacion.propiedadChk = 'checked';
        datosVisualesLiquidacion.propiedadTextA = 'disabled';
    }
    
    /*
    * Crea el Formulario
    */
    var strForm = '<div class="col-xs-12"><div id="notificacion_auditoria"></div></div>'
        +'<div class="col-xs-12">'
            +'<div class="form-group">'
                +'<label>Ingrese la Informacion</label>'
                +'<textarea rows="10" class="form-control" name="observaciones_audit" owner="'+ objResponse.liqu_id +'" '+ datosVisualesLiquidacion.propiedadTextA +'>'+ obsAuditoria +'</textarea>'
            +'</div>'
            +'<div class="checkbox">'
                +'<label>'
                    +'<input type="checkbox" name="ok_audit" owner="'+ objResponse.liqu_id +'" '+ datosVisualesLiquidacion.propiedadChk +'> Liquidación OK'
                +'</label>'
            +'</div>'
        +'</div>';

    objModal.find('.modal-body').html(strForm);
    
    /*
    * Crea los botones para la modal
    */
    var strBotones = '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>'
        +'<button type="button" id="new_auditoria" class="btn btn-primary">Agregar</button>';
    
    objModal.find('.modal-footer').html(strBotones);

    /*
    * Enlaza el evento al boton para guardar
    */
    $('#new_auditoria').click(guardarInformacionAuditoria);

    objModal.modal('show');
}

/*
* Funcion que procesa la actualización de los datos de la auditoria
*/
function guardarInformacionAuditoria(e)
{
    /*
    * Extrae la información de los campos
    */
    var objDatosForm = {};
    objDatosForm.obsAuditoriaForm = $('[name="observaciones_audit"]').val();
    objDatosForm.idLiquidacion = $('[name="observaciones_audit"]').attr('owner');

    /*
    * Valida si el check fue activado
    */
    objDatosForm.ok_liquidacion = 0;
    if($('[name="ok_audit"]').is(':checked'))
    {
        objDatosForm.ok_liquidacion = 1;
    }

    /*
    * Solicita el registro de la informacion de auditoría
    */
    $.ajax({
            type: "POST",
            dataType: "json",
            data: objDatosForm,
            url: base_url+"index.php/liquidaciones/registrarAuditoria",
            success: function(objResponse) {
                /*
                * Renderiza la notificación
                */
                renderizarNotificacion('notificacion_auditoria',objResponse.mensaje, 'alert-success', 400);

                /*
                * Se solicita el establecimiento de propiedades visuales
                * para los campos de informacion de auditoria
                */
                actualizarEstadoVisualLiquidacion(objResponse);
            }
        });
}

/*
* Funcion que valida los valores establecidos en la modificación de la auditoria
* y actualiza la vista para la liquidacion
*/
function actualizarEstadoVisualLiquidacion(objModificacion)
{
    $('#liqu_'+objModificacion.liquidacion).parent().addClass('warning');

    /*
    * Valida si se estableció la propiedad liqu_ok en 1
    * para habilitar o inhabilitar el input de observaciones
    * y para modificar la visualización del botón en el listado
    */
    if(objModificacion.datos.liqu_ok == 1)
    {
        $('[name="observaciones_audit"]').attr('disabled','disabled');
        $('#aud_'+objModificacion.liquidacion).attr('value','Ver').removeClass('btn btn-success').addClass('btn btn-primary');
    }else
        {
            $('[name="observaciones_audit"]').removeAttr('disabled');
            $('#aud_'+objModificacion.liquidacion).attr('value','Auditar').removeClass('btn btn-primary').addClass('btn btn-success');
        }
}

/*
* Funcion de apoyo que identifica si se encuentra
* en la vista de consulta y tiene habilitado el rango
*/
function identificarVistaDatetimepicker()
{
    /*
    * Valida si es la vista de rango de fecha
    * para impresiones
    */
    var n = 0;
    $('body').find('#btn-rango').each(function()
        {
            n++;     
        });

    /*
    * Valida si es la vista de cargue de
    * archivo para conciliacion de pago
    */
    var c = 0;
    $('body').find('#btn-conciliacion').each(function()
        {
            c++;     
        });

    /*
    * Valida si es la vista de cargue agregar
    * documentos normativos
    */
    var docN = 0;
    $('body').find('#btn-documentosNormativosAdd').each(function()
        {
            docN++;     
        });

    /*
    * Valida si es la vista de creación de contrato estampillas    
    */
    var cE = 0;
    $('body').find('#btn-conpapAdd').each(function()
        {
            cE++;     
        });


    if(n > 0)
    {
        //Evento para el timepicker del rango de impresiones
        //por fecha de impresion
        $('#datetimepicker_inicial_impr').datetimepicker({
            pickTime: false
        });
        $('#datetimepicker_final_impr').datetimepicker({
            pickTime: false
        });
        //Evento para el timepicker del rango de impresiones
        //por fecha de pago
        $('#datetimepicker_inicial_pago').datetimepicker({
            pickTime: false
        });
        $('#datetimepicker_final_pago').datetimepicker({
            pickTime: false
        });
        //Evento para el timepicker del rango de impresiones
        //por fecha de liquidacion
        $('#datetimepicker_inicial_liquidacion').datetimepicker({
            pickTime: false
        });
        $('#datetimepicker_final_liquidacion').datetimepicker({
            pickTime: false
        });
    }

    if(docN > 0)
    {
        //Evento para el timepicker del cargue de ordenanzas
        $('#datetimepicker_fechadocnor').datetimepicker({
            pickTime: false
        });
        $('#datetimepicker_iniciodocnor').datetimepicker({
            pickTime: false
        });
    }

    if(c > 0)
    {
        //Evento para el timepicker de la fecha del archivo
        //de conciliacion de pagos
        $('#datetimepicker_conciliacion').datetimepicker({
            pickTime: false
        });        
    }

    if(cE > 0)
    {
        //Evento para el timepicker de la fecha del archivo
        //de conciliacion de pagos
        $('#datetimepicker_fechaContratoE').datetimepicker({
            pickTime: false
        });        
    }
}

/*
* Funcion que renderiza la modal para solicitar
* el rango de fechas para el informe
*/
function solicitarRango(e)
{
    $('#m_rango').modal('show');

    /*
    * Enlaza el chosen a los select luego de que se haya mostrado
    * la modal
     */
    $('#m_rango').on('shown.bs.modal', function () {

        //Evento para los select con chosen
        $('.chosen-modal').chosen({no_results_text: "No se encuentra"});

        /*
        * Evento para dibujar el select de subtipo de acto
        * dependiendo del tipo de acto suministrado para consultar
        */
        $('#tipoActo').chosen({no_results_text: "No se encuentra"}).change(solicitarSubtiposActoConsulta);
    });
}

/*
* Función que solicita los subtipos de acto para la consulta
* de impresiones y redibuja los select de subtipos
*/
function solicitarSubtiposActoConsulta(e)
{
    var objEvento = $(this);

    $.ajax({
        type: "POST",
        dataType: "json",
        data: {tipo_acto : objEvento.val()},
        url: base_url+"index.php/liquidaciones/extraerSubtiposActo",
        success: function(data) {

                /*
                * Se vacia el select de subtipos de acto
                */
                $('#subTipoActo option:gt(0)').remove();

                /*
                * Construye los option con los subtipos de acto
                * recibidos
                */
                var opcionesSelect = '';
                $.map(data, function (nombre, identificador)
                    {
                        opcionesSelect += '<option value="'+ identificador +'">'+ nombre +'</option>';
                    });

                $('#subTipoActo').append(opcionesSelect).trigger('chosen:updated');
               }
             });
}

//Funcion que valida si se ha digitado
//una fecha para generar el informe
//detallado y envia la orden
function generarInformeDetallado (e)
{
    e.preventDefault();  

    var fecha = $('#buscarfecha').find(':text').val();
    var tipoEst = $('#tipoEst').val();

    if(fecha != '')
    {
        /*
        * Valida cual boton generó el evento si pdf o excel
        * para redireccionar respectivamente
        */
        var tipoInforme = $(this).attr('documento');
        if(tipoInforme == 'pdf')
        {
            window.open(base_url+'index.php/liquidaciones/renderizarPDF?fecha_I='+fecha+'&est='+tipoEst);
        }else if(tipoInforme == 'excel')
            {
                window.open(base_url+'index.php/liquidaciones/renderizarExcel?fecha_I='+fecha+'&est='+tipoEst);
            }
    }    
}


//Funcion que valida si se ha digitado
//una fecha para generar el informe
//detallado y envia la orden
function generarInformeRelacion (e)
{
    e.preventDefault();  

    var fecha = $('#buscarfecha').find(':text').val();
    
    if(fecha != '')
    {
        window.open(base_url+'index.php/liquidaciones/renderizarRelacionEstampillasPDF?fecha='+fecha);  
    }    
    
}

 //función que realiza el autocompletar
 //para el nombre del encargado de la papelería

function solicitarUsuarios (e) {
		
	var fragmento=$(this).val();

	$.ajax({
               type: "POST",
               dataType: "json",
               data: {piece : fragmento},
               url: base_url+"index.php/users/extraerUsuarios",
               success: function(data) {

    			     var fuenteBusqueda = [];
    			     for (var i = 0; i < data.nombre.length; i++) 
    			     {
    			     	fuenteBusqueda[i]=data.nombre[i];
    			     	usuariosActual['nombre'][i]=data.nombre[i];
    			     	usuariosActual['id'][i]=data.idd[i];
    			     }
    			     
    			     $( ".responsable" ).autocomplete({
    				  source: fuenteBusqueda
    				 });   			    
               }
             });
}


//Funcion que establece el numero de documento
//del usuario al que se le asignarán la papeleria
//y tambien a quien se reasignará
function cargarId (e) 
{

	 var valorActual=$(this).val();
     var documento;

     for (var i = 0; i < usuariosActual['nombre'].length; i++) 
     {  
    	 if(valorActual==usuariosActual['nombre'][i])
    	 {
    		 documento=usuariosActual['id'][i];
    	 }

     }

     var parent = $(this).attr('id');
     switch(parent)
     {
          case 'responsable' : $('#docuResponsable').val(documento);
              break;

          case 'oldResponsable' : $('#docuOldResponsable').val(documento);
                                  //solicita la extracción de los codigos
                                  //de papeleria asignada al liquidador
                                  //que entrega
                                  $.ajax({
                                      type: "POST",
                                      dataType: "json",
                                      data: {idLiquidador : documento},
                                      url: base_url+"index.php/papeles/extraerPapeleriaAsignada",
                                      success: function(data) {

                                      if(jQuery.isEmptyObject(data))
                                      {    
                                           //valida si hay papeleria para reasignar
                                           //si no bloquea los otros campos
                                           $('#newResponsable').val('').attr('disabled','disabled');
                                           $('#codigofinal').val('').attr('disabled','disabled');                                          
                                           $('#observaciones').val('').attr('disabled','disabled');
                                           $('#docuNewResponsable').val('');
                                           $('#btn_save').attr('disabled','disabled');
                                           
                                           $('#err').html('<div class="alert alert-dismissable alert-danger">'
                                               +'<button type="button" class="close" data-dismiss="alert"'
                                               +'aria-hidden="true">×</button>El liquidador no tiene'
                                               +' papelería para reasignar</div>');
                                           $('#err').show(300);  

                                      }else if(data.hasOwnProperty('varios'))
                                          {    
                                               //valida si hay papeleria para reasignar
                                               //si no bloquea los otros campos
                                               $('#newResponsable').val('').attr('disabled','disabled');
                                               $('#codigofinal').val('').attr('disabled','disabled');                                         
                                               $('#observaciones').val('').attr('disabled','disabled');
                                               $('#docuNewResponsable').val('');
                                               $('#btn_save').attr('disabled','disabled'); 

                                               var cadenaRangos='';

                                               for (var i = 0; i < data.limiteInferior.length; i++) 
                                               {    
                                                    if(data.limiteInferior[i] < data.limiteSuperior[i])
                                                    {
                                                        cadenaRangos += '<option value="'+data.limiteInferior[i]
                                                            +'-'+data.limiteSuperior[i]+'-'+data.idRango[i]+'">'
                                                            +data.limiteInferior[i]+'-'+data.limiteSuperior[i]+'</option>';     
                                                    }else
                                                        {
                                                             cadenaRangos += '<option value="'+data.limiteInferior[i]
                                                            +'-'+data.limiteSuperior[i]+'-'+data.idRango[i]+'">'
                                                            +data.limiteInferior[i]+'</option>';   
                                                        }
                                                    
                                               }
                                               

                                               $('#rangos').html('Elija uno de los Rangos disponibles para reasignación'
                                                    +'<br>'
                                                    +'<select id="rangoAsignacion" class="form-control" required>'                                                    
                                                    +cadenaRangos
                                                    +'</select>');                                               
                                               $('#myModal').modal('show');
                                               

                                          }else
                                              {
                                                //valida si hay papeleria para reasignar,
                                                //si hay, desbloquea los otros campos
                                                $('#newResponsable').removeAttr('disabled');
                                                $('#codigofinal').removeAttr('disabled');  
                                                $('#codigofinal').removeAttr('readonly');                                                                                         
                                                $('#observaciones').removeAttr('disabled');
                                                $('#btn_save').removeAttr('disabled');
                                                $('#err').hide();

                                                //Valida si los limites son el mismo valor
                                                //para solo llenar el codigo inicial
                                                if(data.limiteInferior < data.limiteSuperior)
                                                {
                                                    $('#codigoinicial').val(data.limiteInferior);
                                                    $('#codigofinal').val(data.limiteSuperior);                                                    

                                                }else
                                                    {
                                                        $('#codigoinicial').val(data.limiteInferior);
                                                        $('#codigofinal').val(data.limiteSuperior).attr('readonly','readonly');                                                        
                                                    }
                                                //registra el ultimo valor del rango asignable
                                                //para validación y establece el id del rango
                                                //para el backend   
                                                $('#ultimo').val(data.limiteSuperior);
                                                $('#idRango').val(data.idRango);

                                                var cantidad = parseInt(data.limiteSuperior)-parseInt(data.limiteInferior);
                                                $('#cantidad').val(cantidad+1);
                                              }  
                                      
                                      }
                                  });

              break;

          case 'newResponsable' : $('#docuNewResponsable').val(documento);
              break;
     }
     
}


//función que realiza la conexión asincronica
//para la relación de codigos de la papelería

function solicitarCodigos (e) {
	
	var base_url=$('#base').val();
	var codigoPapel=$(this).val();

	$.ajax({
               type: "POST",
               dataType: "json",
               data: {codPaper : codigoPapel},
               url: base_url+"index.php/users/extraerUsuarios",
               success: function(data) {

    			     var fuenteBusqueda = [];
    			     for (var i = 0; i < data.nombre.length; i++) 
    			     {
    			     	fuenteBusqueda[i]=data.nombre[i];
    			     	usuariosActual['nombre'][i]=data.nombre[i];
    			     	usuariosActual['id'][i]=data.idd[i];
    			     }
    			     
    			     $( "#responsable" ).autocomplete({
    				  source: fuenteBusqueda
    				 });   			    
               }
             });
	
}

//Función de apoyo que llena los campos de la interfaz
//de re-asignación de papeleria luego de elegido
//un rango
function establecerDatosElegidos (e) 
{   
    $('#newResponsable').removeAttr('disabled');
    $('#codigofinal').removeAttr('disabled');   
    $('#codigofinal').removeAttr('readonly');                                            
    $('#observaciones').removeAttr('disabled');
    $('#btn_save').removeAttr('disabled');
    $('#err').hide();

    //Divide el rango para asignarlo a los campos
    //en la vista
    var rango = $('#rangoAsignacion').val();
    var limites = rango.split('-');


    //Valida si los limites son el mismo valor
    //para inabilitar el codigo final
    if(parseInt(limites[0]) < parseInt(limites[1]))
    {
        $('#codigoinicial').val(limites[0]);
        $('#codigofinal').val(limites[1]);  

        var cantidad = parseInt(limites[1])-parseInt(limites[0]);
        $('#cantidad').val(parseInt(cantidad)+1);

        //registra el ultimo valor del rango asignable
        //para validación y establece el id del rango
        //para el backend   
        $('#ultimo').val(limites[1]);
        $('#idRango').val(limites[2]);   
        
    }else
        {
            $('#codigoinicial').val(limites[0]);
            $('#codigofinal').val(limites[1]).attr('readonly','readonly');
            $('#cantidad').val('1');

            //registra el ultimo valor del rango asignable
            //para validación y establece el id del rango
            //para el backend   
            $('#ultimo').val(limites[1]);
            $('#idRango').val(limites[2]);
        }
}

/*
* Funcion de apoyo que renderiza una notificacion
*/
function renderizarNotificacion(idContenedor,mensaje, tipo, animacion)
{
    var notificacion = '<div class="alert '+tipo+'" role="alert">'
        +'<button type="button" class="close" data-dismiss="alert">'
        +'<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'        
        +'<strong>Atencion!</strong> '+mensaje
        +'</div>';
    $('#'+idContenedor).empty();
    $('#'+idContenedor).html(notificacion);
    $('#'+idContenedor).hide();
    $('#'+idContenedor).slideDown(animacion);
}   

/*

 target="_blank" name="formCrearUsuario"

let rellenarFormulario = () => {

    const tiposUsuarios = {
        'liquidador': 4,
        'revisor_fiscal': 9,
        'representante_legal': 9,
    };

    const usuarios = [
        {
            'empresa': 'LOTERIA DE BOYACA				',
            'nit':'	891.801.039-7				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'otolosa@loteriadeboyaca.gov.co	Oscar Orlando	Tolosa Tolosa	  4.050.716		calle   19   9    35 piso 3',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'contabilidad@loteriadeboyaca.gov.co	JORGE ENRIQUE	SOTELO PAEZ	7.124.578		CALLE 19   9   35  PISO 3',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'pavila@loteriadeboyaca.gov.co	ANGELA PATRICIA	AVILA HAMON	24.053.188		cll  19   9   35  piso 3',
                }
            ]
        },
        {
            'empresa': 'EMPRESA DE ENERGIA DE BOYACA S.A. ESP				',
            'nit':'	891.800.219-1				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'impuestos@ebsa.com.co	DIRECCION	IMPUESTOS	891800219-1	87405000	CR 10 No: 15-87',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'Camila.A.Giraldo.Fresneda@co.ey.com	CAMILA ANDREA	GIRALDO FRESNEDA	1.018.492.259	4847000	CR 11 No.98-07',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'gerencia@ebsa.com.co	ROOSEVELT	MESA MARTINEZ	7.214.951	87405000	CR 10 No: 15-87',
                }
            ]
        },
        {
            'empresa': 'ITBOY				',
            'nit':'222',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'atorres.tesoreria@itboy.gov.co	ANGIE	TORRES	406458425		Av. Los Muiscas #72-43, Tunja, Boyacá',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'contabilidad@itboy.gov.co	 Mayerly Liliana	Guerra Guayacundo	1.049.626.919		calle 46 2a 18 Tunja',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'gerencia1@itboy.gov.co	Nathaly Lorena	Grosso Cepeda	33.377.902		Av. Los Muiscas #72-43, Tunja, Boyacá',
                }
            ]
        },
        {
            'empresa': 'EMPRESA DE ENERGIA DE BOYACA S.A. ESP				',
            'nit':'	891.800.219-1				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'impuestos@ebsa.com.co	DIRECCION	IMPUESTOS	891800219-1	87405000	CR 10 No: 15-87',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'Camila.A.Giraldo.Fresneda@co.ey.com	CAMILA ANDREA	GIRALDO FRESNEDA	1.018.492.259	4847000	CR 11 No.98-07',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'gerencia@ebsa.com.co	ROOSEVELT	MESA MARTINEZ	7.214.951	87405000	CR 10 No: 15-87',
                }
            ]
        },
        {
            'empresa': 'Nueva Licorera de Boyacá				',
            'nit':'	901.336.631-9				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'tesoreria@nlb.com.co	Leidy Paola	Monguí Perez	 1.057.580.531 	3203714468	Cra 9 N. 8bis 07',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'contabilidad@nlb.com.co	Lizeth Cecilia	Casas Garcia	 52.199.641 	3112127220	Cra 9a N. 66-11',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'gerencia@nlb.com.co	Sergio Armando	Tolosa Acevedo	 7.160.778 	3156037767	Cra 1B N. 48C -10',
                }
            ]
        },
        {
            'empresa': 'ADMINISTRACION MUNICIPAL DE GUACAMAYAS AGUACAMAYAS-APC				',
            'nit':'	900.282.153-2				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'aguacamayasapc@gmail.com	AGUACAMAYAS	APC	NIT 900.282.153-2	3143653274	CARRERA 5 #3-35 GUACAMAYAS BOYACA',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'carol_0612@hotmail.es	ELKIN	REINALDO BERMUDEZ	1.016.018.469	3124068954	CARRERA 5 #3-35 GUACAMAYAS BOYACA',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'java998@hotmail.com	CARLOS	JAVIER ESLAVA	C.C.79.872.952	3143653274	CARRERA 5 #3-35 GUACAMAYAS BOYACA',
                }
            ]
        },
        {
            'empresa': 'INSTITUTO DEPARTAMENTAL DEL DEPORTE DE BOYACA. INDEPORTES BOYACA				',
            'nit':'	820000919-8				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'tesoreroindeportes@gmail.com	JENNY PAOLA	VARGAS VALBUENA	1.057´892.314	3102850713	Avenida villa olimpica Tunja',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'contadora@indeportesboyaca.gov.co	CLEMENCIA	MORENO DIAZ	24.176.161	3112068883	Avenida villa olimpica Tunja',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'gerencia@indeportesboyaca.gov.co	LUIS ALBERTO	NEIRA SANCHEZ	4.234.754	3125189226	Avenida villa olimpica Tunja',
                }
            ]
        },
        {
            'empresa': 'EMPRESA DE SERVICIOS PUBLICOS DOMICILIARIOS DE LA PROVINCIA DE LENGUPA SERVILENGUPA SA ESP - Miraflores				',
            'nit':'	900325136-3				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'servilengupasaesp@gmail.com	OLGA MILENA	SUAREZ CASTILLO	23755710	3123234408	CALLE 4 · 12-16 Miraflores',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'contafin81@yahoo.com	PEDRO JULIO	BONILLA	6768851	3203406417	carrera 7 N° 22ª-07 Tunja',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'elmiradorhl@gmail.com	GUILLERMO HERNAN	LOPEZ RODRIGUEZ	74346840	3138903077	carrera 7 N° 6-38 Miraflores',
                }
            ]
        },
        {
            'empresa': 'ALIANZA SOCIETARIA Y DE DESARROLLO EMPRESARIAL DE BOYACA SAS , ASDETBOY				',
            'nit':'	901285377-2				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'Yohana.asdetboy@gmail.com	LISBETH YOHANA	ALBA CAMARGO	 1.053.611.559 	3134754728	CRA 33 #24-22 Paipa boyaca',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'contabilidad.asdetboy@gmail.com	PAULA ANDREA	SUAREZ JIMENEZ	 1.056.075.429 	3118314171	CL 19 # 9-35 OF 01 piso 10',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'gerenciaasdetboysas@gmail.com	ALBEIRO	HIGUERA GUARIN	 74.301.757 	3102772887	CL 19 # 9-35 OF 01 piso 10',
                }
            ]
        },
        {
            'empresa': 'EMPRESA DE SERVICIOS PUBLICOS DOMICILIARIOS DE LA PROVINCIA DE MARQUEZ - SERVIMARQUEZ S.A. E.S.P. ,CIENEGA				',
            'nit':'	900.371.611-6				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'servimarquezsaesptesoreria@gmail.com	YULIETH VILLALDINA	BOHORQUEZ BOHORQUEZ	1049641040	3202557427	Cra 10 3 25 Ciénega Boyacá',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'jaisonguerra92@hotmail.com	JAISON DAVID	GUERRA BARAJAS	 1.050.693.087 	3114724229	Cra 3 N° 2a - 04 Ciénega Boyacá',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'servimarquezsaesp@gmail.com	JULIO CESAR	CASTELBLANCO CARDENAS	 1.030.529.674 	3102968082	Cra 10 64b 79 Tunja Boyacá',
                }
            ]
        },
        {
            'empresa': 'Empresa de Servicios Públicos de Macanal MANANTIAL S.A. E.S.P.				',
            'nit':'	900.275.530-7				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'contactenos@espmanantialsa-macanal-boyaca.gov.co	Francy	Osorio	32.242.553	3118176621	Calle 4  N. 4 - 59',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'claje13@hotmail.com	Yeny	Niño	46.450.524	3107803435	Calle 17 N. 15 - 74',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'espmanantialsa@gmail.com	Alejandra	López	1.010.204.968	3213080548	Calle 11 N. 12 - 40',
                }
            ]
        },
        {
            'empresa': 'EMPRESA DE SERVICIOS PUBLICOS DE BUENAVISTA S.A. E.S.P.				',
            'nit':'	900327645 - 1				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'buenservicio.sa.esp@gmail.com	ALEXANDER PARRA	CAMACHO	1051185108	3202391519	CL 4 4 - 44',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'gustavozh@hotmail.com	GUSTAVO ADOLFO	ZAMBRANO HERNANDEZ	7308326	3144421165	CR 7 27 - 40 AP 401',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'y_alex_parra@hotmail.com	YILVER ALEXANDER	PARRA CAMACHO	1051185108	3202391519	CL 4 4 - 44',
                }
            ]
        },
        {
            'empresa': 'ADMINISTRACION PUBLICA COOPERATIVA EMPRESA SOLIDARIA DE SERVICIOS PUBLICOS DE GUAYATA				',
            'nit':'	820005134-6				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'juan.pinerosb@gmail.com	Carolina	Ramirez	1049798745	3203230358	cll 7 3 05',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'jc.impucontables@gmail.com	Juan Carlos	Piñeros Bonilla	1051336148	3112122335	cra 7 27 100',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'emsoguayata@hotmail.com	Luis Ariel	Toloza Bohorquez	1051336119	3115752748	cll 7 3 05',
                }
            ]
        },
        {
            'empresa': 'EMPRESA SOLIDARIA DE SERVICIOS PUBLICOS DE CHINAVITA E.S.P. 				',
            'nit':'	830508349-8				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'emsochinavita_esp@yahoo.com	LIDA ISABEL	BOHÓRQUEZ PEDREROS	23474755	3204357414	CARRERA 4 N° 5-30',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'siscoficina@gmail.com	SANDRA ISABEL	SALAZAR CASTILLO	46367329	3158913980	CARRERA 11 9-77 OF 204',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'empresaemsochinavitaesp@gmailcom	LIDA ISABEL	BOHÓRQUEZ PEDREROS	23474755	3204357414	CARRERA 4 N° 5-30',
                }
            ]
        },
        {
            'empresa': 'Empresa de Servicios Publicos de Togui "aguas de Togui S.A ESP"				',
            'nit':'	900333134-2				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'half04@hotmail.es	Hans Alexander	Leuro Fajardo	74446354	3118476565	calle 3 Nº 3-33 Togui - Boyaca',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'livardo@misena.edu.co	Libardo	Vargas Duarte 	91016377	3045945638	Cra 12 N° 11ª 41 Barbosa - Santander',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'aguasdetoguiesp@togui-boyaca.gov.co	Hans Alexander	Leuro Fajardo	74446354	3118476565	calle 3 Nº 3-33 Togui - Boyaca',
                }
            ]
        },
        {
            'empresa': 'GESTION ENERGETICA SA ESP , GENSA				',
            'nit':'	800.194.208-9				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'marina.cardenas@gensa.com.co	Luz Marina	Cardenas Gil	46453586	3148909494	Paipa: Kilometro 3 vía Paipa - Tunja Central',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'katherine.munoz@crowe.com.co	Katherine	Muñoz Cardona	1053793749	3137212246	Carrera 23 C # 62-06 Manizales',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'andrea.murillo@gensa.com.co 	Juan Guillermo	Correa Garcia	75102272	3146755594	 Carrera 23 N° 64B - 33 Edificio Centro de Negocios Siglo XXI, Torre Gensa.',
                }
            ]
        },
        {
            'empresa': 'EMPRESA SOLIDARIA DE SERVICIOS PUBLICOS DEL MUNICIPIO DE EL COCUY "EMSOCOCUY E.S.P"				',
            'nit':'	900259416-8				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'emsococuy@yahoo.es	ANGELA TERESA	MENA LEAL	52.704.043	3125954907	CL 8 4 15 PISO 1 CASA DE LA CULTURA- EL COCUY',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'hbasesoriasfinancieras@gmail.com 	MIGUEL ANGEL	BUITRAGO PEREZ	91.531.320	3005959017	CL 2 N 3 70',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'angela_mena@hotmail.com	ANGELA TERESA	MENA LEAL	52.704.043	3132103180	k 6 6-63',
                }
            ]
        },
        {
            'empresa': 'EMPRESA DEPARTAMENTAL DE SERVICIOS PUBLICOS DE BOYACA S.A. E.S.P				',
            'nit':'	900.297.725-0				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
					'info': 'yadiplazas@yahoo.com	JULIAN  ANTONIO	PIÑA CAMARGO	1.049.603.074	3142472936	CARRERA 11 N 20-54- Tunja',
				},
				{
                    'tipo': 'revisor_fiscal',
					'info': 'astridplazasruiz@hotmail.com	ASTRID LUNEY	PLAZAS RUIZ	46.380.427	3208094334	CARRERA 11 N 20-54- Tunja',
				},
				{
                    'tipo': 'representante_legal',
					'info': 'gerencia.financiera@espb.gov.co	LEONARDO ANDRES	PLAZAS VERGEL	74.080.141	3142472936	CARRERA 11 N 20-54- Tunja',
				}
            ]
        },
        {
            'empresa': 'COMPAÑÍA DE SERVICIOS PUBLICOS DE SOGAMOSO S.A. E.S.P.				',
            'nit':'	891800031-4				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'contabilidad.financiera@coserviciosesp.com.co	Betty Esperanza	Rodriguez Africano	46357267	3212472220	Carrera 11 15 10 12',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'revisoria.fiscal@coserviciosesp.com.co	Maria Lisbeth	Palencia Montaña	46359845	3103362673	Carrera 11 15 10 12',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'gerencia@coserviciosesp.com.co	Pedro Elias	Barrera Mesa	9533560	3212035371	Carrera 11 15 10 12',
                }
            ]
        },
        {
            'empresa': 'EMPRESAS PUBLICAS DE GARAGOA S.A E.S.P.				',
            'nit':'	9000220341				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'contacto@empresaspublicasdegaragoa-boyaca.gov.co	Deisy Yomara	Leguízamo Parra	40049339	3103284112	Trv 8 No. 6-115 Apto 302',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'a.solutions.nm@gmail.com	Nidia Yamile	Matamoros Herrera	33677629	3133696137	DG 82G 73 A 61 INT 7 APTO 213',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'gerencia@empresaspublicasdegaragoa-boyaca.gov.co	Josue Ricardo	Fernandez Buitrago	7334054	3107623778	Carrera 10 N° 8-37',
                }
            ]
        },
        {
            'empresa': 'Administracion Publica Cooperativa Empresa Solidaria  de Servicios Publicos de Tinjaca E.S.P.				',
            'nit':'	900.335.211-0				',
            'usuarios': [
                {
                    'tipo': 'liquidador',
                    'info': 'aquatinjacaesp2012@gmail.com	Administracion Publica Cooperativa	Empresa Solidaria  de Servicios Publicos de Tinjaca E.S.P.	900.335.211-0	3116276642	calle 4 No. 2-72 palacio Municipal Tinjaca',
                },
                {
                    'tipo': 'revisor_fiscal',
                    'info': 'nicolasavila84@gmail.com 	Nicolas Avila Parra	7.185.612	3004845290	Avenida	Universitaria 29-92 apartamento 105 Tunja',
                },
                {
                    'tipo': 'representante_legal',
                    'info': 'javedsalinas@gmail.com	Javed Angel	Salinas Rozo	4.278.064	3118047274	Carrera 4 # 33-04 Chiquinquira',
                }
            ]
        }
    ];

    const contratantes = [
        {
            'id': 8,
            'nit': 800194208
        },
        {
            'id': 9,
            'nit': 900277171
        },
        {
            'id': 10,
            'nit': 900267080
        },
        {
            'id': 11,
            'nit': 900283400
        },
        {
            'id': 12,
            'nit': 900022034
        },
        {
            'id': 13,
            'nit': 900259416
        },
        {
            'id': 14,
            'nit': 830508349
        },
        {
            'id': 15,
            'nit': 891800219
        },
        {
            'id': 16,
            'nit': 900331439
        },
        {
            'id': 17,
            'nit': 900333134
        },
        {
            'id': 18,
            'nit': 826003618
        },
        {
            'id': 19,
            'nit': 900014648
        },
        {
            'id': 20,
            'nit': 900371611
        },
        {
            'id': 21,
            'nit': 900263342
        },
        {
            'id': 22,
            'nit': 820000671
        },
        {
            'id': 23,
            'nit': 830045472
        },
        {
            'id': 24,
            'nit': 891800031
        },
        {
            'id': 25,
            'nit': 820001405
        },
        {
            'id': 26,
            'nit': 900303661
        },
        {
            'id': 27,
            'nit': 900159283
        },
        {
            'id': 28,
            'nit': 900194394
        },
        {
            'id': 29,
            'nit': 820005134
        },
        {
            'id': 30,
            'nit': 900297725
        },
        {
            'id': 31,
            'nit': 900275530
        },
        {
            'id': 32,
            'nit': 900265657
        },
        {
            'id': 33,
            'nit': 900282153
        },
        {
            'id': 34,
            'nit': 900258898
        },
        {
            'id': 35,
            'nit': 900335211
        },
        {
            'id': 36,
            'nit': 900325136
        },
        {
            'id': 37,
            'nit': 900327645
        },
        {
            'id': 38,
            'nit': 891801069
        },
        {
            'id': 39,
            'nit': 820000919
        },
        {
            'id': 40,
            'nit': 901336631
        },
        {
            'id': 41,
            'nit': 891801039
        },
        {
            'id': 42,
            'nit': 891801333
        },
        {
            'id': 43,
            'nit': 901285377
        },
        {
            'id': 44,
            'nit': 891800462
        },
        {
            'id': 45,
            'nit': 901154852
        },
    ]

	let index = 0;

    usuarios.forEach(empresa => {
        let nitEmpresa = empresa.nit.trim().replaceAll('.', '')
        nitEmpresa = nitEmpresa.split('-')[0]

        empresa.usuarios.forEach(usuario => {
			setTimeout(() => {
				console.log('usuario', usuario);
				let info = usuario.info.split('\t')

				document.getElementById('email').value = info[0].trim()
				document.getElementById('nombres').value = info[1].trim()
				document.getElementById('apellidos').value = info[2].trim()
				document.getElementById('id').value = info[3].replaceAll('.', '').trim()
				document.getElementById('telefono').value = (info[4].trim() != '' ? info[4].trim() : '3000000001')
				document.getElementById('password').value = nitEmpresa
				document.getElementById('password_confirm').value = nitEmpresa

				let contratante = contratantes.find(c => c.nit == nitEmpresa);

				document.getElementById('perfilid').value = tiposUsuarios[usuario.tipo];
				document.getElementById('empresa').value = contratante ? contratante.id : null;

				if(contratante) {
					document.forms["formCrearUsuario"].submit();
					console.log('confirmado', info[0].trim(), nitEmpresa);
				} else {
					console.log('rechazado', info[0].trim());
				}
			}, index * 5000);

			index++
        })
    })
}

*/