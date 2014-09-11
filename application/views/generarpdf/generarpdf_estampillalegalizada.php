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
   
    <div class="table-responsive" border:"0">
      <table class="table table-striped table-bordered " id="tablaq" border="1">
 <thead>
    <tr>
     <th colspan="1" class="text-center small">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima1.jpg" height="50" width="40" >
     </th>
     <th colspan="2" class="text-center small">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" height="50" width="80" >
     </th>
   </tr>
 </thead>
 <tbody>
   
 <tr>
     <td colspan="4"></td>

</tr>
<tr>
     
     <td colspan="4" align="center"><?php echo $estampilla->fact_nombre; ?></td>
</tr>
 <tr>
     <td colspan="4"></td>

</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Nombre </strong></td>
     <td colspan="1" class="text-center"> <?php echo $estampilla->cont_nombre; ?></td>
      <td colspan="1" class="text-center"><strong>C.C. o NIT</strong></td>
     <td colspan="1"> <?php echo $estampilla->cont_nit; ?></td>
</tr>

 <tr>
     <td colspan="1" class="text-center"><strong>No. de contrato </strong></td>
     <td colspan="1" class="text-center"> <?php echo $estampilla->cntr_numero; ?></td>
      <td colspan="1" class="text-center"><strong>Vigencia</strong></td>
     <td colspan="1"> <?php echo $estampilla->cntr_vigencia; ?></td>
</tr>



<tr>
     <td colspan="1" rowspan="2" valign="center">
    <?php if ($estampilla->fact_rutaimagen) { ?>
     <img src="<?php echo base_url().$estampilla->fact_rutaimagen; ?>" >
    <?php } ?>
     </td>
     <td colspan="1" class="text-center">Valor</td>
      <td colspan="2"> <?php echo '$'.number_format($estampilla->fact_valor, 2, ',', '.'); ?></td>
</tr>

<tr>
     <td colspan="3" class="text-right"><tcpdf method="write1DBarcode" params="<?php echo $params; ?>" /></td>
     
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

