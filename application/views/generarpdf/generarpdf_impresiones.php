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
            <th style="width:15mm;">Id</th>
            <th style="width:30mm;">Tipo Liquidación</th>
            <th style="width:30mm;">NIT</th>
            <th style="width:50mm;">Contratista</th>
            <th style="width:120mm;">Estampillas</th> 
            <th style="width:20mm;">Total</th>                                                    
        </tr>
    </thead>
    <tbody>
        <?php foreach ($liquidaciones as $liquidacion) {  ?>        	
            <tr>
            	<td style="width:15mm;"><?php echo $liquidacion->liqu_id; ?></td>
            	<td style="width:30mm;"><?php echo $liquidacion->liqu_tipocontrato; ?></td>
            	<td style="width:30mm;"><?php echo $liquidacion->liqu_nit; ?></td>
            	<td style="width:50mm;"><?php echo $liquidacion->liqu_nombrecontratista; ?></td>            	
            	<td style="text-align:left; width:120mm;"><?php echo $liquidacion->estampillas; ?></td>           
            	<td style="width:20mm;"><br><?php echo $liquidacion->liqu_valortotal; ?></td> 	
            </tr>
        <?php }?>	
    </tbody>       
</table>