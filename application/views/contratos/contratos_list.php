<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratos/contratos_list.php
*   Descripcion:       tabla que mustra todos los contratos existentes
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
"sAjaxSource": "<?php echo base_url(); ?>index.php/contratos/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" }, 
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },  
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
            ],   
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

if(aData[7] != null)
  {
      $("td:eq(7)", nRow).html('<div class="small">' + aData[7].substr( 0, 130 )+ '...</div>');
  }else
      {
           $("td:eq(7)", nRow).html('<div class="small">NO REGISTRA...</div>');     
      }

if(aData[4] == null)
  {
    $("td:eq(4)", nRow).html('<div class="small">NO REGISTRA...</div>');     
  }

if(aData[5] == null)
  {
    $("td:eq(5)", nRow).html('<div class="small">NO REGISTRA...</div>');     
  }

 }             
   });

    oTable.fnSearchHighlighting();
} );


</script>

<div class="row"> 
 <div class="col-sm-12">
 <h1>Contratos</h1>
<?php
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/add')) {
        echo anchor(base_url().'contratos/add','<i class="fa fa-plus"></i> Nuevo contrato ','class="btn btn-large  btn-primary"');
      }
  ?>
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
     <th>Número</th>
     <th>NIT Contratista</th>
     <th>Contratista</th>
     <th>NIT Contratante</th>
     <th>Contratante</th>
     <th>Fecha</th>
     <th>Objeto</th>
     <th>Valor</th>       
     <th>Vigencia</th>
     <th></th>
   </tr>
 </thead>
 <tbody></tbody>     
 <tfoot>
   <tr>
     <th>Id</th>
     <th>Número</th>
     <th>NIT Contratista</th>
     <th>Contratista</th>
     <th>NIT Contratante</th>
     <th>Contratante</th>
     <th>Fecha</th>
     <th>Objeto</th>
     <th>Valor</th>       
     <th>Vigencia</th>
     <th></th>
   </tr>
 </tfoot>
</table>
</div>
</div>   
      </div>
