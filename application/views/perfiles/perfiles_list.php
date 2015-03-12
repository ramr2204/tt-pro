<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/perfiles/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center","sWidth": "5%" }, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" },  
                      { "sClass": "center","bSortable": false,"bSearchable": false },

                    
                      ],    
} );

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
 <div class="col-sm-12">    
  <h1>Perfiles</h1>
  <div id="infoMessage"><?php if (isset($message)) { echo $message; } ?></div>
  <?php
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargos/add'))
      {
        echo anchor(base_url().'perfiles/add','<i class="fa fa-plus"></i> Nuevo perfil ','class="btn btn-large  btn-primary"');
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
