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
    base_url=$('#base').val();

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
    $('#btn-consultar-detalle').click(generarInformeRangoDetalle);
 
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
        window.open(base_url+'index.php/liquidaciones/renderizarPDF?fecha_I='+fecha_inicial+'&fecha_F='+fecha_final);
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
    * ordenanzas
    */
    var o = 0;
    $('body').find('#btn-ordenanzasAdd').each(function()
        {
            o++;     
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

    if(o > 0)
    {
        //Evento para el timepicker del cargue de ordenanzas
        $('#datetimepicker_fechaOrdenanza').datetimepicker({
            pickTime: false
        });
        $('#datetimepicker_inicioOrdenanza').datetimepicker({
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



   
