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
                      { "sClass": "item2"},
                      { "sClass": "item2"},
                      { "sClass": "item2"},
                      { "sClass": "item8"},
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
          $("td:eq(9)", nRow).html('<div class="text-left">' + data.estampillas + '</div>');                                        
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
.item8{
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
    <div class="col-xs-12 col-sm-3 col-custom-chosen">
        Fecha (Generacion Estampilla):<div align="center" id="buscarfecha"></div>                
    </div>
    <div class="col-xs-12 col-sm-4 btn-pdf">
        <div class="form-group">
            <label for="tipoEst">Tipo Estampilla (Detalle)</label>
            <select class="form-control chosen" id="tipoEst">
                <option value="0">Seleccione...</option>
                <?php  foreach($estampillas as $id => $valor) { ?>
                    <option value="<?php echo $id; ?>"><?php echo $valor; ?></option>
                <?php   } ?>
            </select>                
        </div>
    </div>
    <div class="col-xs-12 col-sm-5 btn-pdf">        
        <div class="btn-group group-custom-chosen">
            <a class="btn btn-danger" id="btn-relacion" >
                <i class="fa fa-file-pdf-o fa-1x"></i>
                Relación
            </a>        
            <a class="btn btn-success" id="btn-detalle-excel">
                <i class="fa fa-file-excel-o fa-1x"></i>
                Detalle-Excel
            </a>
            <a class="btn btn-danger" id="btn-detalle-pdf">
              <i class="fa fa-file-pdf-o fa-1x"></i>
              Detalle-PDF
            </a>        
            <a class="btn btn-danger" id="btn-rango">
                <i class="fa fa-file-pdf-o fa-1x"></i>
                Rango
            </a>
        </div>
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
                         <th>Fecha Liquidacion</th>                             
                         <th>Fecha Pago</th> 
                         <th>Valor Factura</th> 
                         <th>Concepto</th>
                         <th>Estampillas</th>                             
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




