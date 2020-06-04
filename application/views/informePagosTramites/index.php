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
    $('#desde_fecha_creacion').datepicker({
      dateFormat: 'yy-mm-dd',
    });

    $('#desde_fecha_final').datepicker({
      dateFormat: 'yy-mm-dd',
    });

    var oTable = $('#tabla_informe_pagos_tramites').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/informesPagosTramites/dataTable?fecha_ini="+ String($('#desde_fecha_creacion').val())+"&fecha_fin="+String($('#desde_fecha_final').val()),
        "sServerMethod": "POST",
        "aoColumns": [ 
            { "sClass": "center"}, /*id 0*/
            { "sClass": "item" }, 
            { "sClass": "item" }, 
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "center", "bSortable": false, "bSearchable": false},
            { "sClass": "center", "bSortable": false, "bSearchable": false},
        ],
        "aoColumnDefs": [ 
        { "aTargets": [12], 
            "fnRender": function(o, val) { 
                return val == 1 ? 'Si' : 'No';
            } 
        },
        { "aTargets": [17], 
            "fnRender": function(o, val) { 
                if(val != '')
                {
                    return '<div class="btn-toolbar">'
                            +'<div>'
                            +'<a href="<?php echo base_url(); ?>'+val+'" class="btn btn-success btn-xs" title="Ver Soporte Factura"><i class="fa fa-file-image-o" aria-hidden="true"></i></a>'
                            +'</div>'
                            +'</div>';
                }
                else
                {
                    return 'No adjuntó';
                }
            } 
        }
        ]
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
        <h1>Informe Pagos Trámites</h1>
        <br>
        <div class="container">
            <div style="display: table;width:100%">
                <div style="display: table-cell;">
                    <div class="form-group" style="width: 93%">
                        <label for="tipo_documento">Fecha Inicio</label>
                        <input id="desde_fecha_creacion" type="text" name="desde_fecha_creacion" class="form-control"/>
                    </div>
                </div>
                <div style="display: table-cell;">
                    <div class="form-group" style="width: 93%">
                        <label for="tipo_documento">Fecha Final</label>
                        <input id="desde_fecha_final" type="text" name="desde_fecha_final" class="form-control"/>
                    </div>
                </div>
                <div style="display: table-cell;">
                    <div class="form-group" style="width: 93%">
                        <label for="tipo_documento">Vigencia Trámite</label>
                        <select name="select-tipo-tramite"id="tramite_vigencia" class="form-control">
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
                    <div class="form-group" style="width: 93%">
                        <label for="tipo_documento">Tipo Trámite</label>
                        <select name="select-tipo-tramite" id="select-tipo-tramite" class="form-control select-tipo-tramite">
                            <option value="">Seleccione una opción</option>
                        </select>
                    </div>
                </div>
                <div style="display: table;width:90%;margin-top: 20px">
                    <div style="display: table-cell;text-align: right;">
                        <button class="btn btn-primary" id="btn-reestablecer" style="margin-top: 60px">Reestablecer búsquedas</button>
                    </div>
                    <div style="display: table-cell;text-align: left;">
                        <button class="btn btn-success" id="btn-exportar-excel-tra" style="margin-top: 60px">Exportar Excel</button>            
                    </div>
                </div>
            </div>
            <div style="margin-top: 10px">
                <div class="col-md-4" style="text-align: right;">
                    <label>
                        <input type="radio" name="filtro_pago_tramite" value="1" class="filtro_pago_tramite">
                        Generados con Pago
                    </label>  
                </div>
                <div class="col-md-4">
                    <label>
                        <input type="radio" name="filtro_pago_tramite" value="0" class="filtro_pago_tramite">
                        Generados sin Pago
                    </label>   
                </div>
                <div class="col-md-4">
                    
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div class="div-table-fijo ">
            <table class="table table-striped table-bordered table-hover" id="tabla_informe_pagos_tramites">
                <thead>
                    <tr>
                        <td>Id</td>
                        <td>Primer Nombre</td>
                        <td>Segundo Nombre</td>
                        <td>Primer Apellido</td>
                        <td>Segundo Apellido</td>
                        <td>Telefono 1</td>
                        <td>Telefono 2</td>
                        <td>Dirección</td>
                        <td>Departamento</td>
                        <td>Municipio</td>
                        <td>Trámite</td>
                        <td>Fecha Creación</td>
                        <td>Pagado</td>
                        <td>Documento</td>
                        <td>Fecha Pago</td>
                        <td>Banco</td>
                        <td>Numero Factura</td>
                        <td>Ver Soporte</td>
                        <td>Ver Factura</td>
                   </tr>
               </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
</div>