<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratosestampillas/ordenanzas_list.php
*   Descripcion:       tabla que muestra todos los contratos existentes
*   Fecha Creacion:    05/Ene/2016
*   @author           Mike Ortiz <engineermikeortiz@gmail.com>
*   @version          2016-01-05
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">
    //generación de la tabla mediante json
    $(document).ready(function() {

        var oTable = $('#tablaq').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url(); ?>index.php/contratoEstampillas/dataTable",
            "sServerMethod": "POST",
            "aoColumns": [ 
                { "sClass": "center"}, /*id 0*/                 
                { "sClass": "item" },  
                { "sClass": "item" },
                { "sClass": "item" }
            ],    
        });

        oTable.fnSearchHighlighting();
    });
</script>

<div class="row"> 
    <div class="col-sm-12">    
        <h1>Contratos Estampillas</h1>

        <?php
            if ($this->ion_auth->is_admin()) 
            {
                echo anchor(base_url().'contratoEstampillas/add','<i class="fa fa-plus"></i> Nuevo Contrato Estampillas ','class="btn btn-large  btn-primary"');
            }
        ?>
        <br><br> 
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>                        
                        <th>Número</th>
                        <th>Fecha Contrato</th>
                        <th>Cantidad Estampillas Contratadas</th>
                        <th>Cantidad Estampillas Impresas</th>
                        <th>Detalles</th>     
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
</div>
