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
     
     <th colspan="4" class="text-center small">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
   
   </tr>
 </thead>
 <tbody>
   
 <tr>
     <td colspan="4"><h1>Liquidación de contrato</h1></td>
</tr>
<tr>
     
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="3"><?php echo $result->cont_nombre; ?>
     <input type="hidden" name="nombrecontratista" value="<?php echo $result->cont_nombre; ?>">
     </td>
</tr>

 <tr>
     <td colspan="1"><strong>C.C. o NIT</strong></td>
     <td colspan="3"><?php echo $result->cont_nit; ?>
     <input type="hidden" name="nit" value="<?php echo $result->cont_nit; ?>">
     </td>
</tr>

<tr>
     <td colspan="1"><strong>Tipo de contratista</strong></td>
     <td colspan="3"><?php echo $result->tpco_nombre; ?>
     <input type="hidden" name="tipocontratista" value="<?php echo $result->tpco_nombre; ?>">
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Número de contrato</strong></td>
     <td colspan="1"><?php echo $result->cntr_numero; ?>
     <input type="hidden" name="numero" value="<?php echo $result->cntr_numero; ?>">
     </td>
     <td colspan="1"><strong>Vigencia</strong></td>
     <td colspan="1"><?php echo $result->cntr_vigencia; ?>
     <input type="hidden" name="vigencia" value="<?php echo $result->cntr_vigencia; ?>">
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Valor del contrato</strong></td>
     <td colspan="1"><?php echo '$'.number_format($result->cntr_valor, 2, ',', '.'); ?>
     <input type="hidden" name="valor" value="<?php echo '$'.number_format($result->cntr_valor, 2, ',', '.'); ?>">
     </td>
     <td colspan="1"><strong>Valor sin IVA</strong></td>
     <td colspan="1"><?php echo '$'.number_format($cnrt_valorsiniva, 2, ',', '.'); ?>
     <input type="hidden" name="valorsiniva" value="<?php echo '$'.number_format($cnrt_valorsiniva, 2, ',', '.'); ?>">
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de contrato</strong></td>
     <td colspan="3"><?php echo $result->tico_nombre; ?>
     <input type="hidden" name="tipocontrato" value="<?php echo $result->tico_nombre; ?>">
     </td>
</tr>
<tr>
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta de ahorro</strong></td>
     <td colspan="1" class="text-center"><strong>Porcentaje</strong></td>
     <td colspan="1" class="text-center"><strong>Régimen <?php echo $result->regi_nombre; ?></strong>
     <input type="hidden" name="regimen" value="<?php echo $result->regi_nombre; ?>">
     </td>

</tr>
<?php $x=1; ?>
<?php
$totalestampillas='';
$porcentajes='';
$cuentas='';
?>
<?php  foreach($estampillas as $row2) { ?>
<tr>
     <td colspan="1"><?php echo $row2->estm_nombre; ?>
     <input type="hidden" name="nombreestampilla" value="<?php echo $row2->estm_nombre; ?>">

     </td>
     <td colspan="1" class="text-center"><?php echo $row2->estm_cuenta; ?><br><?php echo $row2->banc_nombre; ?>
     <input type="hidden" name="cuenta<?php echo $x; ?>" value="cuenta: <?php echo $row2->estm_cuenta; ?>, banco: <?php echo $row2->banc_nombre; ?> ">

     </td>
     <td colspan="1" class="text-center"><?php echo $row2->esti_porcentaje; ?>%
     <input type="hidden" name="porcentaje<?php echo $x; ?>" value="<?php echo $row2->esti_porcentaje; ?>">
     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($est_totalestampilla[$row2->estm_id], 2, ',', '.'); ?>
     <input type="hidden" name="totalestampilla<?php echo $x; ?>" value="<?php echo '$'.number_format($est_totalestampilla[$row2->estm_id], 2, ',', '.'); ?>">
     </td>
</tr>
<?php $totalestampillas.= '$'.number_format($est_totalestampilla[$row2->estm_id], 2, ',', '.').'|';
      $porcentajes.=$row2->esti_porcentaje.'|';
      $cuentas.= 'cuenta: '.$row2->estm_cuenta.' banco: '.$row2->banc_nombre.'|';
      $x++;
 ?>
<?php } ?>
<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>
     <input type="hidden" name="totalestampillas" value="<?php echo $totalestampillas; ?>">
     <input type="hidden" name="porcentajes" value="<?php echo $porcentajes; ?>">
     <input type="hidden" name="cuentas" value="<?php echo $cuentas; ?>">


     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($est_valortotal, 2, ',', '.'); ?>
     <input type="hidden" name="valortotal" value="<?php echo '$'.number_format($est_valortotal, 2, ',', '.'); ?>">
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

