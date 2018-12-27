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
    <div class="col-xs-12 col-md-3 col-custom-chosen">
        Fecha (Generacion Estampilla):<div align="center" id="buscarfecha"></div>                
    </div>
    <div class="col-xs-12 col-md-9 btn-pdf">
        <div class="btn-group group-custom-chosen pull-right">
            <a class="btn btn-default" id="btn-rango">
                <i class="fa fa-file-pdf-o fa-1x text-danger" style="font-weight: bold;"></i>
                /
                <i class="fa fa-file-excel-o fa-1x text-success" style="font-weight: bold;"></i>
                <b>Generar Informes</b>
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
  <div class="modal-dialog modal-lg" role="document" style="width: 750px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Consultar por Rango de fechas</h4>
      </div>
      <div class="modal-body">      
          <div class="row">
              <div class="col-xs-12">
               <legend>Filtrar por fecha de liquidaci&oacute;n</legend>
              </div>
              <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                      <label for="f_inicial">Fecha Inicial</label>
                      <div class='input-group date' id='datetimepicker_inicial_liquidacion' data-date-format="YYYY-MM-DD">
                          <input type='text' class="form-control" name="f_inicial_liquidacion" required="required"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>                      
                  </div>
              </div>
              <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                      <label for="f_final">Fecha Final</label>
                      <div class='input-group date' id='datetimepicker_final_liquidacion' data-date-format="YYYY-MM-DD">
                          <input type='text' class="form-control" name="f_final_liquidacion" required="required"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>                       
                  </div>
              </div>
              <div class="col-xs-12">
               <legend>Filtrar por fecha de pago</legend>
              </div>
              <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                      <label for="f_inicial">Fecha Inicial</label>
                      <div class='input-group date' id='datetimepicker_inicial_pago' data-date-format="YYYY-MM-DD">
                          <input type='text' class="form-control" name="f_inicial_pago" required="required"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>                      
                  </div>
              </div>
              <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                      <label for="f_final">Fecha Final</label>
                      <div class='input-group date' id='datetimepicker_final_pago' data-date-format="YYYY-MM-DD">
                          <input type='text' class="form-control" name="f_final_pago" required="required"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>                       
                  </div>
              </div>
            <div class="col-xs-12">
               <legend>Filtrar por fecha de Impresi&oacute;n</legend>
            </div>
              <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                      <label for="f_inicial">Fecha Inicial</label>
                      <div class='input-group date' id='datetimepicker_inicial_impr' data-date-format="YYYY-MM-DD">
                          <input type='text' class="form-control" name="f_inicial_impr" required="required"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>                      
                  </div>
              </div>
              <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                      <label for="f_final">Fecha Final</label>
                      <div class='input-group date' id='datetimepicker_final_impr' data-date-format="YYYY-MM-DD">
                          <input type='text' class="form-control" name="f_final_impr" required="required"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>                       
                  </div>
              </div>
              <div class="col-xs-12">
               <legend>Filtros adicionales</legend>
               </div>
              <div class="col-xs-12 col-md-6 btn-pdf">
                  <div class="form-group">
                      <label for="tipoEst">Tipo Estampilla</label>
                      <select class="form-control chosen-modal" id="tipoEst">
                          <option value="0">Seleccione...</option>
                          <?php  foreach($estampillas as $id => $valor) { ?>
                              <option value="<?php echo $id; ?>"><?php echo $valor; ?></option>
                          <?php   } ?>
                      </select>
                  </div>
              </div>
              <div class="col-xs-12 col-md-6 btn-pdf">
                  <div class="form-group">
                      <label for="tipoActo">Tipo Acto</label>
                      <select class="form-control" id="tipoActo">
                          <option value="0">Seleccione...</option>
                          <?php  foreach($tipos_acto as $id => $valor) { ?>
                              <option value="<?php echo $id; ?>"><?php echo $valor; ?></option>
                          <?php   } ?>
                      </select>
                  </div>
              </div>
              <div class="col-xs-12 btn-pdf">
                  <div class="form-group">
                      <label for="tipoActo">Subtipo Acto</label>
                      <select class="form-control chosen-modal" id="subTipoActo">
                          <option value="0">Seleccione...</option>
                          <?php  foreach($subtipos_acto as $id => $valor) { ?>
                              <option value="<?php echo $id; ?>"><?php echo $valor; ?></option>
                          <?php   } ?>
                      </select>
                  </div>
              </div>
              <div class="col-xs-12 btn-pdf">
                  <div class="form-group">
                      <label for="contribuyente">Contribuyente</label>
                      <select class="form-control chosen-modal" id="contribuyente">
                          <option value="0">Seleccione...</option>
                          <?php  foreach($contribuyentes as $id => $valor) { ?>
                              <option value="<?php echo $id; ?>"><?php echo $valor; ?></option>
                          <?php   } ?>
                      </select>
                  </div>
              </div>
              <div class="col-xs-12 btn-pdf">
                  <div class="form-group">
                      <label for="contratante">Contratante (Contratos)</label>
                      <select class="form-control chosen-modal" id="contratante">
                          <option value="0">Seleccione...</option>
                          <?php  foreach($contratantes as $id => $valor) { ?>
                              <option value="<?php echo $id; ?>"><?php echo $valor; ?></option>
                          <?php   } ?>
                      </select>
                  </div>
              </div>
              <div class="col-xs-12 btn-pdf">
                  <div class="form-group">
                      <label for="municipio">Municipios (Contratos)</label>
                      <select class="form-control chosen-modal" id="municipio">
                          <option value="0">Seleccione...</option>
                          <?php  foreach($municipios as $objMunicipio) { ?>
                              <option value="<?php echo $objMunicipio->muni_id; ?>"><?php echo $objMunicipio->muni_nombre; ?></option>
                          <?php   } ?>
                      </select>
                  </div>
              </div>
              <div class="col-xs-12 btn-pdf">
                  <label>(Sólo informe Relaci&oacute;n) Agrupar por :</label>
              </div>
              <div class="col-xs-12 btn-pdf text-center">
                    <label class="checkbox-inline">
                        <input type="checkbox" id="group_anio"> <b>Año</b>
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="group_mes"> <b>Mes</b>
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="group_contribuyente"> <b>Contribuyente</b>
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="group_tipoacto"> <b>Tipo Acto</b>
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="group_subtipoacto"> <b>Subtipo Acto</b>
                    </label>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
            <a class="btn btn-danger" id="btn-consultar" documento="consolidado_pdf">
                <i class="fa fa-file-pdf-o fa-1x"></i>        
                Consultar Relacion
            </a>     
            <a class="btn btn-success" id="btn-consultar-detalle-excel" documento="excel">
                <i class="fa fa-file-excel-o fa-1x"></i>
                Consultar Detalle-Excel
            </a>
            <a class="btn btn-danger" id="btn-consultar-detalle-pdf" documento="pdf">
                <i class="fa fa-file-pdf-o fa-1x"></i>        
                Consultar Detalle-PDF
            </a>
        </div>
      </div>
    </div>
  </div>
</div>