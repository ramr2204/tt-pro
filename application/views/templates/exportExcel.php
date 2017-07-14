<?php
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            generarexcel_impresiones.php
*   Ruta:              /application/views/generarexcel/generarexcel_impresiones.php
*   Descripcion:       contiene la estructura del excel del listado de impresiones del día
*   Fecha Creacion:    15/sep/2015
*   @author           Mike Ortiz <michael.ortiz@turrisystem.com>
*   @version          2015-09-15
*
*/
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Impresiones_". str_replace(' ', '_', $_SESSION['fecha_informe_excel']) .".xls");
header("Pragma: no-cache");
header("Expires: 0");
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

echo $contents; 
?>