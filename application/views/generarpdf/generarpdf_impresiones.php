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
            <th style="width:10mm;">No</th>
            <th style="width:20mm;">Tipo Liquidación</th>
            <th style="width:25mm;">NIT</th>
            <th style="width:40mm;">Contratista</th>
            <th style="width:20mm;">Fecha Liquidacion</th>
            <th style="width:25mm;">Concepto</th>
            <th style="width:27mm;">Valor</th>
            <th style="width:20mm;">Fecha Pago</th>
            <th style="width:50mm;">Estampillas</th> 
            <?php 
            /*
            * Valida si el usuario autenticado es administrador para
            * renderizar la informacion del liquidador que generó
            * la impresion
            */
            if($this->ion_auth->is_admin())
            {
                echo '<th style="width:20mm;">Liquidador</th>';
            }
            ?>
            <th style="width:20mm;">Total</th>                                                    
        </tr>
    </thead>
    <tbody>
        <?php
            $n = 0;
            foreach ($liquidaciones as $liquidacion) {  
            $n++; 
        ?>        	
            <tr>
            	<td style="width:10mm;"><?php echo $n; ?></td>
            	<td style="width:20mm;"><?php echo $liquidacion->liqu_tipocontrato; ?></td>
            	<td style="width:25mm;"><?php echo $liquidacion->liqu_nit; ?></td>
            	<td style="width:40mm;"><?php echo $liquidacion->liqu_nombrecontratista; ?></td>
                <td style="width:20mm;"><?php echo $liquidacion->liqu_fecha; ?></td>
                <td style="width:25mm;"><?php echo $liquidacion->fact_nombre; ?></td>
                <td style="width:27mm;"><?php echo $liquidacion->fact_valor; ?></td>
                <td style="width:20mm;"><?php echo $liquidacion->pago_fecha; ?></td>
            	<td style="text-align:left; width:50mm;"><?php echo $liquidacion->estampillas; ?></td>   
                <?php
                /*
                * Valida si el usuario autenticado es administrador para
                * renderizar la informacion del liquidador que generó
                * la impresion
                */
                if($this->ion_auth->is_admin())
                {
                    echo '<th style="text-align:left; width:20mm;">'.$liquidacion->liquidador.'</th>';
                }
                ?>        
            	<td style="width:20mm;"><br><?php echo $liquidacion->liqu_valortotal; ?></td> 	
            </tr>
        <?php }?>	
    </tbody>       
</table>