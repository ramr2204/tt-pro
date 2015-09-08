<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/items/items_list.php
*   Descripcion:       tabla que muestra todos los Items existentes
*   Fecha Creacion:    10/Ago/2015
*   @author           Mike Ortiz <engineermikeortiz@gmail.com>
*   @version          2015-08-10
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/items/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/                 
                      { "sClass": "item" },                      
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
                      ],    
} );

    oTable.fnSearchHighlighting();
} );
</script>
<style type="text/css">
	.btn-toolbar
	{
		margin-left: 40%;
	}
</style>
<div class="row"> 
 <div class="col-sm-12">    
  <h1>Items</h1>

    <?php
    if ($this->ion_auth->is_admin()) 
    {
        echo anchor(base_url().'items/add','<i class="fa fa-plus"></i> Nuevo Item ','class="btn btn-large  btn-primary"');
    }
  ?>

   <br><br> 
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Nombre</th>
     <th>Acciones</th>     
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
</div>   
      </div>
