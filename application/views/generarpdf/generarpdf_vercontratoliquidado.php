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

</style>

<div class="row"> 
 <div class="col-sm-12">    
   
    <div class="table-responsive">
      <table class="table table-striped table-bordered " id="tablaq" border="1">
 <thead>
    <tr>
     
     <th colspan="4" class="text-center small">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
   
   </tr>
 </thead>
 <tbody>
   
 <tr>
     <td colspan="4"></td>

</tr>
<tr>
     
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?>
     </td>
</tr>

 <tr>
     <td colspan="1"><strong>C.C. o NIT</strong></td>
     <td colspan="3"><?php echo $result->liqu_nit; ?>
     </td>
</tr>

<tr>
     <td colspan="1"><strong>Tipo de contratista</strong></td>
     <td colspan="3"><?php echo $result->liqu_tipocontratista; ?>
     </td>
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
     <td colspan="1"><?php echo $result->liqu_valorconiva; ?>
     </td>
     <td colspan="1"><strong>Valor sin IVA</strong></td>
     <td colspan="1"><?php echo $result->liqu_valorsiniva; ?>
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de contrato</strong></td>
     <td colspan="3"><?php echo $result->liqu_tipocontrato; ?>
     </td>
</tr>
<tr>
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta de ahorro</strong></td>
     <td colspan="1" class="text-center"><strong>Porcentaje</strong></td>
     <td colspan="1" class="text-center"><strong>Régimen <?php echo $result->liqu_regimen; ?></strong>
     </td>

</tr>
<?php foreach($facturas as $row2) { ?>
<tr>
     <td colspan="1"><?php echo $row2->fact_nombre; ?>

     </td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_cuenta; ?><br><?php echo $row2->fact_banco; ?>

     </td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_porcentaje; ?>%
     </td>
     <td colspan="1" class="text-right"><?php echo $row2->fact_valor; ?>
    
     </td>
</tr>
<?php } ?>
<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>


     </td>
     <td colspan="1" class="text-right"><?php echo $result->liqu_valortotal; ?>
     </td>
</tr>
 </tbody>     
 <tfoot>
   <tr>
     <th colspan="4" class="text-center">
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

