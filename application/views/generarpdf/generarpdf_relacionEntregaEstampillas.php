<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            generarpdf_relacionEntregaEstampillas.php
*   Ruta:              /application/views/generarpdf/generarpdf_relacionEntregaEstampillas.php
*   Descripcion:       contiene la estructura del pdf del listado de impresiones del día
*   Fecha Creacion:    22/ene/2015
*   @author           Mike Ortiz <michael.ortiz@turrisystem.com>
*   @version          2015-01-13
*
*/
?>

<style type="text/css">
    .text-center
    {
        text-align: center;
    }

    .text-left
    {
        text-align: left;
    }

    .text-right
    {
        text-align: right;
    }
</style>


<table>            
    <tbody>
        <tr>
                    <td class="text-left" style="height: 19mm; width: 20mm;
                        border-bottom: 0.5px solid black;"><img id="logo_gobernacion" src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima1.jpg" style="height: 15mm; width: 20mm;"></td>

                    <td class="text-center" id="leyenda_encabezado" colspan="2" style="height: 
                        19mm;width:110mm;                          
                        border-bottom: 0.5px solid black;
                        font-size:9;"><br><br>GOBERNACIÓN DEL TOLIMA <br><span style="font-size:8px;">SECRETARIA DE HACIENDA<br>DIRECCION FINANCIERA DE RENTAS E INGRESOS</span></td>

                    <td style="height: 19mm; width: 26mm; 
                        border-bottom: 0.5px solid black;"><br><br><img id="logo_gobernador" src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima2.png" style="height: 13mm; width: 25mm;" ></td>
     
        </tr>
    </tbody>
</table>
    
<table><tr><td style="height: 12mm; color:white">espaciador</td></tr></table>










<p>Ibagué, <?php 

$diaNumero = date('d');
$diaNombre = date('l');
$mesNumero = date('m');

switch ($diaNombre) 
{
    case 'Sunday': $diaNombre = 'Domingo';        
        break;

    case 'Monday': $diaNombre = 'Lunes';        
        break;

    case 'Tuesday': $diaNombre = 'Martes';        
        break;

    case 'Wednesday': $diaNombre = 'Miercoles';        
        break;

    case 'Thursday': $diaNombre = 'Jueves';        
        break;

    case 'Friday': $diaNombre = 'Viernes';        
        break;

    case 'Saturday': $diaNombre = 'Sabado';        
        break;
        
}

switch ($mesNumero) 
{
    case '01': $mesNombre = 'Enero';        
        break;

    case '02': $mesNombre = 'Febrero';        
        break;

    case '03': $mesNombre = 'Marzo';        
        break;

    case '04': $mesNombre = 'Abril';        
        break;

    case '05': $mesNombre = 'Mayo';        
        break;

    case '06': $mesNombre = 'Junio';        
        break;

    case '07': $mesNombre = 'Julio';        
        break;

    case '08': $mesNombre = 'Agosto';        
        break;

    case '09': $mesNombre = 'Septiembre';        
        break;

    case '10': $mesNombre = 'Octubre';        
        break;

    case '11': $mesNombre = 'Noviembre';        
        break;

    case '12': $mesNombre = 'Diciembre';        
        break;
        
}

//separa la fecha en un arreglo segun la expresion regular
preg_match('/(\d{4})-(\d{2})-(\d{2})/',$fecha,$partes);

$diaNumeroImpresiones = $partes[3];
$diaNombreImpresiones = date('l',strtotime($fecha));
$mesNumeroImpresiones = $partes[2];
$anioNumeroImpresiones = $partes[1];

switch ($diaNombreImpresiones) 
{
    case 'Sunday': $diaNombreImpresiones = 'Domingo';        
        break;

    case 'Monday': $diaNombreImpresiones = 'Lunes';        
        break;

    case 'Tuesday': $diaNombreImpresiones = 'Martes';        
        break;

    case 'Wednesday': $diaNombreImpresiones = 'Miercoles';        
        break;

    case 'Thursday': $diaNombreImpresiones = 'Jueves';        
        break;

    case 'Friday': $diaNombreImpresiones = 'Viernes';        
        break;

    case 'Saturday': $diaNombreImpresiones = 'Sabado';        
        break;
        
}

