<?php
/**
 * exportar_excel.php
 * Ruta:              /ttibolivar/vistas/informes/exportar_excel.php
 * Fecha Creación:    21/Jun/2019
 *
 * Interfaz para generar el archivo excel
 *
 * @author           David Mahecha <david.mahecha@turrisystem.com>
 * @copyright        2019 David Mahecha
 * @license          GPL 2 or later
 * @version          2019-06-21
 *
*/

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: filename=". $datos_vista['nomArchivo'] .".xls");
echo "
    <html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">
    <html>
        <head><meta http-equiv=\"Content-type\" content=\"text/html;charset=utf-8\" /></head>
        <body>
";

if(isset($datos_vista['registros']))
{
    echo '<table style="border: 1px solid #ddd;">'
        .'<tr><th style="border: 1px solid #ddd;" colspan="'. count($datos_vista['encabezado']) .'" class="text-center">'.$datos_vista['tituloTabla'].'</th></tr>'
        .'<tr>';

    foreach($datos_vista['encabezado'] as $datos_vista['titulo'])
    {
        echo '<th style="border: 1px solid #ddd;" class="text-center">'. $datos_vista['titulo'] .'</th>';
    }

    echo '</tr>';
    foreach($datos_vista['registros'] as $datos_vista['fila'])
    {
        echo '<tr>';

        foreach($datos_vista['fila'] as $datos_vista['campo'] => $datos_vista['celda'])
        {
            // Comprueba si la columna se muestra o no
            if(!in_array($datos_vista['campo'], $datos_vista['columnasOcultar']))
            {
                // Valida si a la columna se le aplica un formato especifico
                if(isset($datos_vista['formateoColumnas'][$datos_vista['campo']])){
                    $datos_vista['result'] = $datos_vista['formateoColumnas'][$datos_vista['campo']]('', $datos_vista['fila']);
                }else{
                    $datos_vista['result'] = $datos_vista['celda'];
                }
                
                if(!empty($datos_vista['condicionAdicional']))
                {
                    if($datos_vista['campo'] == $datos_vista['condicionAdicional']['campo'])
                    {
                        if($datos_vista['celda'] == $datos_vista['condicionAdicional']['comparar'])
                        {
                            $datos_vista['result'] = $datos_vista['condicionAdicional']['condicionIf'];
                        }
                        else
                        {
                            $datos_vista['result'] = $datos_vista['condicionAdicional']['condicionElse'];
                        }
                    }
                }
                echo '<td style=`mso-number-format:"@";border: 1px solid #ddd; ` class=`text-center`>'. $datos_vista['result'] . '&nbsp' .'</td>';
            }
        }
        echo '</tr>';
    }

    echo '</table>';
}
echo "</body></html>";