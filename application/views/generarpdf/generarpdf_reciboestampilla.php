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
           <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima1.jpg" height="60" width="70" >
     </th>
     <th colspan="2" class="text-center small">Gobernación del Putumayo <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small">
           <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima2.png" height="50" width="90" >
     </th>
   </tr>
 </thead>
 <tbody>

<tr>
     <td colspan="1"><strong>Numero Factura </strong></td>
     <td colspan="3"> <?php echo $codigodepto.'-'.$facturaestampilla->fact_id; ?></td>
</tr>

<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?>
     </td>
</tr>

 <tr>
     <td colspan="1"><strong>C.C. o NIT</strong></td>
     <td colspan="1"><?php echo $result->liqu_nit; ?></td>
     <td colspan="1"><strong>Tipo de contratista</strong></td>
     <td colspan="1"><?php echo $result->liqu_tipocontratista; ?></td>
</tr>

<tr>
     <td colspan="1"><strong>Número de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_numero; ?> </td>
     <td colspan="1"><strong>Vigencia</strong></td>
     <td colspan="1"><?php echo $result->liqu_vigencia; ?></td>
</tr>
<tr>
     <td colspan="1"><strong>Valor del contrato</strong></td>
     <td colspan="1"><?php echo '$'.number_format($result->liqu_valorconiva, 2, ',', '.'); ?>
     </td>
     <td colspan="1"><strong>Valor sin IVA</strong></td>
     <td colspan="1"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?>
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_tipocontrato; ?></td>
     <td colspan="2" class="text-center"><strong>Régimen <?php echo $result->liqu_regimen; ?></strong></td>
</tr>

<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta Ahorros</strong></td>
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
     <small> "Vale ser legal"<br>
      Calle 8 Número 7-40 <br>
      Teléfonos (57+8) 4206600 Ext. 101 Fax: 4295196<br>
      contactenos@putumayo.gov.co </small> 
     </th>
     <th colspan="2" class="text-center">
     <br><br>

      <!-- <tcpdf method="write1DBarcode" params="<?php echo $params; ?>" /> -->
      <img src="<?php echo $this->config->item('application_root'); ?>application/libraries/barcodegen/<?php echo $codebar ?>.png" width="300" height="40">
      <small><?php echo $codebar; ?></small>
     </th>
   </tr>
 </tfoot>
</table>



<div class="separador"></div>






      <table class="table table-striped table-bordered " id="tablaq" border="1">
 <thead>
    <tr>
     <th colspan="1" class="text-center small">
           <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima1.jpg" height="60" width="70" >
     </th>
     <th colspan="2" class="text-center small">Gobernación del Putumayo <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small">
           <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima2.png" height="50" width="90" >
     </th>
   </tr>
 </thead>
 <tbody>

<tr>
     <td colspan="1"><strong>Numero Factura </strong></td>
     <td colspan="3"> <?php echo $codigodepto.'-'.$facturaestampilla->fact_id; ?></td>
</tr>

<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?>
     </td>
</tr>

<tr>
     <td colspan="1"><strong>C.C. o NIT</strong></td>
     <td colspan="1"><?php echo $result->liqu_nit; ?></td>
     <td colspan="1"><strong>Tipo de contratista</strong></td>
     <td colspan="1"><?php echo $result->liqu_tipocontratista; ?></td>
</tr>
<tr>
     <td colspan="1"><strong>Número de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_numero; ?>
     </td>
     <td colspan="1"><strong>Vigencia</strong></td>
     <td colspan="1"><?php echo $result->liqu_vigencia; ?>
     <input type="hidden" name="vigencia" value="<?php echo $result->liqu_vigencia; ?>">
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Valor del contrato</strong></td>
     <td colspan="1"><?php echo '$'.number_format($result->liqu_valorconiva, 2, ',', '.'); ?>
     </td>
     <td colspan="1"><strong>Valor sin IVA</strong></td>
     <td colspan="1"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?>
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_tipocontrato; ?></td>
     <td colspan="2" class="text-center"><strong>Régimen <?php echo $result->liqu_regimen; ?></strong></td>
</tr>

<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta Ahorros</strong></td>
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
     <small> "Vale ser legal"<br>
      Calle 8 Número 7-40 <br>
      Teléfonos (57+8) 4206600 Ext. 101 Fax: 4295196<br>
      contactenos@putumayo.gov.co </small> 
     </th>
     <th colspan="2" class="text-center">
     <br><br>

      <!-- <tcpdf method="write1DBarcode" params="<?php echo $params; ?>" /> -->
      <img src="<?php echo $this->config->item('application_root'); ?>application/libraries/barcodegen/<?php echo $codebar ?>.png" width="300" height="40">
      <small><?php echo $codebar; ?></small>
     </th>
   </tr>
 </tfoot>
</table>






<div class="separador"></div>



      <table class="table table-striped table-bordered " id="tablaq" border="1">
 <thead>
    <tr>
     <th colspan="1" class="text-center small">
           <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima1.jpg" height="60" width="70" >
     </th>
     <th colspan="2" class="text-center small">Gobernación del Putumayo <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small">
           <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima2.png" height="50" width="90" >
     </th>
   </tr>
 </thead>
 <tbody>
<tr>
     <td colspan="1"><strong>Numero Factura </strong></td>
     <td colspan="3"> <?php echo $codigodepto.'-'.$facturaestampilla->fact_id; ?></td>
</tr>

<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?>
     </td>
</tr>

<tr>
     <td colspan="1"><strong>C.C. o NIT</strong></td>
     <td colspan="1"><?php echo $result->liqu_nit; ?></td>
     <td colspan="1"><strong>Tipo de contratista</strong></td>
     <td colspan="1"><?php echo $result->liqu_tipocontratista; ?></td>
</tr>
<tr>
     <td colspan="1"><strong>Número de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_numero; ?>
     </td>
     <td colspan="1"><strong>Vigencia</strong></td>
     <td colspan="1"><?php echo $result->liqu_vigencia; ?></td>
</tr>
<tr>
     <td colspan="1"><strong>Valor del contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_valorconiva; ?>
     </td>
     <td colspan="1"><strong>Valor sin IVA</strong></td>
     <td colspan="1"><?php echo $result->liqu_valorsiniva; ?>
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_tipocontrato; ?></td>
     <td colspan="2" class="text-center"><strong>Régimen <?php echo $result->liqu_regimen; ?></strong></td>
</tr>

<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta Ahorros</strong></td>
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
     <small> "Vale ser legal"<br>
      Calle 8 Número 7-40 <br>
      Teléfonos (57+8) 4206600 Ext. 101 Fax: 4295196<br>
      contactenos@putumayo.gov.co </small> 
     </th>
     <th colspan="2" class="text-center">
     <br><br>

      <!-- <tcpdf method="write1DBarcode" params="<?php echo $params; ?>" /> -->
      <img src="<?php echo $this->config->item('application_root'); ?>application/libraries/barcodegen/<?php echo $codebar ?>.png" width="300" height="40">
      <small><?php echo $codebar; ?></small>
     </th>
     
   </tr>
 </tfoot>
</table>