switch ($mesNumeroImpresiones) 
{
    case '01': $mesNombreImpresiones = 'Enero';        
        break;

    case '02': $mesNombreImpresiones = 'Febrero';        
        break;

    case '03': $mesNombreImpresiones = 'Marzo';        
        break;

    case '04': $mesNombreImpresiones = 'Abril';        
        break;

    case '05': $mesNombreImpresiones = 'Mayo';        
        break;

    case '06': $mesNombreImpresiones = 'Junio';        
        break;

    case '07': $mesNombreImpresiones = 'Julio';        
        break;

    case '08': $mesNombreImpresiones = 'Agosto';        
        break;

    case '09': $mesNombreImpresiones = 'Septiembre';        
        break;

    case '10': $mesNombreImpresiones = 'Octubre';        
        break;

    case '11': $mesNombreImpresiones = 'Noviembre';        
        break;

    case '12': $mesNombreImpresiones = 'Diciembre';        
        break;
        
}


$fechaNombres = $diaNombreImpresiones.' '.$diaNumeroImpresiones.' de '.$mesNombreImpresiones.' de '.$anioNumeroImpresiones;

echo  strtoupper($diaNumero.' de '.$mesNombre.' de '.date('Y'));


?></p>

<table><tr><td style="height: 2mm; color:white">espaciador</td></tr></table>

<p>
Doctora<br>
<b>YENNY MILENA GONZALEZ CRUZ</b><br>
Directora de Rentas<br>
Gobernación del Tolima<br>
Ibagué<br>
</p>


   
<p class="text-center">
<b>RELACION ENTREGA DE ESTAMPILLAS</b><br>
(<?php echo strtoupper($fechaNombres);?>)
</p>


<p>De conformidad con lo establecido en la circular 010 del 4 de mayo de 2014 </p>
<table>            
    <tbody> 
                 
        <tr>
            <td class="text-center" style="height: 5mm; width:50mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><b>Tipo Estampilla</b></td>
            <td class="text-center" style="height: 5mm; width:30mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><b>Nro. Consignación</b></td>
            <td class="text-center" style="height: 5mm; width:30mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><b>Valor en $</b></td>
            <td class="text-center" style="height: 5mm; width:40mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><b>Observaciones</b></td>            
        </tr>
        <?php $n=0;
        foreach ($liquidaciones as $liquidacion) {
        $n++;  
        ?>        	
            <tr>
            	<td  style="height: 5mm; width:50mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"> <?php echo $liquidacion['nombre']; ?></td>
            	<td class="text-center" style="height: 5mm; width:30mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">EFECTIVO</td>
            	<td class="text-right" style="height: 5mm; width:30mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><?php echo $liquidacion['valor'];?><span style="color:white;">..</span></td>
            	<td  style="height: 5mm; width:40mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"></td>            	            	                 
            </tr>
        <?php }?>	
    </tbody>       
</table>

<?php 
    //Según la cantidad de tipos de estampillas 
    //establece los espacios a dejar
    switch ($n) 
    {
        case 1: $n = 6;            
        break;
        
        case 2: $n = 5;            
        break;

        case 3: $n = 4;            
        break;

        case 4: $n = 3;            
        break;

        case 5: $n = 2;            
        break;
        
        case 6: $n = 1;            
        break;

        default : $n = 1;            
        break;
       
    
      }

      for ($i=1; $i <=$n ; $i++) 
      { 
          echo '<table><tr><td style="height: 12mm; color:white">espaciador</td></tr></table>';
      }
?>


Cordial saludo,

<table><tr><td style="height: 15mm; color:white">espaciador</td></tr></table>
<table><tr><td style="height: 15mm; color:white">espaciador</td></tr></table>

<br><?php echo strtoupper($usuario); ?><br>
OPERARIO TTI<br>
THOMAS GREG & SONS DE COLOMBIA S.A.<br>
PROYECTO ESTAMPILLAS PRO<br>

<table><tr><td style="height: 20mm; color:white">espaciador</td></tr></table>

<div class="text-center" style="font-size:8px;">
<br>
“Soluciones que Transforman”<br>
Gobernación del Tolima<br>
www.tolima.gov.co
</div>