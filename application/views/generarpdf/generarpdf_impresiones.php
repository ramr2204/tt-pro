<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            generarpdf_impresiones.php
*   Ruta:              /application/views/generarpdf/generarpdf_impresiones.php
*   Descripcion:       contiene la estructura del pdf del listado de impresiones del día
*   Fecha Creacion:    13/ene/2015
*   @author           Mike Ortiz <michael.ortiz@turrisystem.com>
*   @version          2015-01-13
*
*/
?>

<h1 style="text-align: center;">Listado de Impresiones para la Fecha <?php echo $fecha; ?></h1>
<br>
<br>
<table class="table table-striped table-bordered table-hover" id="tablaq">
    <thead>
        <tr>
            <th>Id</th>
            <th>Tipo Liquidación</th>
            <th>NIT</th>
            <th>Contratista</th>
            <th>Total</th>            
            <th>Estampillas</th>       
            <th></th>                  
        </tr>
    </thead>
    <tbody>
        <?php foreach ($liquidaciones as $liquidacion) {  ?>        	
            <tr>
            	<td><?php echo $liquidacion->liqu_id; ?></td>
            	<td><?php echo $liquidacion->liqu_tipocontrato; ?></td>
            	<td><?php echo $liquidacion->liqu_nit; ?></td>
            	<td><?php echo $liquidacion->liqu_nombrecontratista; ?></td>
            	<td><?php echo $liquidacion->liqu_valortotal; ?></td>
            	<td><?php echo $liquidacion->estampillas; ?></td>            	
            </tr>
        <?php }?>	
    </tbody>       
</table>