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
    $('#btn-consultar').click(generarInformeRango);
    $('#btn-consultar-detalle-pdf').click(generarInformeRangoDetalle);
    $('#btn-consultar-detalle-excel').click(generarInformeRangoDetalle);

    /*
    * Evento para solicitar recargar el formulario de adicionar papelería
    * con o sin la variable de contingencia
    */
    $('#chk_contingencia').click(solicitarRecargaPagina);

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

/*
* Funcion que solicita al servidor la validacion del tipo de regimen
* del contratista seleccionado
*/
function validarRegimenContratista(e)
{
    var idContratista = $(this).val();

    if(idContratista != '0')
    {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {idContratista : idContratista},
            url: base_url+"index.php/contratos/validarRegimen",
            success: function(objResponse) {
                if(objResponse.msj == '')
                {
                    if(objResponse.es_otros == 'SI')
                    {
                        $('#cont_iva_otros').slideDown(400);
                    }else
                        {
                            $('#cont_iva_otros').slideUp(400);
                        }
                }else
                    {
                        renderizarNotificacion('notificacion',objResponse.msj, 'alert-danger', 400);
                    }
            }
        });
    }
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

/*
* Funcion de apoyo que solicita la generacion del informe
* de impresiones por rango de fecha
*/
function generarInformeRangoDetalle(e)
{
    var fecha_inicial = $('#m_rango').find('[name="f_inicial"]').val();
    var fecha_final = $('#m_rango').find('[name="f_final"]').val();
    var tipoEst = $('#tipoEst').val();
    
    /*
    * Se valida numericamente que las fechas tengan valor
    * distinto de vacio
    */
    if(fecha_inicial != '')
    {
        var fe_i = 1;
    }else
        {
            var fe_i = 0;
        }
    
    if(fecha_final != '')
    {
        var fe_f = 1;
    }else
        {
            var fe_f = 0;
        }

    if((fe_i+fe_f) > 0)
    {
        /*
        * Valida cual boton generó el evento si pdf o excel
        * para redireccionar respectivamente
        */
        var tipoInforme = $(this).attr('documento');
        if(tipoInforme == 'pdf')
        {
            window.open(base_url+'index.php/liquidaciones/renderizarPDF?fecha_I='+fecha_inicial+'&fecha_F='+fecha_final+'&est='+tipoEst);
        }else if(tipoInforme == 'excel')
            {
                window.open(base_url+'index.php/liquidaciones/renderizarExcel?fecha_I='+fecha_inicial+'&fecha_F='+fecha_final+'&est='+tipoEst);
            }
    }
}

/*
* Funcion de apoyo que solicita la generacion del informe
* de impresiones por rango de fecha
*/
function generarInformeRango(e)
{
    var fecha_inicial = $('#m_rango').find('[name="f_inicial"]').val();
    var fecha_final = $('#m_rango').find('[name="f_final"]').val();
    
    /*
    * Se valida numericamente que las fechas tengan valor
    * distinto de vacio
    */
    if(fecha_inicial != '')
    {
        var fe_i = 1;
    }else
        {
            var fe_i = 0;
        }
    
    if(fecha_final != '')
    {
        var fe_f = 1;
    }else
        {
            var fe_f = 0;
        }

    if((fe_i+fe_f) > 0)
    {
        window.open(base_url+'index.php/liquidaciones/renderizarRangoImpresionesPDF?fecha_I='+fecha_inicial+'&fecha_F='+fecha_final);
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
                    { "sClass": "center","bSortable": false,"bSearchable": false},
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
                * Se dibuja el link para visualizar el soporte del contrato
                */
                $("td:eq(6)", nRow).html("<a href='"+ base_url + aData[7] +"' target='_blank'><img src='"+ base_url + aData[7] +"' class='file-preview-image' alt='comprobante de pago' title='comprobante de pago'  height='42' width='42'></a>");

            },
        "fnDrawCallback": function( oSettings ) 
            {
                $(".auto_num").autoNumeric('init',{aSep:'.',aDec:',',aSign:'$ '});
                // $(".liquidar").on('click', function(event) {
                     // event.preventDefault();
                     // var ID = $(this).attr("id");
                      // $("#idcontrato").val(ID);
                       // $('.liquida').load('<?php echo base_url(); ?>index.php/liquidaciones/liquidarcontrato/'+ID,function(result){
                        // $('#myModal').modal({show:true});
          // 
                       // Eventos liquidar contratos-temporal
                        // $('.calcular').blur(actualizarTotal); 
                        // function actualizarTotal(e)
                        // { 
                            // var total = 0;
          // 
                            // $('.calcular').map(function(){      
          // 
                                // if($(this).val()!='')
                                // {
                                    // total += parseInt($(this).val());   
                                // }else
                                    // {
                                        // total += 0;  
                                    // }                
                                // 
                            // });
          // 
                            // $('#valortotal').val(total);
                            // 
                        // }
          // 
                       // });
                   // });
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
            null,
            {    
                sSelector: "#buscarano", 
                type:"select" ,
                values : vecVig
            },
            null,
        ]
        };
    return filtros;
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
        $('#datetimepicker_inicial').datetimepicker({
            pickTime: false
        });
        $('#datetimepicker_final').datetimepicker({
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