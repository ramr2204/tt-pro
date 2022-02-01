<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/documentosNormativos/ordenanzas_list.php
*   Descripcion:       tabla que mustra todos los Documentos Normativos existentes
*   Fecha Creacion:    06/Ene/2016
*   @author           Mike Ortiz <engineermikeortiz@gmail.com>
*   @version          2016-01-06
*
*/

?>

<script type="text/javascript" language="javascript" charset="utf-8">

$(document).ready(function() {

    var oTable = $('#tablaq').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/documentosNormativos/dataTable",
        "sServerMethod": "POST",
        "aoColumns": [ 
                        { "sClass": "center"}, /*id 0*/
                        { "sClass": "center"},
                        { "sClass": "item" },  
                        { "sClass": "item" },
                        { "sClass": "item" },
                        { "sClass": "item" },
                        { "sClass": "center","bSortable": false,"bSearchable": false},                    
                      ],    
} );

    oTable.fnSearchHighlighting();
} );
</script>

<div class="row"> 
    <div class="col-sm-12">    
        <h1>Documentos Normativos</h1>
        <?php
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('documentosNormativos/add'))
            {
                echo anchor(base_url().'documentosNormativos/add','<i class="fa fa-plus"></i> Nuevo Documento ','class="btn btn-large  btn-primary"');
            }
        ?>
        <br><br> 
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tipo</th>
                        <th>Número</th>
                        <th>Fecha Expedición</th>
                        <th>Inicio Vigencia</th>
                        <th>Documento</th>
                        <th>Detalles</th>     
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
</div>
