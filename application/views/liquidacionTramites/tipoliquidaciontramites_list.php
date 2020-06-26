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

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

    var oTable = $('#table-concepto-tramites').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/tipoLiquidacionTramite/dataTable",
        "sServerMethod": "POST",
        "aoColumns": [ 
            { "sClass": "center"}, /*id 0*/
            { "sClass": "item" }, 
            { "sClass": "item" }, 
            { "sClass": "item" },
            { "sClass": "item" },
            { "sClass": "center", "bSortable": false, "bSearchable": false},
        ],
        "aoColumnDefs": [ 

            { 
                "aTargets": [4], 
                "fnRender": function(o, val) { 
                    return val == 1 ? 'Activo' : 'Inactivo';
                }
            },
            { 
                "bVisible": false,
                "aTargets": [2]
            }
        ]
    });

    oTable.fnSearchHighlighting();

    var modal_conceptos = $('#table-concepto-tramites-modal').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url + "index.php/tipoLiquidacionTramite/dataTableConceptos?id="+$(this).val(),
        "sServerMethod": "POST",
        "aoColumns": [ 
            { "sClass": "center"}, /*id 0*/
            { "sClass": "item" }, 
            { "sClass": "item" }
        ]
    });

    modal_conceptos.fnSearchHighlighting();
} );

</script>

<div class="row"> 

    <!-- Modal -->
    <div class="modal fade" id="conceptos-tramites-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p style="font-size: 18px;text-align: center" class="modal-title" id="exampleModalLabel">Conceptos Valor Trámite</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-concepto-tramites-modal">
                            <thead>
                                <tr>
                                   <th>Id</th>
                                   <th>Concepto</th>
                                   <th>Valor</th>
                               </tr>
                           </thead>
                            <tbody></tbody>     
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">    
        <h1>Tipo Trámites</h1>

        <?php
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipoLiquidacionTramite/add')) {
            echo anchor(base_url().'tipoLiquidacionTramite/add','<i class="fa fa-plus"></i> Nuevo tipo trámite ','class="btn btn-large  btn-primary"');
            }
        ?>

        <br><br> 
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="table-concepto-tramites">
                <thead>
                    <tr>
                       <th>Id</th>
                       <th>Vigencia</th>
                       <th>escondida</th>
                       <th>Nombre</th>
                       <th>Estado</th>
                       <th>Acciones</th>
                   </tr>
               </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
</div>
