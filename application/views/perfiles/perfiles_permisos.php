
<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


<h1><?php echo $result->perf_nombre; ?> - Editar permisos predeterminados del perfil</h1>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        
                        <div class="col-md-12 column">
                       <div class="message"></div>
                                
                                <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover" id="tablaq">
                             <thead>
                                <tr>
                                 <th>Id</th>
                                 <th>Nombre</th>
                                 <th>Ruta</th>
                                 <th>Estado</th>
                                 
                            
                               </tr>
                             </thead>
                             <tbody></tbody>     
                            </table>
                            </div>

                        </div>
                  </div> 
            </div>
      </div>


<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/perfiles/permisos_datatable/<?php echo $result->perf_id; ?>",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center","sWidth": "5%" }, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" },  
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "5%" },

                    
                      ], 
    "fnRowCallback" :function( nRow, aData, iDataIndex ) {
          if ( aData[3] == null )
            {
               $('td:eq(3)', nRow).html( '<a id="'+aData[0]+'" href="#" class="btn btn-default btn-xs agrega"" title="Activar"><i class="fa fa-lock" style="color:red"></i></a>' );    
            }else
            {
               $('td:eq(3)', nRow).html( '<a id="'+aData[3]+'" href="#" class="btn btn-default btn-xs elimina" title="Desactivar"><i class="fa fa-unlock" style="color:green"></i> </a>' );
            }
         },
  //agrega a la tabla de predeterminados (PERMISOS_GRUPOS) mediante ajax    
  "fnDrawCallback": function( oSettings ) {
             $(".agrega").on('click', function(event) {
             event.preventDefault();
             var ID = $(this).attr("id");
             //alert('----'+ID);
              $(".message").append('<i class="fa fa-spinner fa-spin"></i>');
             $(".message").load("<?php echo base_url(); ?>index.php/perfiles/predeterminar", {id_menu: ID, id_perfil:<?php echo $result->perf_id; ?>}, function(){
                oTable.fnDraw();  
              });
    
         });
             $(".elimina").on('click', function(event) {
             event.preventDefault();
             var IDP = $(this).attr("id");
              $(".message").append('<i class="fa fa-spinner fa-spin"></i>');
              $(".message").load("<?php echo base_url(); ?>index.php/perfiles/despredeterminar", {id_permiso: IDP}, function(){
                oTable.fnDraw();
              });
    
         });
    },     

} );

    oTable.fnSearchHighlighting();
} );
</script>