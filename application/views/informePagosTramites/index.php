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
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.21/api/fnReloadAjax.js"></script>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

    var oTable = $('#tabla_informe_pagos_tramites').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/informesPagosTramites/dataTable",
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
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "center", "bSortable": false, "bSearchable": false},
            { "sClass": "center", "bSortable": false, "bSearchable": false},
        ],
        "aoColumnDefs": [ 
        { "aTargets": [13], 
            "fnRender": function(o, val) { 
                return val == 1 ? 'Si' : 'No';
            } 
        },
        { "aTargets": [19], 
            "fnRender": function(o, val) { 
                return '<div class="btn-toolbar">'
                        +'<div>'
                        +'<a href="<?php echo base_url(); ?>'+val+'" class="btn btn-success btn-xs" title="Ver Soporte Factura"><i class="fa fa-file-image-o" aria-hidden="true"></i></a>'
                        +'</div>'
                        +'</div>'
            } 
        }
        ]
    });

    oTable.fnSearchHighlighting();

    $('.filtro_pago_tramite').change(function(){
        oTable.fnFilter($("input:radio[name=filtro_pago_tramite]:checked").val(), 13); 
    });

    $('#select_periodo_tramite').change(function(){
        oTable.fnFilter($("#select_periodo_tramite").val(), 11); 
    });
    
    $('#btn-reestablecer').click(function(){
        $('.filtro_pago_tramite').prop("checked", false);
        $('#select_periodo_tramite').val('');
        oTable.fnFilter('', 13); 
        oTable.fnFilter('', 11); 
    });

    $('#btn-exportar-excel-tra').click(function(){
        var pagado  = $("input:radio[name=filtro_pago_tramite]:checked").val()
        var periodo = $("#select_periodo_tramite").val()
        window.open("<?php echo base_url(); ?>informesPagosTramites/exportarExcelFacturacion?pagado="+pagado+"&anho_periodo="+periodo);
    })

} );
</script>
<div class="row"> 
    <div class="col-sm-12">    
        <h1>Tipo Trámites</h1>
        <br>
        <div class="container">
            <div style="display: table;width:100%">
                <div style="display: table-cell;">
                    <select class="form-control" id="select_periodo_tramite">
                        <option value="">Seleccione una opción</option>
                        <?php
                            for ($i=date('2020'); $i <= date('Y'); $i++) 
                            { 
                                ?> 
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <?php 
                            }
                        ?>
                    </select>
                </div>
                <div style="display: table-cell;text-align: center;">
                    <label>
                        <input type="radio" name="filtro_pago_tramite" value="1" class="filtro_pago_tramite">
                        Generados con Pago
                    </label>      
                </div>
                <div style="display: table-cell;text-align: center;">
                    <label>
                         <input type="radio" name="filtro_pago_tramite" value="0" class="filtro_pago_tramite">
                Generados sin Pago
                    </label>      
                </div>
                <div style="display: table;width:90%;margin-top: 20px">
                    <div style="display: table-cell;text-align: right;">
                        <button class="btn btn-primary" id="btn-reestablecer">Reestablecer búsquedas</button>
                    </div>
                    <div style="display: table-cell;text-align: left;">
                        <button class="btn btn-success" id="btn-exportar-excel-tra">Exportar Excel</button>            
                    </div>
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
                        <td>Fecha Vencimiento</td>
                        <td>Pagado</td>
                        <td>Documento</td>
                        <td>Codigo Barras</td>
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
