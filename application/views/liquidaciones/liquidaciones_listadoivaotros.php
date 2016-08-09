<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            liquidaciones_listadoivaotros
*   Ruta:              /application/views/liquidaciones/liquidaciones_listadoivaotros.php
*   Descripcion:       tabla que muestra todos los contratos liquidados con regimen otros existentes
*   Fecha Creacion:    08/Ago/2016
*   @author           Mike Ortiz <engineermikeortiz@gmail.com>
*   @version          2016-08-08
*
*/
?>
<script type="text/javascript" language="javascript" charset="utf-8">
    /*
    * establecimiento de la variable para el select
    */
    var vecVig = <?php echo $vigencias; ?>;
</script>

<style type="text/css">
    .dataTables_filter, .dataTables_length, .dataTables_footer 
    {
      display: none;
    }
    .item2
    {
        width: 160px;
        height: 15px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <h1>Auditar Liquidaciones</h1>
        <br>
    </div>
</div> 

<div class="row"> 
    <div class="col-sm-1"></div>
    <div class="col-sm-2">Número Contrato:<div align="center" id="buscarnumero"></div></div>
    <div class="col-sm-2">NIT Contratista:<div align="center" id="buscarnit"></div></div>
    <div class="col-sm-2">Vigencia Liquidación:<div align="center" id="buscarano"></div></div>
    <div class="col-sm-1"></div>
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tabla_audit">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Número Contrato</th>
                        <th>NIT Contratista</th>
                        <th class="text-center center-vertical">Valor total</th>
                        <th class="text-center center-vertical">Valor IVA</th>
                        <th class="text-center center-vertical">Valores Estampillas</th>
                        <th>Fecha Liquidación</th>       
                        <th>Soporte</th>
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
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <input class="form-control" id="idcontrato" type="hidden" name="idcontrato" value=""/>
      <div class="modal-body liquida">
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Liquidar</button>
      </div>
    </div>
  </div>
</div>


