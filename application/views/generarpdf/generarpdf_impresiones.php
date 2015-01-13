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
<p><h1 style="text-align: center;">Sistema Estampillas-Pro, Impresiones <?php echo $liquidaciones[0]->liqu_fecha; ?></h1></p>

<table border="1" style="text-align:center;">
    <thead>
        <tr>
            <th>Id</th>
            <th>Tipo Liquidación</th>
            <th>NIT</th>
            <th>Contratista</th>
            <th>Estampillas</th> 
            <th>Total</th>                                                    
        </tr>
    </thead>
    <tbody>
        <?php foreach ($liquidaciones as $liquidacion) {  ?>        	
            <tr>
            	<td><?php echo $liquidacion->liqu_id; ?></td>
            	<td><?php echo $liquidacion->liqu_tipocontrato; ?></td>
            	<td><?php echo $liquidacion->liqu_nit; ?></td>
            	<td><?php echo $liquidacion->liqu_nombrecontratista; ?></td>            	
            	<td><?php echo $liquidacion->estampillas; ?></td>           
            	<td><br><?php echo $liquidacion->liqu_valortotal; ?></td> 	
            </tr>
        <?php }?>	
    </tbody>       
</table>