<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
  $(document).ready(function() {
    var oTable = $('#tablaq').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/empresas/dataTable",
        "sServerMethod": "POST",
        "aoColumns": [ 
                        { "sClass": "center","sWidth": "5%" }, /*id 0*/
                        { "sClass": "center","sWidth": "5%" }, /*id 0*/
                        { "sClass": "item" },
                        { "sClass": "item" },
                        { "sClass": "item" },
                        { "sClass": "item" },
                        { "sClass": "item" },
                        { "sClass": "item"},
                        { "sClass": "item"},
                        { "sClass": "item"},
                        { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "5%" },
        ],
        "fnRowCallback":function( nRow, aData, iDataIndex ) {
            console.log(aData);
            if ( aData[9] ==1 )
            {
               $('td:eq(9)', nRow).html( '<a href="<?php echo base_url(); ?>empresas/delete/'+aData[0]+'/0" class="btn btn-default btn-xs" title="Desactivar"><i class="fa fa-unlock" style="color:green"></i> </a>' );
            }else
            {
                $('td:eq(9)', nRow).html( '<a href="<?php echo base_url(); ?>empresas/delete/'+aData[0]+'/1" class="btn btn-default btn-xs"" title="Activar"><i class="fa fa-lock" style="color:red"></i></a>' );
            }
        },                          

    } );

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
    <div class="col-sm-12">    
        <h1><?php echo lang('index_heading');?></h1>
        <?php echo anchor(base_url().'empresas/create','<i class="fa fa-plus"></i> '. lang('index_create_user_link'),'class="btn btn-large  btn-primary"'); ?>
        <br><br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nit</th>
                        <th>Nombre</th> 
                        <th>Email</th>  
                        <th>Dirección</th> 
                        <th>Telefono</th>
                        <th>Municipio</th>
                        <th>Representante</th>
                        <th>Id. Representante</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
 </div>










