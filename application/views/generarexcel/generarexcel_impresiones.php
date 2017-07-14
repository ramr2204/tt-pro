<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            generarexcel_impresiones.php
*   Ruta:              /application/views/generarexcel/generarexcel_impresiones.php
*   Descripcion:       contiene la estructura del excel del listado de impresiones del día
*   Fecha Creacion:    15/sep/2015
*   @author           Mike Ortiz <michael.ortiz@turrisystem.com>
*   @version          2015-09-15
*
*/

?>
<p><h1 style="text-align: center;">Sistema Estampillas-Pro, Impresiones <?php echo $fecha; ?></h1></p>

<table border="1" style="text-align:center;">
    <thead>
        <tr>
            <th rowspan="2" style="width:10mm;"><strong>No</strong></th>
            <th rowspan="2" style="width:20mm;text-align:center;"><strong>Tipo Liquidacion</strong></th>
            <th rowspan="2" style="width:15mm;"><strong>No Acto</strong></th>
            <th rowspan="2" style="width:40mm;"><strong>Contratista / NIT</strong></th>
            <th rowspan="2" style="width:20mm;"><strong>Fecha Liquidacion</strong></th>            
            <th rowspan="2" style="width:27mm;"><strong>Valor Acto</strong></th>            
            <th colspan="5" style="width:102mm;"><strong>Estampillas</strong></th> 
            <?php 
            /*
            * Valida si el usuario autenticado es administrador para
            * renderizar la informacion del liquidador que generó
            * la impresion
            */
            if($this->ion_auth->is_admin())
            {
                echo '<th rowspan="2" style="width:20mm;"><strong>Liquidador</strong></th>';
            }

            /*
            * Se crea variable para almacenar el total de las estampillas
            */
            $totalEstampillas = 0;
            ?>
            <th rowspan="2" style="width:20mm;"><strong>Total</strong></th>                                                    
        </tr>
        <tr>
            <th style="text-align:center; width:26mm;"><strong>Tipo</strong></th>
            <th style="text-align:center; width:20mm;"><strong>Fecha Pago</strong></th>
            <th style="text-align:center; width:20mm;"><strong>Valor</strong></th>
            <th style="text-align:center; width:16mm;"><strong>Numero Rotulo</strong></th>
            <th style="text-align:center; width:20mm;"><strong>Fecha Impresion</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $n = 0;
            foreach ($liquidaciones as $liquidacion) 
            {
                $n++; 
                $cantRowspan = $liquidacion->cantEstampillas;
                $totalEstampillas += $liquidacion->cantEstampillas;
                /*
                * Valida si la fila a imprimir no es la primera
                * para aumentar el valor de las filas anidadas en 1
                * para el titulo de los datos de las estampillas
                */
                if($n>1)
                {
                    $cantRowspan++;
                }
        ?>        	
                <tr>
            	    <td rowspan="<?php echo $cantRowspan;?>" style="width:10mm;"><?php echo $n; ?></td>
            	    <td rowspan="<?php echo $cantRowspan;?>" style="width:20mm;"><?php echo $liquidacion->liqu_tipocontrato; ?></td>
                    <td rowspan="<?php echo $cantRowspan;?>" style="width:15mm;"><?php echo $liquidacion->numActo; ?></td>
            	    <td rowspan="<?php echo $cantRowspan;?>" style="width:40mm;"><?php echo ucwords(utf8_decode($liquidacion->liqu_nombrecontratista)); ?><br><?php echo $liquidacion->liqu_nit;?></td>
                    <td rowspan="<?php echo $cantRowspan;?>" style="width:20mm;"><?php echo $liquidacion->liqu_fecha; ?></td>                
                    <td rowspan="<?php echo $cantRowspan;?>" style="width:27mm;"><?php echo $liquidacion->valorActo; ?></td>                
                <?php 
                /*
                * Valida si la fila a imprimir no es la primera
                * para imprimir la fila de los titulos de los datos
                * de las estampillas
                */
                if($n>1)
                {
                ?>
                    <!-- Titulos de las estampillas -->
                    <td style="text-align:center; width:26mm;"><strong>Tipo</strong></td>
                    <td style="text-align:center; width:20mm;"><strong>Fecha Pago</strong></td>
                    <td style="text-align:center; width:20mm;"><strong>Valor</strong></td>
                    <td style="text-align:center; width:16mm;"><strong>Numero Rotulo</strong></td>
                    <td style="text-align:center; width:20mm;"><strong>Fecha Impresion</strong></td>
                <?php 
                }else
                    {
                        /*
                        * Si es la primer fila imprime los datos de la estampilla solamente
                        */
                        imprimirInformacionEstampilla($liquidacion->estampillas[0]);
                        /*
                        * Elimina el vector de la estampilla ya impresa
                        */
                        unset($liquidacion->estampillas[0]);
                    }

                /*
                * Valida si el usuario autenticado es administrador para
                * renderizar la informacion del liquidador que generó
                * la impresion
                */
                if($this->ion_auth->is_admin())
                {
                    echo '<td rowspan="'.$cantRowspan.'" style="text-align:left; width:20mm;">'.utf8_decode($liquidacion->liquidador).'</td>';
                }
                           
                echo '<td rowspan="'.$cantRowspan.'" style="width:20mm;"><br>'.$liquidacion->liqu_valortotal.'</td>'
                    .'</tr>';
                ?>

                <!-- Datos de las Estampillas -->
                <?php            
                foreach ($liquidacion->estampillas as $estampilla) 
                {                                                                           
                    echo '<tr>';
                    imprimirInformacionEstampilla($estampilla);
                    echo '</tr>';                        
                }                
            }   
                ?>            
    </tbody>       
</table>

<br><br>
<table border="1">
    <tbody>
    <tr>
        <td style="width:214mm;"><strong> CANTIDAD TOTAL ESTAMPILLAS</strong></td>                 
        <td style="width:20mm;text-align:center;"><strong><?php echo number_format($totalEstampillas,0,',','.'); ?></strong></td>
    </tr>
    <tr>
        <td style="width:214mm;"><strong> TOTAL RECAUDADO</strong></td>                 
        <td style="width:20mm;text-align:center;">$ <strong><?php echo number_format($totalRecaudado,0,',','.'); ?></strong></td>
    </tr>
    </tbody>
</table>

<?php
/*
* Funcion de apoyo que imprime la informacion de la estampilla especificada
*/
function imprimirInformacionEstampilla($vectorEstampilla)
{
    echo '<td style="text-align:left; width:26mm;">'.$vectorEstampilla['tipo'].'</td>'
        .'<td style="text-align:left; width:20mm;">'.$vectorEstampilla['fecha_pago'].'</td>'
        .'<td style="text-align:right; width:20mm;">'.round($vectorEstampilla['valor']).'</td>'
        .'<td style="text-align:center; width:16mm;">'.$vectorEstampilla['rotulo'].'</td>'
        .'<td style="text-align:left; width:20mm;">'.$vectorEstampilla['fecha_impr'].'</td>';
}
?>