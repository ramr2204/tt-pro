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
           <img src="<?php echo base_url() ?>images/gobernacion_tolima1.jpg" height="50" width="40" >
     </th>
     <th colspan="2" class="text-center small" width="50%">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small" width="25%">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" height="50" width="80" >
     </th>
   </tr>
 </thead>
 <tbody>
   
   <?php if(isset($idtramite)){?>
   <input class="form-control" id="idtramite" type="hidden" name="idtramite" value="<?php echo $idtramite; ?>"/>
   <?php }?>

 <tr>
     <td colspan="4"><h1>Liquidación</h1></td>
</tr>
<tr>
     
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="1"><strong>Nombre del Tramitador</strong></td>
     <td colspan="3"><?php echo $result->litr_tramitadornombre; ?>
     <input type="hidden" name="nombretramitador" value="<?php echo $result->litr_tramitadornombre; ?>">
     </td>
</tr>

 <tr>
     <td colspan="1"><strong>Número de documento</strong></td>
     <td colspan="3"><?php echo $result->litr_tramitadorid; ?>
     <input type="hidden" name="idtramitador" value="<?php echo $result->litr_tramitadorid; ?>">
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Fecha</strong></td>
     <td colspan="3"><?php echo $result->litr_fechaliquidacion; ?>
     <input type="hidden" name="vigencia" value="<?php echo $result->litr_fechaliquidacion; ?>">
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Salario mínimo diario</strong></td>
     <td colspan="3"><?php echo '$'.number_format($cnrt_valorsiniva, 2, ',', '.'); ?>
     <input type="hidden" name="valorsiniva" value="<?php echo $cnrt_valorsiniva; ?>">
     </td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de trámite</strong></td>
     <td colspan="3"><?php echo $result->tram_nombre; ?>
     <input type="hidden" name="tipocontrato" value="<?php echo $result->tram_nombre; ?>">
     </td> 
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
<?php $x=1; ?>
<?php
$totalestampillas='';
$porcentajes='';
$cuentas='';
?>
<?php  foreach($estampillas as $row2) { ?>
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
     <td colspan="1" class="text-center"><?php echo $row2->estr_porcentaje; ?>%
     <input type="hidden" name="porcentaje<?php echo $x; ?>" value="<?php echo $row2->estr_porcentaje; ?>">
     <input type="hidden" name="rutaimagen<?php echo $x; ?>" value="<?php echo $row2->estm_rutaimagen; ?>">
     </td>
     <td colspan="1" class="text-right">
     <br>
     <input type="text" class="calcular" name="totalestampilla<?php echo $x; ?>" value="<?php echo $est_totalestampilla[$row2->estm_id]; ?>">
     </td>
</tr>
<?php 

     $totalestampillas.= '$'.number_format($est_totalestampilla[$row2->estm_id], 2, ',', '.').'|';
      $porcentajes.=$row2->estr_porcentaje.'|';
      $cuentas.= 'cuenta: '.$row2->estm_cuenta.' banco: '.$row2->banc_nombre.'|';
      $x++;
 ?>
<?php } ?>
<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>
     <input type="hidden" name="totalestampillas" value="<?php echo $totalestampillas; ?>">
     <input type="hidden" name="porcentajes" value="<?php echo $porcentajes; ?>">
     <input type="hidden" name="cuentas" value="<?php echo $cuentas; ?>">
     <input type="hidden" name="numeroestampillas" value="<?php echo $x; ?>"

     </td>
     <td colspan="1" class="text-right">
     <input type="text" name="valortotal" id="valortotal" value="<?php echo $est_valortotal; ?>" readonly="readonly">
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

