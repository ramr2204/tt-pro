<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            contratantes_list
*   Ruta:              /application/views/contratantes/contratantes_list.php
*   Descripcion:       tabla que muestra todos los contratantes existentes
*   Fecha Creacion:    18/dic/2018
*   @author           Michael Angelo Ortiz Trivinio <engineermikeortiz@gmail.com>
*   @version          2018-12-18
*
*/
?>

<script type="text/javascript" language="javascript" charset="utf-8">
    //generación de la tabla mediante json
    $(document).ready(function() {
    
    var oTable = $('#tablaq').dataTable( {
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": "<?php echo base_url(); ?>index.php/contratantes/dataTable",
    "sServerMethod": "POST",
    "aoColumns": [
                          { "sClass": "center"}, /*id 0*/
                          { "sClass": "item" },
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
        <h1>Contratantes</h1>
        <?php
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/add')) 
        {
            echo anchor(base_url().'contratantes/add','<i class="fa fa-plus"></i> Nuevo contratante ','class="btn btn-large  btn-primary"');
        }
        ?>
        <br><br> 
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>NIT</th>
                        <th>Nombre</th>
                        <th>Tipo de contratante</th>
                        <th>Municipio</th>
                        <th>Departamento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>