<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratistas/contratistas_list.php
*   Descripcion:       tabla que muestra todos los contratistas existentes
*   Fecha Creacion:    20/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-20
*
*/
?>
<style type="text/css">
    .div-table-fijo
    {
        overflow-x:scroll;
        width:100%;
    }

</style>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.21/api/fnReloadAjax.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<script type="text/javascript" language="javascript" charset="utf-8">

//generación de la tabla mediante json
$(document).ready(function() 
{
    var oTable = $('#tabla_informe_totalizado_tramites').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/totalizadoPersonaTramite/dataTable",
        "sServerMethod": "POST",
        "aoColumns": [ 
            { "sClass": "center"}, /*id 0*/
            { "sClass": "item" }, 
            { "sClass": "item" }, 
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" }
        ],
    });

    oTable.fnSearchHighlighting();

    jQuery.datepicker.regional['es'] = {
        closeText: 'Cerrar',
         prevText: '< Ant',
         nextText: 'Sig >',
         currentText: 'Hoy',
         monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
         monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
         dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
         dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
         dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
         weekHeader: 'Sm',
         dateFormat: 'dd/mm/yy',
         firstDay: 1,
         isRTL: false,
         showMonthAfterYear: false,
         yearSuffix: ''
    }
    jQuery.datepicker.setDefaults($.datepicker.regional['es']);

} );
</script>
<div class="row"> 
    <div class="col-sm-12">    
        <h1>Informe Totalizado Trámites</h1>
        <br>
        <div class="container">
            <div style="display: table;width:100%">
                <div style="display: table-cell;">
                    <div class="form-group" style="width: 80%">
                        <label for="tipo_documento">Vigencia Trámite</label>
                        <select name="select-tipo-tramite"id="tramite_vigencia_totalizado" class="form-control ">
                            <option value="">Seleccione una opción</option>
                            <?php
                            foreach ($result['vigencia_tramite'] as $vigencia) {
                                ?>
                                    <option value="<?php echo $vigencia->vigencia ?>"><?php echo $vigencia->vigencia ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div style="display: table-cell;">
                    <div class="form-group" style="width: 80%">
                        <label for="tipo_documento">Tipo Trámite</label>
                        <select name="select-tipo-tramite" id="select-tipo-tramite-total" class="form-control select-tipo-tramite">
                            <option value="">Seleccione una opción</option>
                        </select>
                    </div>
                </div>
                <div style="display: table;width:90%">
                    <div style="display: table-cell;text-align: right;">
                        <button class="btn btn-primary" id="btn-reestablecer-totalizado" style="margin-top:60px">Reestablecer búsquedas</button>
                    </div>
                    <div style="display: table-cell;text-align: left;">
                        <button class="btn btn-success" id="btn-exportar-excel-tra-total" style="margin-top:60px">Exportar Excel</button>            
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div class="div-table-fijo ">
            <table class="table table-striped table-bordered table-hover" id="tabla_informe_totalizado_tramites">
                <thead>
                    <tr>
                        <td>Id</td>
                        <td>Vigencia</td>
                        <td>Valor Individual</td>
                        <td>Nombre Trámite</td>
                        <td>Cantidad Personas</td>
                        <td>Total</td>
                   </tr>
               </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
</div>