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


function solicitarUsuarios () {
	
	var base_url=$('#base').val();
	var fragmento=$('#responsable').val();

	$.ajax({
               type: "POST",
               dataType: "html",
               data: {piece : fragmento},
               url: base_url+"index.php/papeles/extraerUsuarios",
               success: function(data) {
    			    alert(data);             
               }
             });

	
}