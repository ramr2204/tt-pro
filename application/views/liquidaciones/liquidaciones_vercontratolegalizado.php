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
     <th colspan="1" class="text-center small" width="22%">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima1.jpg" height="50" width="40" >
     </th>
     <th colspan="3" class="text-center small" width="56%">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small" width="22%">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" height="50" width="80" >
     </th>
   </tr>
 </thead>
 <tbody>
   
<tr>
     
     <td colspan="5"></td>
</tr>
<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="4"><?php echo $result->liqu_nombrecontratista; ?></td>

</tr>

 <tr>
     <td colspan="1"><strong>C.C. o NIT</strong></td>
     <td colspan="4"><?php echo $result->liqu_nit; ?>
     </td>
</tr>

<tr>
     <td colspan="1"><strong>Tipo de contratista</strong></td>
     <td colspan="4"><?php echo $result->liqu_tipocontratista; ?></td>
</tr>
<tr>
     <td colspan="1"><strong>Número de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_numero; ?>
     </td>
     <td colspan="1"><strong>Vigencia</strong></td>
     <td colspan="2"><?php echo $result->liqu_vigencia; ?></td>
</tr>
<tr>
     <td colspan="1"><strong>Valor del contrato</strong></td>
     <td colspan="1"><?php echo '$'.number_format($result->liqu_valorconiva, 2, ',', '.'); ?></td>
     <td colspan="1"><strong>Valor sin IVA</strong></td>
     <td colspan="2"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?></td>
</tr>
<tr>
     <td colspan="1"><strong>Tipo de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_tipocontrato; ?></td>
     <td colspan="3" class="text-center"><strong>Régimen <?php echo $result->liqu_regimen; ?></strong></td>
</tr>
<tr>
     <td colspan="5"></td>
</tr>
<tr>
     <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Cuenta de ahorro</strong></td>
     <td colspan="1" class="text-center"><strong>Porcentaje</strong></td>
     <td colspan="1" class="text-center"><strong>Valor</strong></td>
     <td colspan="1" class="text-center"><strong>Procesos</strong></td>
</tr>
<?php $x=0; ?>
<?php foreach($facturas as $row2) { ?>
<tr>
     <td colspan="1"><?php echo $row2->fact_nombre; ?>
       <?php if ($row2->fact_rutaimagen) { ?>
           <img src="<?php echo base_url().$row2->fact_rutaimagen; ?>" height="60" width="60" >
       <?php } ?>

      </td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_cuenta; ?><br><?php echo $row2->fact_banco; ?></td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_porcentaje; ?>%</td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($row2->fact_valor, 2, ',', '.'); ?>

     </td>
     <td colspan="1" class="text-center">
       
     <div>
      <?php   if ($row2->fact_rutacomprobante != '') { ?>     
       <a href='<?php echo base_url().$row2->fact_rutacomprobante; ?>' target='_blank'><img src='<?php echo base_url().$row2->fact_rutacomprobante; ?>' class='file-preview-image' alt='comprobante de pago' title='comprobante de pago'  height="42" width="42"></a>
      <?php } ?> 
     </div>
     <?php if ($facturapagada[$row2->fact_id]) {  ?>      
     <div class="bg-success">Código: <?php echo $row2->fact_codigo; ?> <i class="fa fa-check"></i> </div> 
     <?php if ($row2->impr_codigopapel>0) { ?>
                <div class="bg-info">legalizado: <?php echo $row2->impr_codigopapel; ?> <i class="fa fa-gavel"></i> </div>
                <?php echo anchor(base_url().'generarpdf/generar_estampilla/'.$row2->fact_id,'<i class="fa fa-print"></i> Imprimir estampilla','class="btn btn-large  btn-default" target="_blank"'); ?>
          <?php  } ?>
     <?php  } else { ?>
      <div class="bg-danger">Pagado: <?php echo '$'.number_format($row2->pago_valor, 2, ',', '.'); ?> <i class="fa fa-times"></i> </div> 
     <?php  } ?>
     
     </td>
</tr>


<?php $x++; ?>
<?php } ?>

 </tbody>   
</table>
  <?php //echo form_open_multipart("liquidaciones/cargar_comprobante",'role="form"');?>
<?php //echo form_close();?>
</div>
</div>   
      </div>
 