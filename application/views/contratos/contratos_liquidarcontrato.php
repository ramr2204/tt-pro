<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratos/contratos_list.php
*   Descripcion:       tabla que mustra todos los contratos existentes
*   Fecha Creacion:    20/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-20
*
*/
?>

<div class="row"> 
 <div class="col-sm-12">    
   
    <div class="table-responsive">
      <table class="table table-striped table-bordered " id="tablaq">
 <thead>
    <tr>
     
     <th colspan="5" class="text-center small">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
   
   </tr>
 </thead>
 <tbody>
   
 <tr>
     <td colspan="5"><h1>Liquidación de contrato</h1></td>
</tr>
<tr>
     
     <td colspan="5"></td>
</tr>
<tr>
     <td colspan="2">Nombre del contratista</td>
     <td colspan="3"><?php echo $result->cont_nombre; ?></td>
</tr>

 <tr>
     <td colspan="2">C.C. o NIT</td>
     <td colspan="3"><?php echo $result->cont_nit; ?></td>
</tr>

<tr>
     <td colspan="2">Número de contrato</td>
     <td colspan="3"><?php echo $result->cntr_numero; ?></td>
</tr>
<tr>
     <td colspan="2">Tipo de contrato</td>
     <td colspan="3"><?php echo $result->tico_nombre; ?></td>
</tr>
<tr>
     <td colspan="2">Tipo de contratista</td>
     <td colspan="3"></td>
</tr>
<tr>
     <td colspan="2">Régimen tributario</td>
     <td colspan="3"><?php echo $result->regi_nombre; ?></td>
</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Ctas.de ahorro</strong></td>
     <td colspan="1" class="text-center"><strong>Valor del contrato</strong></td>
     <td colspan="1" class="text-center"><strong>Régimen común</strong></td>
     <td colspan="1" class="text-center"><strong>Simplificado</strong></td>
     <td colspan="1" class="text-center"><strong>Exento</strong></td>
</tr>
<tr>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
</tr>
<tr>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
</tr>
<tr>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
</tr>
<tr>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
</tr>
<tr>
     <td colspan="1"><strong>Totales</strong></td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
     <td colspan="1">-</td>
</tr>
 </tbody>     
 <tfoot>
   <tr>
     <th colspan="5" class="text-center">
     <small> "Unidos por la grandeza del Tolima"<br>
      Edificio de la Gobernación del Tolima, carrera 3 calle 10 y 11, 9 piso <br>
      Teléfonos 2610758 - 2611111 -Ext. 209 - 305<br>
      dcontratos@outlook.com </small> 
     </th>
   </tr>
 </tfoot>
</table>
</div>
</div>   
      </div>

