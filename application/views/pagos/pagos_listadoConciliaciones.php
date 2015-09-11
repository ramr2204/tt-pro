<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            pagos_listadoConciliaciones
*   Ruta:              /application/views/pagos/pagos_listadoConciliaciones.php
*   Descripcion:       tabla que mustra todos las conciliaciones realizadas
*   Fecha Creacion:    20/may/2014
*   @author           Mike Ortiz <michael.ortiz@turrisystem.com>
*   @version          2015-09-11
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/pagos/conciliacionesDataTable",
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
  <h1>Tipos de tipo banco</h1>

  <?php
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/add')) {
        echo anchor(base_url().'bancos/add','<i class="fa fa-plus"></i> Nuevo banco ','class="btn btn-large  btn-primary"');
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
