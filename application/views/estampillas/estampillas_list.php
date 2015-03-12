<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/estampillas/estampillas_contratos.php
*   Descripcion:       
*   Fecha Creacion:    20/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-20
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/estampillas/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" }, 
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
  <h1>Estampillas pro</h1>

  <?php
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/add')) {
        echo anchor(base_url().'estampillas/add','<i class="fa fa-plus"></i> Nuevo estampilla ','class="btn btn-large  btn-primary"');
      }
  ?>

   <br><br> 
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Nombre</th>
     <th>Cuenta</th>
     <th>Banco</th>
     <th>Descripción</th>
     <th width="5%">Acciones</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
</div>   
      </div>
