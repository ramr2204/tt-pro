<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratosestampillas/impresiones_add.php
*   Descripcion:       permite visualizar el saldo de estampillas para un contrato activo
*   Fecha Creacion:    12/may/2014
*   @author            Mike Ortiz <engineermikeortiz@gmail.com>
*   @version           2016-02-08
*
*/

?>
<div class="row clearfix">
    <div class="col-md-12">
        <div class="row clearfix">        
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Cantidad Estampillas Disponibles</h1></div>
                    <div class="panel-body">                              
                        <div class="alert alert-success text-center" role="alert">
                            <span style="font-size:50px"><?php echo $saldo; ?></span>
                        </div>
                    </div>
                </div>      
            </div>                
        </div> 
    </div>
</div>