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

    var oTable = $('#tablaq').dataTable( {
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
            { "sClass": "item" },
            { "sClass": "center", "bSortable": false, "bSearchable": false},
        ],
        "aoColumnDefs": [ 

            { 
                "aTargets": [5], 
                "fnRender": function(o, val) { 
                    return val == 1 ? 'Activo' : 'Inactivo';
                }
            },
            { 
                "bVisible": false,
                "aTargets": [3]
            }
        ]
    });

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
    <div class="col-sm-12">    
        <h1>Tipo Trámites</h1>

        <?php
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipoLiquidacionTramite/add')) {
            echo anchor(base_url().'tipoLiquidacionTramite/add','<i class="fa fa-plus"></i> Nuevo tipo trámite ','class="btn btn-large  btn-primary"');
            }
        ?>

        <br><br> 
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                       <th>Id</th>
                       <th>Valor</th>
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
