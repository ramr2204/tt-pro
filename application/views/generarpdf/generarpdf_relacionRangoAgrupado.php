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
    'plantilla_inicio'     => '<table border="1" style="text-align:center;"><thead><tr>',
    'plantilla_fin'        => '<tbody></table>',
    'titulos_grupos'       => '',
    'str_tablas_completas' => ''
    );
foreach($resultados['vec_estampillas'] as $objInformacion)
{
    $vecStrTabla = concatenarStrTabla($vecStrTabla, $objInformacion, $resultados['cant_agrupacion']);
    echo'<pre>';print_r($vecStrTabla);echo'</pre>';exit();
}

/*
* Función de apoyo que concatena al string de la tabla html
* las filas titulo o de información dependiendo del contenido
*/
function concatenarStrTabla($vecStrTabla, $objAgrupacion, $cantAgrupacion)
{echo'<pre>';print_r($objAgrupacion);echo'</pre>';
    if(property_exists($objAgrupacion, 'nombre_titulo'))
    {
        $vecStrTabla['titulos_grupos'] .= '<th>'. $objAgrupacion->nombre_titulo .'</th>';
        $vecStrTabla = concatenarStrTabla($vecStrTabla, $objAgrupacion->datos, $cantAgrupacion);
    }else
        {
            /*
            * Valida si es una agrupación para iterar en las agrupaciones
            */
            foreach($objAgrupacion as $objTestear)
            {
                if(!property_exists($objTestear, 'nombre_estampilla'))
                {
                    $vecStrTabla['titulos_grupos'] .= '<th>'. $objTestear->nombre_titulo .'</th>';
                    $vecStrTabla = concatenarStrTabla($vecStrTabla, $objTestear->datos, $cantAgrupacion);
                }else
                    {
                        /*
                        * Determina la extensión de las celdas en los titulos
                        */
                        $expansion_celda1 = 0;
                        if($cantAgrupacion > 3)
                        {
                            $expansion_celda1 = (int)$cantAgrupacion - 2;
                        }

                        $strTemporal = $vecStrTabla['plantilla_inicio'].$vecStrTabla['titulos_grupos']
                            .'</tr>'
                            .'<tr>'
                                .'<th colspan="'. $expansion_celda1 .'">Tipo Estampilla</th>'
                                .'<th>Cantidad</th>'
                                .'<th>Valor</th>'
                            .'</tr>'
                            .'</thead>'
                            .'<tbody>';
            
                        foreach($objAgrupacion as $objEstampilla)
                        {
                            $strTemporal .= '<tr>'
                                    .'<td colspan="'. $expansion_celda1 .'">'. $objEstampilla->nombre_estampilla .'</td>'
                                    .'<td>'. $objEstampilla->cant_estampilla .'</td>'
                                    .'<td>'. $objEstampilla->valor_estampilla .'</td>'
                                .'</tr>';
                        }
                        
                        $strTemporal .= $vecStrTabla['plantilla_fin'];

                        $vecStrTabla['str_tablas_completas'] .= $strTemporal;
                    }
            }
        }
    return $vecStrTabla;
}
?>
<p><h1 style="text-align: center;">Sistema Estampillas-Pro, Impresiones <?php echo $fecha; ?></h1></p>
<table border="1" style="text-align:center;">
    <thead>
        <tr>
        <?php
            /*
            * Construye la cabecera con
            */
        ?>
        </tr>
    </thead>
    <tbody>
       
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
    echo '<td style="text-align:left; width:26mm;">'. $vectorEstampilla['tipo'] .'</td>'
        .'<td style="text-align:left; width:20mm;">'. $vectorEstampilla['fecha_pago'] .'</td>'
        .'<td style="text-align:right; width:20mm;">'. number_format(round($vectorEstampilla['valor']),0,',','.') .'</td>'
        .'<td style="text-align:center; width:16mm;">'. $vectorEstampilla['rotulo'] .'</td>'
        .'<td style="text-align:left; width:20mm;">'. $vectorEstampilla['fecha_impr'] .'</td>';
}
?>