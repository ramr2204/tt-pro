<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
 *   Nombre:            impresiones_list_anulaciones
 *   Ruta:              /application/views/impresiones/impresiones_list_anulaciones.php
 *   Descripcion:       tabla que muestra todos las anulaciones de papel existentes
 *   Fecha Creacion:    29/Ene/2019
 *   @author            Michael Angelo Ortiz <engineermikeortiz@gmail.com>
 *   @version           2019-01-29
 *
 */

?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {

"aaSorting": [[1, 'desc']], 
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/impresiones/dataTable_anulaciones",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "center" },  
                      { "sClass": "center" }, 
                      { "sClass": "item" },
                      { "sClass": "item" }, 
                      { "sClass": "center" },
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" },
                    
                      ],    
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

  if (aData[5]==1) {
   $("td:eq(5)", nRow).html('<i class="fa fa-check-square-o" style="color:green"></i>');
   $("td:eq(8)", nRow).html('');
  } else  {
   $("td:eq(5)", nRow).html('<i class="fa fa-times-circle-o" style="color:red"></i>');
   $("td:eq(8)", nRow).html('<a href="<?php echo base_url(); ?>index.php/impresiones/get_verificar_anulacion/'+ aData[0] +'" class="btn btn-danger btn-xs" title="Verificar Anulaci&oacute;n" ><i class="fa fa-file-excel-o"></i></a>');
  }

 },                      
} );

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
 <div class="col-sm-12">    
  <h1>Anulaciones Reportadas</h1>

   <br><br> 
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Código del papel</th>
     <th>Tipo de anulación</th>
     <th>Fecha Impresi&oacute;n</th>
     <th>Observaciones Anulaci&oacute;n</th>
     <th>Verificada</th>
     <th>Fecha Verificaci&oacute;n</th>
     <th>Observaciones Verificaci&oacute;n</th>
     <th>Acciones</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
</div>   
      </div>
