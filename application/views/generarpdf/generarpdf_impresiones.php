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
            <th rowspan="2" style="width:10mm;">No</th>
            <th rowspan="2" style="width:20mm;text-align:center;">Tipo Liquidación</th>
            <th rowspan="2" style="width:15mm;">No Acto</th>
            <th rowspan="2" style="width:40mm;">Contratista / NIT</th>
            <th rowspan="2" style="width:20mm;">Fecha Liquidacion</th>            
            <th rowspan="2" style="width:27mm;">Valor Acto</th>            
            <th colspan="5" style="width:102mm;">Estampillas</th> 
            <?php 
            /*
            * Valida si el usuario autenticado es administrador para
            * renderizar la informacion del liquidador que generó
            * la impresion
            */
            if($this->ion_auth->is_admin())
            {
                echo '<th rowspan="2" style="width:20mm;">Liquidador</th>';
            }
            ?>
            <th rowspan="2" style="width:20mm;">Total</th>                                                    
        </tr>
        <tr>
            <th style="text-align:center; width:26mm;">Tipo</th>
            <th style="text-align:center; width:20mm;">Fecha Pago</th>
            <th style="text-align:center; width:20mm;">Valor</th>
            <th style="text-align:center; width:16mm;">Numero Rotulo</th>
            <th style="text-align:center; width:20mm;">Fecha Impresion</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $n = 0;
            foreach ($liquidaciones as $liquidacion) 
            {  
                $n++; 
        ?>        	
                <tr>
            	    <td rowspan="<?php echo $liquidacion->cantEstampillas;?>" style="width:10mm;"><?php echo $n; ?></td>
            	    <td rowspan="<?php echo $liquidacion->cantEstampillas;?>" style="width:20mm;"><?php echo $liquidacion->liqu_tipocontrato; ?></td>
                    <td rowspan="<?php echo $liquidacion->cantEstampillas;?>" style="width:15mm;"><?php echo $liquidacion->numActo; ?></td>
            	    <td rowspan="<?php echo $liquidacion->cantEstampillas;?>" style="width:40mm;"><?php echo ucwords($liquidacion->liqu_nombrecontratista); ?><br><?php echo $liquidacion->liqu_nit;?></td>
                    <td rowspan="<?php echo $liquidacion->cantEstampillas;?>" style="width:20mm;"><?php echo $liquidacion->liqu_fecha; ?></td>                
                    <td rowspan="<?php echo $liquidacion->cantEstampillas;?>" style="width:27mm;"><?php echo $liquidacion->valorActo; ?></td>  
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
                    <td rowspan="<?php echo $liquidacion->cantEstampillas;?>" style="width:20mm;"><br><?php echo $liquidacion->liqu_valortotal; ?></td>     
                </tr>
                <!-- Datos de las Estampillas -->
                <?php            
                foreach ($liquidacion->estampillas as $estampilla) 
                {              
                ?> 
                    <tr>
            	        <td style="text-align:left; width:26mm;"><?php echo $estampilla['tipo']; ?></td>
                        <td style="text-align:left; width:20mm;"><?php echo $estampilla['fecha_pago']; ?></td>
                        <td style="text-align:right; width:20mm;"><?php echo round($estampilla['valor']); ?></td>
                        <td style="text-align:center; width:16mm;"><?php echo $estampilla['rotulo']; ?></td>
                        <td style="text-align:left; width:20mm;"><?php echo $estampilla['fecha_impr']; ?></td>                    
                    </tr>
                <?php
                }                
            }?>	
    </tbody>       
</table>