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


function inicial () 
{
	$('#responsable').keyup(solicitarUsuarios);
}



 //función que realiza el autocompletar
 //para el nombre del encargado de la papelería

function solicitarUsuarios () {
	
	var base_url=$('#base').val();
	var fragmento=$('#responsable').val();

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
    			     }
    			     
    			     $( "#responsable" ).autocomplete({
    				  source: fuenteBusqueda
    				 });
               }
             });
	
}