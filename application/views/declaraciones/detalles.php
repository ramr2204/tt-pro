<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="row"> 
    <div class="col-sm-12">
        <h1>Detalles de la Declaración</h1>
        <div class="btn-group">
            <?php
                if( $this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index') ) {
                    echo anchor(base_url().'declaraciones/index','<i class="fa fa-reply"></i>','class="btn btn-large btn-default" title="Volver"');
                }
            ?>
            <?= anchor(base_url().'declaraciones/detallesExcel/'.$id_declaracion,'<i class="fa fa-file-excel-o"></i>','class="btn btn-large btn-success" title="Generar Excel"'); ?>
        </div>
        <br><br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>Nº doc.</th>
                        <th>Fe.contab.</th>
                        <th>Importe en MD</th>
                        <th>Impte.base Qst en MI</th>
                        <th>Importe qst en MI</th>
                        <th>Contrato</th>
                        <th>Pago</th>
                        <th>Estampilla</th>
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>
 </div>

<script type="text/javascript" language="javascript" charset="utf-8">

    $(function () {
        construirTablaDetallesDeclaracion()
    });

    function construirTablaDetallesDeclaracion() {

        var oTable = $('#tablaq').dataTable( {
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?php echo base_url(); ?>index.php/declaraciones/detallesDatatable?id_declaracion=<?= $id_declaracion ?>',
            'sServerMethod': 'POST',
            'aoColumns': [
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
            ],
            'fnRowCallback':function( nRow, aData, iDataIndex ) {
                for (var i = 3; i <= 5; i++) {
                    $('td:eq('+i+')', nRow).html('<div class="">' + (accounting.formatMoney(aData[i], "$", 2, ".", ",")) + '</div>');
                }
            },

        } );

        oTable.fnSearchHighlighting();
    }
</script>