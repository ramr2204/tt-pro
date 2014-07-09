<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/papeles/papeles_list.php
*   Descripcion:       tabla que mustra todos los papeles existentes
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
"sAjaxSource": "<?php echo base_url(); ?>index.php/papeles/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" }, 
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "center","bSortable": false,"bSearchable": false},
                      ],    
"fnRowCallback":function( nRow, aData, iDataIndex ) {
            var restante=aData[1]-aData[2];
            $('td:eq(3)', nRow).html(restante);
          
         },                      
} );

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
 <div class="col-sm-12">    
  <h1>Inventario de papelería para impresión de estampillas</h1>

  <?php
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/add')) {
        echo anchor(base_url().'papeles/add','<i class="fa fa-plus"></i> Nuevo ingreso ','class="btn btn-large  btn-primary"');
      }
  ?>

   <br><br> 
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Cantidad inicial</th>
     <th>Cantidad utilizada</th>
     <th>Cantidad restante</th>
     <th>Fecha de ingreso</th>
     <th>observaciones</th>
     <th></th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
</div>   
      </div>
