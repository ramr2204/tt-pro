<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            liquidaciones_consultar
*   Ruta:              /application/views/liquidaciones/liquidaciones_consultar.php
*   Descripcion:       tabla que mustra todos las liquidaciones existentes
*   Fecha Creacion:    10/ene/2015
*   @author           Michael Ortiz <michael.ortiz@turrisystem.com>
*   @version          2015-01-10
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/liquidaciones/consultas_dataTable",
"sServerMethod": "POST",
"iDisplayLength": 5,
"aoColumns": [                      
                      { "sClass": "item1 center" }, 
                      { "sClass": "item2" },
                      { "sClass": "center" },
                      { "sClass": "item" },
                      { "sClass": "item" },  
                      { "sClass": "item6"},
                      { "sClass": "item"},
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
            ],   
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
    
  var number= accounting.formatMoney(aData[4], "$", 2, ".", ","); // €4.999,99
  $("td:eq(4)", nRow).html('<div class="">' + number + '</div>');  

  var liquidacion = aData[0];

  $.ajax({
      type: "POST",
      dataType: "json",
      data: {id : liquidacion},
      url: base_url+"index.php/liquidaciones/extraerFacturas",
      success: function(data) {              
          $("td:eq(6)", nRow).html('<div class="text-left">' + data.estampillas + '</div>');                                        
      }
  });

 },
  "fnDrawCallback": function( oSettings ) {
      //eventos a los elementos del data table
   
    }     




   }).columnFilter(

{
                     aoColumns: [
                                    
                                    null,
                                    null,
                                    null,
                                    null,
                                    null,
                                    {
                                         type: "date",
                                         sSelector: "#buscarfecha"
                                    },
                                    null,
                                    null

                                    
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
    width: 30px;
    height: 15px;    
}
.item1{
    width: 20px;
    height: 15px;    
}
.item6{
    width: 40px;
    height: 15px;    
}
</style>
<div class="row"> 
 <div class="col-sm-12">
 <h1>Consulta de Liquidaciones</h1>

   <br><br>
 </div>
</div> 

<div class="row">     
    <div class="col-xs-12 col-sm-2 col-sm-offset-8">
        Fecha:<div align="center" id="buscarfecha"></div>                
    </div>              
    <div class="col-xs-12 col-sm-2 btn-pdf">
        <a class="btn btn-danger" id="btn-pdf">PDF</a>
    </div>

</div>
            


<div class="row"> 
     <div class="col-sm-12">    
   
         <div class="table-responsive">
             <table class="table table-striped table-bordered table-hover" id="tablaq">
                 <thead>
                     <tr>
                         <th>Id</th>
                         <th>Tipo Liquidación</th>
                         <th>NIT</th>
                         <th>Contratista</th>
                         <th>Total</th>
                         <th>Fecha</th>
                         <th>Estampillas</th>       
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




