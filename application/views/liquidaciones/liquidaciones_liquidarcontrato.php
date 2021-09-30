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
     <th colspan="1" class="text-center small" width="25%">
           <img src="<?php echo base_url() ?>images/gobernacion.jpg" height="60" width="70" >
     </th>
     <th colspan="2" class="text-center small" width="50%">Gobernación de Boyacá <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small" width="25%">
           <img src="<?php echo base_url() ?>images/logo.png" height="50" width="80" >
     </th>
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
     <input type="hidden" name="valorconiva" value="<?php echo $result->cntr_valor; ?>">
     </td>
     <td colspan="1"><strong>Valor sin IVA</strong></td>
     <td colspan="1"><?php echo '$'.number_format($cnrt_valorsiniva, 2, ',', '.'); ?>
     <input type="hidden" name="valorsiniva" value="<?php echo $cnrt_valorsiniva; ?>">
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de contrato</strong></td>
     <td colspan="1"><?php echo $result->tico_nombre; ?>
     <input type="hidden" name="tipocontrato" value="<?php echo $result->tico_nombre; ?>">
     </td>
     <td colspan="2" class="text-center"><strong>Régimen <?php echo $result->regi_nombre; ?></strong>
     <input type="hidden" name="idregimen" value="<?php echo $result->regi_id; ?>">
</tr>
<tr>
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta de ahorro</strong></td>
     <td colspan="1" class="text-center"><strong>Porcentaje</strong></td>
     <td colspan="1" class="text-center"><strong>Valor</strong>
     <input type="hidden" name="regimen" value="<?php echo $result->regi_nombre; ?>">
     </td>

</tr>
<?php $x=0; ?>
<?php
$totalestampillas='';
$porcentajes='';
$cuentas='';
?>
<?php  foreach($estampillas as $row2) { 

    /*
    * Se valida si la estampilla a almacenar es pro electrificacion
    * y si la fecha de liquidacion (fecha actual) es mayor al 21 de mayo de 2017
    * no se incluya la estampilla en las liquidaciones según ordenanza 026 de 2007
    */
    $bandRegistrarFactura = Liquidaciones::validarInclusionEstampilla($row2->estm_id, $result->cntr_fecha_firma, $result->cntr_tipocontratoid);
    if($bandRegistrarFactura)
    {
?>
<tr>
     <td colspan="1"><?php echo $row2->estm_nombre; ?>
     <input type="hidden" name="nombreestampilla<?php echo $x; ?>" value="<?php echo $row2->estm_nombre; ?>">
     <input type="hidden" name="idestampilla<?php echo $x; ?>" value="<?php echo $row2->estm_id; ?>">
     <?php if ($row2->estm_rutaimagen) { ?>
     <img src="<?php echo base_url().$row2->estm_rutaimagen; ?>" height="60" width="60" >
    <?php } ?>
     </td>
     <td colspan="1" class="text-center"><?php echo $row2->estm_cuenta; ?><br><?php echo $row2->banc_nombre; ?>
     <input type="hidden" name="cuenta<?php echo $x; ?>" value="<?php echo $row2->estm_cuenta; ?>">
     <input type="hidden" name="banco<?php echo $x; ?>" value="<?php echo $row2->banc_nombre; ?>">
     </td>
     <td colspan="1" class="text-center"><?php echo $row2->esti_porcentaje; ?>%
     <input type="hidden" name="porcentaje<?php echo $x; ?>" value="<?php echo $row2->esti_porcentaje; ?>">
     <input type="hidden" name="rutaimagen<?php echo $x; ?>" value="<?php echo $row2->estm_rutaimagen; ?>">
     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($est_totalestampilla[$row2->estm_id], 2, ',', '.'); ?>
     <br>
     <input type="hidden" name="totalestampilla<?php echo $x; ?>" value="<?php echo $est_totalestampilla[$row2->estm_id]; ?>">
     </td>
</tr>
<?php 

     $totalestampillas.= '$'.number_format($est_totalestampilla[$row2->estm_id], 2, ',', '.').'|';
      $porcentajes.=$row2->esti_porcentaje.'|';
      $cuentas.= 'cuenta: '.$row2->estm_cuenta.' banco: '.$row2->banc_nombre.'|';
      $x++;

    }
 ?>
<?php } ?>
<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>
         <input type="hidden" name="totalestampillas" value="<?php echo $totalestampillas; ?>">
         <input type="hidden" name="porcentajes" value="<?php echo $porcentajes; ?>">
         <input type="hidden" name="cuentas" value="<?php echo $cuentas; ?>">
         <input type="hidden" name="numeroestampillas" value="<?php echo $x; ?>">

     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($est_valortotal, 2, ',', '.'); ?>
     <input type="hidden" name="valortotal" value="<?php echo $est_valortotal; ?>">
     </td>
</tr>
 </tbody>     
 <tfoot>
   <tr>
     <th colspan="4" class="text-center">
     <small> "Boyacá Avanza"<br>
      Palacio de la Torre, Calle 20 No. 9 – 90 <br>
      Teléfono PBX+(57)608742 0150<br>
      contactenos@boyaca.gov.co </small> 
     </th>
   </tr>
 </tfoot>
</table>
</div>
</div>   
      </div>

