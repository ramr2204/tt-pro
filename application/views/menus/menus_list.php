<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/menus/menus_list.php
*   Descripcion:       tabla que mustra todos los menus existentes
*   Fecha Creacion:    12/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-12
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/menus/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center","sWidth": "5%" }, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" },  
                      { "sClass": "item" },  
                      { "sClass": "item" },  
                      { "sClass": "item" }, 
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "5%" },

                    
                      ],    
} );

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
 <div class="col-sm-12">    
  <h1>Menús</h1>
  <div id="infoMessage"><?php if (isset($message)) { echo $message; } ?></div>
  <?php
  if ($this->ion_auth->is_admin())
      {
        echo anchor(base_url().'menus/add','<i class="fa fa-plus"></i> Nuevo menú ','class="btn btn-large  btn-primary"');
      }
  ?>
   <br><br> 
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Nombre</th>
     <th>Módulo</th>
     <th>Descripción</th>
     <th>Ruta</th>
     <th>Estado</th>
     <th></th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
</div>   
      </div>
