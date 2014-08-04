<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/logactividades/logactividades_list.php
*   Descripcion:       tabla que mustra todos los logactividades existentes
*   Fecha Creacion:    04/ago/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-08-04
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/logactividades/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" }, 
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item","sWidth":"20%" },  
                      { "sClass": "item","sWidth":"20%" },
                      { "sClass": "item" },
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
            ],   
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {


    $("td:eq(5)", nRow).html('<div>' + aData[5] + '</div>');

 }             
   });

    oTable.fnSearchHighlighting();
} );


</script>

<div class="row"> 
 <div class="col-sm-12">
 <h1>Log de actividades</h1>

   <br><br>
 </div>
</div> 


<div class="row"> 
 <div class="col-sm-12">    
   
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Acción</th>
     <th>Tabla</th>
     <th>Código </th>
     <th>Fecha</th>
     <th>Valores anteriores</th>
     <th>Valores nuevos</th>       
     <th>Usuario</th>
     <th>IP</th>
     <th></th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
</div>   
      </div>
