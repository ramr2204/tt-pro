<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


<script type="text/javascript" language="javascript" charset="utf-8">

    meses = JSON.parse('<?= json_encode($meses) ?>');
    tipos_declaraciones = JSON.parse('<?= json_encode($tipos_declaraciones) ?>');

    function capitalize(string) {
        return string[0].toUpperCase() + string.slice(1)
    }

    //generación de la tabla mediante json
    $(document).ready(function() {
        var oTable = $('#tablaq').dataTable( {
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?php echo base_url(); ?>index.php/declaraciones/dataTable',
            'sServerMethod': 'POST',
            'aoColumns': [
                { 'sClass': 'center','sWidth': '5%' }, /*id 0*/
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item', 'bSortable': false,'bSearchable': false },
                { 'sClass': 'item', 'bSortable': false,'bSearchable': false },
                { 'sClass': 'item' },
                { 'sClass': 'center','bSortable': false,'bSearchable': false,'sWidth': '5%' },
            ],
            'fnRowCallback':function( nRow, aData, iDataIndex ) {

                var fecha_partida = aData[3].split('-')

                $('td:eq(3)', nRow).html(capitalize(meses[fecha_partida[1]]) + ' ' + fecha_partida[0])

                $('td:eq(4)', nRow).html(tipos_declaraciones[aData[4]])
            }, 

        } );

        oTable.fnSearchHighlighting();
    } );
</script>

<div class="row"> 
    <div class="col-sm-12">    
        <h1><?php echo lang('index_heading');?></h1>
        <?php echo anchor(base_url().'declaraciones/create','<i class="fa fa-plus"></i> '. lang('index_create_user_link'),'class="btn btn-large  btn-primary"'); ?>
        <br><br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Empresa</th>
                        <th>Estampilla</th>
                        <th>Periodo</th>
                        <th>Tipo de Declaracion</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
 </div>











