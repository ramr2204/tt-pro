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
"sAjaxSource": "<?php echo base_url(); ?>index.php/liquidaciones/tramites_datatable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center","bVisible": false}, /*id 0*/
                      { "sClass": "center" }, 
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },  
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
            ],   
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

   
  if (aData[6]=='Legalizado') {
   $("td:eq(6)", nRow).html('<a href="#" class="btn btn-success btn-xs terminar" title="Cambiar estado" id="'+aData[0]+'"><i class="fa fa-tags"></i></a>');
  }
   if (aData[6]=='Liquidado') {
   $("td:eq(6)", nRow).html('<a href="#" class="btn btn-primary btn-xs pagar" title="Cambiar estado" id="'+aData[0]+'"><i class="fa fa-money"></i></a>');
  }
  if (aData[6]==null) {
   $("td:eq(6)", nRow).html('<a href="#" class="btn btn-danger btn-xs liquidar" title="Liquidar" id="'+aData[0]+'"><i class="fa fa-file-excel-o"></i></a>');
  }

 },
  "fnDrawCallback": function( oSettings ) {
      $(".liquidar").on('click', function(event) {
           event.preventDefault();
           var ID = $(this).attr("id");
            $("#idtramite").val(ID);
             $('.liquida').load('<?php echo base_url(); ?>index.php/liquidaciones/verliquidartramite/'+ID,function(result){
              $('#myModal').modal({show:true});
             });
         });
       $(".pagar").on('click', function(event) {
           event.preventDefault();
           var ID = $(this).attr("id");
            $("#idtramite").val(ID);
             $('.paga').load('<?php echo base_url(); ?>index.php/liquidaciones/vertramiteliquidado/'+ID,function(result){
              $('#myModal2').modal({show:true});
             });
         });
      $(".terminar").on('click', function(event) {
           event.preventDefault();
           var ID = $(this).attr("id");
            $("#idtramite").val(ID);
             $('.termina').load('<?php echo base_url(); ?>index.php/liquidaciones/vertramitelegalizado/'+ID,function(result){
              $('#myModal3').modal({show:true});

                $('.confirmar_impresion').click(function(event) {
                  
                  var siguienteEstampilla = $('#siguienteEstampilla').val();
                  if(!confirm('SIGUIENTE ESTAMPIILLA A IMPRIMIRSE => No. '+siguienteEstampilla+'\n\n'
                        +'Esta seguro de generar la impresión?'
                        +' Recuerde que será modificado el consecutivo de la papeleria asignada a usted!'))
                  {
                    event.preventDefault();
                  }

              });
          

             });
         });
    }      




   }).columnFilter(

{
                     aoColumns: [
                                    
                                    {
                                         type: "number",
                                         sSelector: "#buscarnumero"
                                    },
                                    
                                    {
                                         type: "number",
                                         sSelector: "#buscarnit"
                                    },
                                    {
                                         type: "text",
                                         sSelector: "#buscarcontratista",
                                         bSmart: false

                                    },
                                    null,
                                    null,
                                    null,
                                    null,
                                    null,

                                    
                                 ]
               }


);
    
    oTable.fnSearchHighlighting();
    

} );




</script>


<style type="text/css">
  .dataTables_filter, .dataTables_length, .dataTables_footer {
    display: none;
  }
.item2{
    width: 160px;
    height: 15px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}
</style>
<div class="row"> 
 <div class="col-sm-12">
 <h1>Liquidación de trámites, documentos, certificados.</h1>
   <br><br>
    <?php
        echo anchor(base_url().'liquidaciones/addtramite','<i class="fa fa-plus"></i> Nueva liquidación ','class="btn btn-large btn-primary"');
    ?>
 </div>
</div> 
<br>
<div class="row"> 
<div class="col-sm-1"></div>
 <div class="col-sm-2">Identificación:<div align="center" id="buscarnumero"></div></div>
 <div class="col-sm-3">Nombre:<div align="center" id="buscarnit"></div></div>
 <div class="col-sm-3">Trámite:<div align="center" id="buscarcontratista"></div></div>
 <div class="col-sm-1"></div>
</div>


          
    

<div class="row"> 
 <div class="col-sm-12">    
   
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Identificación</th>
     <th>Nombre</th>
     <th>Trámite</th>   
     <th>Fecha de liquidación</th>
     <th>Observaciones</th>
     <th>Estado</th>
     <th></th>
   </tr>
 </thead>
 <tbody></tbody>     
 <tfoot>
   <tr class="dataTables_footer">
     <th></th>
     <th></th>
     <th></th>
     <th></th>
     <th></th>
     <th></th>
     <th></th> 
     <th></th>       
   </tr>
 </tfoot>
</table>
</div>
</div>   
      </div>

<?php echo form_open("liquidaciones/procesarliquidaciontramite",'role="form"');?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <input class="form-control" id="idtramite" type="hidden" name="idtramite" value=""/>
      <div class="modal-body liquida">
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Liquidar</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>



<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     
      <div class="modal-body paga">
         
      </div>
      <div class="modal-footer">
      <div class="text-center">
        <small> "Unidos por la grandeza del Tolima"<br>
      Edificio de la Gobernación del Tolima, carrera 3 calle 10 y 11, 9 piso <br>
      Teléfonos 2610758 - 2611111 -Ext. 209 - 305<br>
      dcontratos@outlook.com </small>
      </div>
        
      </div>
    </div>
  </div>
</div>




<?php echo form_open("liquidaciones/procesarterminado",'role="form"');?>
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <input class="form-control" id="idtramite" type="hidden" name="idtramite" value=""/>
      <div class="modal-body termina">
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>


<?php if ($accion=='creado') { ?>
<script type="text/javascript">
  
            var ID = <?php echo $idtramite; ?>;
            
           $('.liquida').load('<?php echo base_url(); ?>index.php/liquidaciones/verliquidartramite/'+ID,function(result){
            
            $('#myModal').modal('show');
          
        });
        
 
</script>
<?php   } ?>


<?php if ($accion=='liquidado') { ?>
<script type="text/javascript">
  
            var ID = <?php echo $idtramite; ?>;
            
            $('.paga').load('<?php echo base_url(); ?>index.php/liquidaciones/vertramiteliquidado/'+ID,function(result){
            
            $('#myModal2').modal('show');
            //alert(ID+'....');
        });
        
 
</script>
<?php   } ?>




<?php if ($accion=='legalizado') { ?>
<script type="text/javascript">
  
            var ID = <?php echo $idtramite; ?>;
            
           $('.termina').load('<?php echo base_url(); ?>index.php/liquidaciones/vertramitelegalizado/'+ID,function(result){
  var siguienteEstampilla = $('#siguienteEstampilla').val();alert(siguienteEstampilla);          
            $('#myModal3').modal('show');

        });
        
 
</script>
<?php   } ?>