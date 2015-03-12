<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            tiposcontratos
*   Ruta:              /application/views/tiposcontratos/tiposcontratos_list.php
*   Descripcion:       tabla que mustra todos los tipos de contratos existentes
*   Fecha Creacion:    22/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-22
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/tiposcontratos/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" },  
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
                      ],    
} );

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
 <div class="col-sm-12">    
  <h1>Tipos de contrato</h1>

  <?php
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcontratos/add')) {
        echo anchor(base_url().'tiposcontratos/add','<i class="fa fa-plus"></i> Nuevo tipo de contrato ','class="btn btn-large  btn-primary"');
      }
  ?>

   <br><br> 
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Nombre</th>
     <th>Descripción</th>
     <th></th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
</div>   
      </div>
