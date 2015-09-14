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

var oTable = $('#tabla_conciliaciones').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/pagos/conciliacionesDataTable",
"sServerMethod": "POST",
"iDisplayLength": 5,
"aoColumns": [                      
                      { "sClass": "item1 center" }, 
                      { "sClass": "item2" },
                      { "sClass": "center" },
                      { "sClass": "item" },
                      { "sClass": "item" },  
                      { "sClass": "item2"},
                      { "sClass": "item2"},
                      { "sClass": "item2"},
                      { "sClass": "item8"},
                      { "sClass": "item8"},
                      { "sClass": "item8"},
                      { "sClass": "item8"},
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
            ],   
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
    
  var number= accounting.formatMoney(aData[3], "$", 2, ".", ","); // €4.999,99
  $("td:eq(3)", nRow).html('<div class="">' + number + '</div>');

  number= accounting.formatMoney(aData[5], "$", 2, ".", ","); // €4.999,99
  $("td:eq(5)", nRow).html('<div class="">' + number + '</div>');

  number= accounting.formatMoney(aData[7], "$", 2, ".", ","); // €4.999,99
  $("td:eq(7)", nRow).html('<div class="">' + number + '</div>');

  /*
  * Si la diferencia en la conciliacion está vacia se cambia por un cero
  */
  if(aData[9] == '')
  {
      aData[9] = 0;
  }
  number= accounting.formatMoney(aData[9], "$", 2, ".", ","); // €4.999,99  
  $("td:eq(9)", nRow).html('<div class="">' + number + '</div>');

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
.item8{
    width: 40px;
    height: 15px;    
}
</style>
<div class="row"> 
 <div class="col-sm-12">
 <h1>Listado de Conciliaciones</h1>

   <br><br>
 </div>
</div> 

<div class="row">     
    <div class="col-xs-12 col-sm-3 col-sm-offset-9">
        Fecha (Conciliación):<div align="center" id="buscarfecha"></div>                
    </div>                     
</div>
            


<div class="row"> 
     <div class="col-sm-12">    
   
         <div class="table-responsive">
             <table class="table table-striped table-bordered table-hover" id="tabla_conciliaciones">
                 <thead>
                     <tr>
                         <th>Id</th>
                         <th>Tipo Liquidación</th>
                         <th>Contratista</th>
                         <th>Total</th>
                         <th>Fecha Pago</th>
                         <th>Valor Pago</th>                             
                         <th>Fecha Conciliacion</th> 
                         <th>Valor Conciliacion</th> 
                         <th>Estado</th>
                         <th>Diferencia Conciliacion</th>
                         <th>Usuario Conciliacion</th>
                         <th>Banco Conciliacion</th>
                         <th>Tipo</th>
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
	

<!-- Modal Rango-->
<div class="modal fade" id="m_rango" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Consultar por Rango (Fecha Generacion Estampilla)</h4>
      </div>
      <div class="modal-body">      
          <div class="row">          
              <div class="col-xs-12 col-sm-6">
                  <div class="form-group">
                      <label for="f_inicial">Fecha Inicial</label>
                      <div class='input-group date' id='datetimepicker_inicial' data-date-format="YYYY-MM-DD">
                          <input type='text' class="form-control" name="f_inicial" required="required"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>                      
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6">
                  <div class="form-group">
                      <label for="f_final">Fecha Final</label>
                      <div class='input-group date' id='datetimepicker_final' data-date-format="YYYY-MM-DD">
                          <input type='text' class="form-control" name="f_final" required="required"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>                       
                  </div>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <a class="btn btn-danger" id="btn-consultar">
            <i class="fa fa-file-pdf-o fa-1x"></i>        
            Consultar Relacion
        </a>     
        <a class="btn btn-danger" id="btn-consultar-detalle">
            <i class="fa fa-file-pdf-o fa-1x"></i>        
            Consultar Detalle
        </a> 
      </div>
    </div>
  </div>
</div>