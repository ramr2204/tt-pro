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
<style type="text/css">
  
    .item2{
        width: 160px;
        height: 15px;    
        overflow: hidden;
        text-align: center;        
    }   
    .cantidades{
        width: 100px;
        height: 15px;    
        overflow: hidden;
        text-align: center;        
    }     
</style>

<script type="text/javascript" language="javascript" charset="utf-8">
    //generación de la tabla mediante json
    $(document).ready(function() {

        var oTable = $('#tablaq').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url(); ?>index.php/contratoEstampillas/dataTable",
            "sServerMethod": "POST",
            "aoColumns": [ 
                { "sClass": "item2"},
                { "sClass": "item2" },  
                { "sClass": "cantidades" },
                { "sClass": "cantidades" },
                { "sClass": "cantidades" },
                { "sClass": "item" },
                { "sClass": "item2" }
            ],
            "fnRowCallback":function( nRow, aData, iDataIndex ) {
                
                /*
                * Arreglo con descripcion de estados de contratos
                * de estampillas para establecer en la tabla
                * en la columna estado
                */
                var estados = ['Inactivo','Activo','Completado'];
                var estado = aData[6];
                var ancla = '';

                if(estado == 0)
                {
                    ancla = '<a href="'+<?php echo json_encode(base_url().'contratoEstampillas/state/'); ?>+ aData[7] +'"'
                        +' class="btn btn-danger">'
                        +'<i class="fa fa-times"></i> '
                        +estados[estado]+'</a>';
                }else if(estado == 1)
                    {
                        ancla = '<a href="'+<?php echo json_encode(base_url().'contratoEstampillas/state/'); ?>+ aData[7] +'"'
                            +' class="btn btn-success">'
                            +'<i class="fa fa-check"></i> '
                            +estados[estado]+'</a>';
                    }else if(estado == 2)
                        {
                            ancla = '<a href="#" class="btn btn-warning">'
                                +'<i class="fa fa-ban"></i> '
                                +estados[estado]+'</a>';
                        }                

                $('td:eq(6)', nRow).html(ancla);

                /*
                * Calcula las estampillas restantes para impresion en el contrato
                */
                var totContrato = aData[2];
                var totImpresas = aData[3];
                var totRestantes = parseInt(totContrato) - parseInt(totImpresas);
                $('td:eq(4)', nRow).html(totRestantes);
          
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
                        <th>Estampillas Contratadas</th>
                        <th>Estampillas Impresas</th>
                        <th>Estampillas Disponibles</th>
                        <th>Detalles</th>     
                        <th>Estado</th>     
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
</div>
