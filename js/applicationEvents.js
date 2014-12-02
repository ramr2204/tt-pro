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
                                                        cadenaRangos += '<option value="'+data.limiteInferior[i]+'-'+data.limiteSuperior[i]+'">'
                                                            +data.limiteInferior[i]+'-'+data.limiteSuperior[i]+'</option>';     
                                                    }else
                                                        {
                                                             cadenaRangos += '<option value="'+data.limiteInferior[i]+'">'
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
                                                        $('#codigofinal').val('').attr('disabled','disabled');                                                        
                                                    }
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
    $('#observaciones').removeAttr('disabled');
    $('#btn_save').removeAttr('disabled');
    $('#err').hide();

    //Divide el rango para asignarlo a los campos
    //en la vista
    var rango = $('#rangoAsignacion').val();
    var limites = rango.split('-');


    //Valida si los limites son el mismo valor
    //para solo llenar el codigo inicial
    if(parseInt(limites[0]) < parseInt(limites[1]))
    {
        $('#codigoinicial').val(limites[0]);
        $('#codigofinal').val(limites[1]);  
    }else
        {
            $('#codigoinicial').val(limites[0]);
            $('#codigofinal').val('').attr('disabled','disabled');
        }
    
    var cantidad = parseInt(limites[1])-parseInt(limites[0]);
    $('#cantidad').val(parseInt(cantidad)+1);
}



   
