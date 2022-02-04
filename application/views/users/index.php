<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


<script type="text/javascript" language="javascript" charset="utf-8">

  tipos_usuarios = JSON.parse('<?= json_encode($tipos_usuarios) ?>');

  //generación de la tabla mediante json
  $(document).ready(function() {

    var oTable = $('#tablaq').dataTable( {
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": "<?php echo base_url(); ?>index.php/users/dataTable",
      "sServerMethod": "POST",
      "aoColumns": [
        { "sClass": "center","sWidth": "5%" }, /*id 0*/
        { "sClass": "item" },
        { "sClass": "item" },
        { "sClass": "item" },
        { "sClass": "item","bSortable": false,"bSearchable": false },
        { "sClass": "item","sWidth": "5%" }, 
        { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "5%" },
      ],
      "fnRowCallback":function( nRow, aData, iDataIndex ) {
        if ( aData[5] ==1 )
        {
          $('td:eq(5)', nRow).html( '<a href="<?php echo base_url(); ?>users/deactivate/'+aData[0]+'" class="btn btn-default btn-xs" title="Desactivar"><i class="fa fa-unlock" style="color:green"></i> </a>' );  
        }else
        {
          $('td:eq(5)', nRow).html( '<a href="<?php echo base_url(); ?>users/activate/'+aData[0]+'" class="btn btn-default btn-xs"" title="Activar"><i class="fa fa-lock" style="color:red"></i></a>' );  
        }

        $('td:eq(4)', nRow).html(tipos_usuarios[aData[4]])
      },
    } );

    oTable.fnSearchHighlighting();
  });
</script>


<div class="row"> 
 <div class="col-sm-12">    
  <h1><?php echo lang('index_heading');?></h1>

  <?php echo anchor(base_url().'users/create_user','<i class="fa fa-plus"></i> '. lang('index_create_user_link'),'class="btn btn-large  btn-primary"'); ?>
  <br><br>
<div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Identificación</th>
     <th>Email</th>
     <th>Empresa</th>
     <th>Perfil</th>
     <th>Tipo</th>
     <th>Estado</th>
     <th>Acciones</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>
      </div>   
      </div>











