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
                        font-size:9;"><br><br>GOBERNACIÓN DEL TOLIMA <br>SECRETARIA DE HACIENDA<br>DIRECCION FINANCIERA DE RENTAS E INGRESOS</td>

                    <td style="height: 19mm; width: 26mm; 
                        border-bottom: 0.5px solid black;"><br><br><img id="logo_gobernador" src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima2.jpg" style="height: 13mm; width: 25mm;" ></td>
     
        </tr>
    </tbody>
</table>
    
<table><tr><td style="height: 12mm; color:white">espaciador</td></tr></table>


<p>Ibagué <?php echo date('Y-m-d'); ?></p>

<table><tr><td style="height: 2mm; color:white">espaciador</td></tr></table>

<p>
Doctora<br>
<b>LUZ MERY HERRERA RODRIGUEZ<b><br>
Directora Financiera de Tesorería<br>
Gobernación del Tolima<br>
Ibagué<br>
</p>


   
<p class="text-center">
<b>RELACION ENTREGA DE ESTAMPILLAS</b><br>
(MARTES 20  DE ENERO DE 2015)
</p>


<p>De conformidad con lo establecido en la circular 010 del 4 de mayo de 2014 </p>
<table>            
    <tbody> 
                 
        <tr>
            <td class="text-center" style="height: 5mm; width:50mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">Tipo Estampilla</td>
            <td class="text-center" style="height: 5mm; width:30mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">Nro. Consignación</td>
            <td class="text-center" style="height: 5mm; width:30mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">Valor en $</td>
            <td class="text-center" style="height: 5mm; width:40mm;border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">Observaciones</td>            
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

<br>HEBERTH MESA APONTE<br>
OPERARIO TTI<br>
THOMAS GREG & SONS DE COLOMBIA S.A.<br>
PROYECTO ESTAMPILLAS PRO<br>

<table><tr><td style="height: 20mm; color:white">espaciador</td></tr></table>

<div class="text-center" style="font-size:8px;">
<br>
“Unidos por la Grandeza del Tolima”<br>
Gobernación del Tolima<br>
www.tolima.gov.co
</div>