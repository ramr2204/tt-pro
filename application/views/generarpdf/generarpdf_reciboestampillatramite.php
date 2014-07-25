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

<style type="text/css">
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
        padding-right: 1px;
    }
    #tablaq {
        margin-top: 3px;

    }

</style>

      <table class="table table-striped table-bordered " id="tablaq" border="1" cellpadding="1">
 <thead>
    <tr>
     <th colspan="1" class="text-center small">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima1.jpg" height="50" width="40" >
     </th>
     <th colspan="2" class="text-center small">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" height="50" width="90" >
     </th>
   </tr>
 </thead>
 <tbody>
   
<tr>
     <td colspan="1"><strong>Nombre </strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?>
     </td>
</tr>

 <tr>
     <td colspan="1"><strong>Documento</strong></td>
     <td colspan="3"><?php echo $result->liqu_nit; ?></td>
     
</tr>

<tr>
     <td colspan="4"><strong></strong></td>
</tr>
<tr>
     <td colspan="1"><strong>Base</strong></td>
     <td colspan="3"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?>
     </td>
</tr>
<tr>
     <td colspan="4"><strong></strong></td>
</tr>
<tr>
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta de ahorro</strong></td>
     <td colspan="1" class="text-center"><strong>Porcentaje</strong></td>
     <td colspan="1" class="text-center"><strong>Valor</strong></td>

</tr>

<tr>
     <td colspan="1"><?php echo $facturaestampilla->fact_nombre; ?>

     </td>
     <td colspan="1" class="text-center"><?php echo $facturaestampilla->fact_cuenta; ?><br><?php echo $facturaestampilla->fact_banco; ?>

     </td>
     <td colspan="1" class="text-center"><?php echo $facturaestampilla->fact_porcentaje; ?>%
     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($facturaestampilla->fact_valor, 2, ',', '.'); ?>
    
     </td>
</tr>



<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>


     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($facturaestampilla->fact_valor, 2, ',', '.'); ?>
     </td>
</tr>
 </tbody>     
 <tfoot>
   <tr>
     <th colspan="2" class="text-center">
     <small> "Unidos por la grandeza del Tolima"<br>
      Edificio de la Gobernación del Tolima, carrera 3 calle 10 y 11, 9 piso <br>
      Teléfonos 2610758 - 2611111 -Ext. 209 - 305<br>
      dcontratos@outlook.com </small> 
     </th>
     <th colspan="2" class="text-center">
      <tcpdf method="write1DBarcode" params="<?php echo $params; ?>" />
     </th>
   </tr>
 </tfoot>
</table>



<div class="separador"></div>






      <table class="table table-striped table-bordered " id="tablaq" border="1">
 <thead>
    <tr>
     <th colspan="1" class="text-center small">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima1.jpg" height="50" width="40" >
     </th>
     <th colspan="2" class="text-center small">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" height="50" width="90" >
     </th>
   </tr>
 </thead>
 <tbody>
 
<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?>
     </td>
</tr>

<tr>
     <td colspan="1"><strong>Documento</strong></td>
     <td colspan="3"><?php echo $result->liqu_nit; ?></td>
</tr>
<tr>
     <td colspan="4"><strong></strong></td>
</tr>
<tr>
     <td colspan="1"><strong>Valor base</strong></td>
     <td colspan="1"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?>
     </td>
</tr>
<tr>
     <td colspan="4"><strong></strong></td>
</tr>
<tr>
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta de ahorro</strong></td>
     <td colspan="1" class="text-center"><strong>Porcentaje</strong></td>
     <td colspan="1" class="text-center"><strong>Valor</strong></td>

</tr>

<tr>
     <td colspan="1"><?php echo $facturaestampilla->fact_nombre; ?>

     </td>
     <td colspan="1" class="text-center"><?php echo $facturaestampilla->fact_cuenta; ?><br><?php echo $facturaestampilla->fact_banco; ?>

     </td>
     <td colspan="1" class="text-center"><?php echo $facturaestampilla->fact_porcentaje; ?>%
     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($facturaestampilla->fact_valor, 2, ',', '.'); ?>
    
     </td>
</tr>



<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>


     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($facturaestampilla->fact_valor, 2, ',', '.'); ?>
     </td>
</tr>
 </tbody>     
 <tfoot>
   <tr>
     <th colspan="2" class="text-center">
     <small> "Unidos por la grandeza del Tolima"<br>
      Edificio de la Gobernación del Tolima, carrera 3 calle 10 y 11, 9 piso <br>
      Teléfonos 2610758 - 2611111 -Ext. 209 - 305<br>
      dcontratos@outlook.com </small> 
     </th>
     <th colspan="2" class="text-center">
      <tcpdf method="write1DBarcode" params="<?php echo $params; ?>" />
     </th>
   </tr>
 </tfoot>
</table>






<div class="separador"></div>



      <table class="table table-striped table-bordered " id="tablaq" border="1">
 <thead>
    <tr>
     <th colspan="1" class="text-center small">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima1.jpg" height="50" width="40" >
     </th>
     <th colspan="2" class="text-center small">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" height="50" width="90" >
     </th>
   </tr>
 </thead>
 <tbody>
   
<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?>
     </td>
</tr>

<tr>
     <td colspan="1"><strong>Documento</strong></td>
     <td colspan="3"><?php echo $result->liqu_nit; ?></td>
</tr>
<tr>
     <td colspan="4"><strong></strong></td>
</tr>
<tr>
     <td colspan="1"><strong>Valor base</strong></td>
     <td colspan="3"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?>
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_tipocontrato; ?></td>
     <td colspan="2" class="text-center"><strong>Régimen <?php echo $result->liqu_regimen; ?></strong></td>
</tr>
<tr>
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta de ahorro</strong></td>
     <td colspan="1" class="text-center"><strong>Porcentaje</strong></td>
     <td colspan="1" class="text-center"><strong>Valor</strong>
     </td>

</tr>

<tr>
     <td colspan="1"><?php echo $facturaestampilla->fact_nombre; ?>

     </td>
     <td colspan="1" class="text-center"><?php echo $facturaestampilla->fact_cuenta; ?><br><?php echo $facturaestampilla->fact_banco; ?>

     </td>
     <td colspan="1" class="text-center"><?php echo $facturaestampilla->fact_porcentaje; ?>%
     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($facturaestampilla->fact_valor, 2, ',', '.'); ?>
    
     </td>
</tr>



<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>


     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($facturaestampilla->fact_valor, 2, ',', '.'); ?>
     </td>
</tr>
 </tbody>     
 <tfoot>
   <tr>
     <th colspan="2" class="text-center">
     <small> "Unidos por la grandeza del Tolima"<br>
      Edificio de la Gobernación del Tolima, carrera 3 calle 10 y 11, 9 piso <br>
      Teléfonos 2610758 - 2611111 -Ext. 209 - 305<br>
      dcontratos@outlook.com </small> 
     </th>
     <th colspan="2" class="text-center">
      <tcpdf method="write1DBarcode" params="<?php echo $params; ?>" />
     </th>
     
   </tr>
 </tfoot>
</table>
