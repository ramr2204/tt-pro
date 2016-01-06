<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratosestampillas/contratosestampillas_list.php
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
                { "sClass": "center" },  
                { "sClass": "center" },
                { "sClass": "center" },
                { "sClass": "item" },
                { "sClass": "center" }
            ],
            "fnRowCallback":function( nRow, aData, iDataIndex ) {
                
                /*
                * Arreglo con descripcion de estados de contratos
                * de estampillas para establecer en la tabla
                * en la columna estado
                */
                var estados = ['Inactivo','Activo','Completado'];
                var estado = aData[5];
                var ancla = '';

                if(estado == 0)
                {
                    ancla = '<a href="'+<?php echo json_encode(base_url().'contratoEstampillas/state/'); ?>+ aData[6] +'"'
                        +' class="btn btn-danger">'
                        +'<i class="fa fa-times"></i> '
                        +estados[estado]+'</a>';
                }else if(estado == 1)
                    {
                        ancla = '<a href="'+<?php echo json_encode(base_url().'contratoEstampillas/state/'); ?>+ aData[6] +'"'
                            +' class="btn btn-success">'
                            +'<i class="fa fa-check"></i> '
                            +estados[estado]+'</a>';
                    }else if(estado == 2)
                        {
                            ancla = '<a href="#" class="btn btn-warning">'
                                +'<i class="fa fa-ban"></i> '
                                +estados[estado]+'</a>';
                        }                

                $('td:eq(5)', nRow).html(ancla);

            // $.ajax({
            //    type: "POST",
            //    dataType: "html",
            //    data: {papelid : aData[0]},
            //    url: "<?php echo base_url(); ?>index.php/papeles/contarpapeles",
            //    success: function(data) {
            //      var restante=cantidad-data;
            //      $('td:eq(5)', nRow).html(data);
            //      $('td:eq(6)', nRow).html(restante);
            //    }
            //  });
          
         }
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
                        <th>Estado</th>     
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
</div>
