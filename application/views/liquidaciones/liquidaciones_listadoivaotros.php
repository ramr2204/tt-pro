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
                        <th>Acciones</th>
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

<div class="modal fade" id="modal_auditoria" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-modal-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


