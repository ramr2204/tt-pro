<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/impresiones/impresiones_list.php
*   Descripcion:       tabla que muestra todos los impresiones existentes
*   Fecha Creacion:    14/jul/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-07-14
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
"sAjaxSource": "<?php echo base_url(); ?>index.php/impresiones/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "center" },  
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "item" },
                      { "sClass": "center" }, 

                    
                      ],    
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

  if (aData[5]==1) {
   $("td:eq(5)", nRow).html('<i class="fa fa-check-square-o" style="color:green"></i>');
  } else  {
   $("td:eq(5)", nRow).html('<i class="fa fa-times-circle-o" style="color:red"></i>');
  }

 },                      
} );

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
 <div class="col-sm-12">    
  <h1>Impresiones</h1>

  <?php
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('impresiones/add')) {
        echo anchor(base_url().'impresiones/add','<i class="fa fa-chain-broken"></i> Reportar papel dañado o anulado ','class="btn btn-large  btn-danger"');
      }
  ?>

   <br><br> 
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Código del papel</th>
     <th>Fecha</th>
     <th>Estampilla asociada</th>
     <th>Observaciones</th>
     <th>estado</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
</div>   
      </div>
