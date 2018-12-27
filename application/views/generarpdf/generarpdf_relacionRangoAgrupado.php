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
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

$vecStrTabla = array(
    'plantilla_inicio'     => '<table border="1" style="text-align:center;"><thead>',
    'plantilla_fin'        => '<tbody></table>',
    'str_tablas_completas' => ''
    );
foreach($resultados['vec_estampillas'] as $objInformacion)
{
    $vecStrTabla = Liquidaciones::concatenarStrTabla($vecStrTabla, $objInformacion, $resultados['cant_agrupacion']);
}
?>
<p><h1 style="text-align: center;">Sistema Estampillas-Pro, Impresiones <?php echo $resultados['fecha']; ?></h1></p>
<table>            
    <tbody> 
                 
        <tr>
            <td style="height: 5mm; width:160mm;border-top: 0.5px solid black;
                        background-color:#3C3C3C;color:white;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;" colspan="2"><b> Filtros Aplicados</b></td>
        </tr>
        <?php $n = 0;
        foreach ($resultados['vec_filtros'] as $nom_filtro => $valor_filtro) {
            if ($valor_filtro != '') {
                $nom_filtro = ucwords(str_replace('_', ' ', $nom_filtro));
                $valor_filtro = str_replace('Ó', 'ó', $valor_filtro);
                ?>        	
            <tr>
            	<td  style="height: 5mm; width:80mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><b> <?php echo $nom_filtro; ?></b></td>            	
                <td style="height: 5mm; width:80mm;border-top: 0.5px solid black;
                        text-align: center;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"> <?php echo $valor_filtro; ?> </td>
            </tr>
        <?php

    }
}
?>
    </tbody>       
</table>
<br><br>
<?php
    echo $vecStrTabla['str_tablas_completas'];
?>
<table border="1">
    <tbody>
    <tr>
        <td style="width:150mm;"><strong> CANTIDAD TOTAL ESTAMPILLAS</strong></td>                 
        <td style="width:55mm;text-align:center;"><strong><?php echo number_format($resultados['cant_total_estampillas'],0,',','.'); ?></strong></td>
    </tr>
    <tr>
        <td style="width:150mm;"><strong> TOTAL RECAUDADO</strong></td>                 
        <td style="width:55mm;text-align:center;">$ <strong><?php echo number_format($resultados['total_recaudado'],0,',','.'); ?></strong></td>
    </tr>
    </tbody>
</table>