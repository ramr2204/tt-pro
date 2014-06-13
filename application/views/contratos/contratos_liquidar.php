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
<?php 
    $a= "[";
    foreach ($vigencias as $key => $value) {
      $a.='"'.$value.'", ';
    }
    $a=substr($a, 0, -2);
    $a.= "]"
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/contratos/liquidaciones_dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center","bVisible": false}, /*id 0*/
                      { "sClass": "center","sWidth": "6%" }, 
                      { "sClass": "center" }, 
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },  
                      { "sClass": "item" },
                      { "sClass": "item",},
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
            ],   
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

  $("td:eq(4)", nRow).html('<div class="small">' + aData[5].substr( 0, 130 )+ '...</div>');

 },
  "fnDrawCallback": function( oSettings ) {
      $(".agrega").on('click', function(event) {
           event.preventDefault();
           var ID = $(this).attr("id");
            $("#idcontrato").val(ID);
             $('.modal-body').load('<?php echo base_url(); ?>index.php/contratos/liquidar_contrato/'+ID,function(result){
              $('#myModal').modal({show:true});
             });
          // $('#myModal').modal('show');
         // var nodes = datatable.fnGetNodes();
          //   alert(datatable.fnGetNodes());
           
          // alert(oTable.fnGetData(2));
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
                                    {    
                                         sSelector: "#buscarano", 
                                         type:"select" ,
                                         values : <?php echo $a; ?>,
                                         selected: <?php echo $vigencias[0]; ?>
                                    },
                                    null,
                                    null,
                                    null,
                                    null,

                                    
                                 ]
               }


);
    
    oTable.fnSearchHighlighting();
    
    $('#tablaq tbody').on( 'click', '.agrega', function () {

        
    } );

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
 <h1>Contratos</h1>

   <br><br>
 </div>
</div> 

<div class="row"> 
<div class="col-sm-1"></div>
 <div class="col-sm-2">Número:<div align="center" id="buscarnumero"></div></div>
 <div class="col-sm-2">NIT:<div align="center" id="buscarnit"></div></div>
 <div class="col-sm-4">Contratista:<div align="center" id="buscarcontratista"></div></div>
 <div class="col-sm-2">Vigencia:<div align="center" id="buscarano"></div></div>
 <div class="col-sm-1"></div>
</div>


          
    







<div class="row"> 
 <div class="col-sm-12">    
   
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Número</th>
     <th>NIT</th>
     <th>Contratista</th>
     <th>Fecha</th>
     <th>Objeto</th>
     <th>Valor</th>       
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
     <th>Todas</th>
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

<?php echo form_open("tiposcontratos/delete",'role="form"');?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <input class="form-control" id="idcontrato" type="hidden" name="idcontrato" value=""/>
      <div class="modal-body">
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Liquidar</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>