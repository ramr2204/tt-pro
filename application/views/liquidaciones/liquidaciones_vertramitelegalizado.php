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
           <img src="<?php echo base_url() ?>images/gobernacion.jpg" height="60" width="70" >
     </th>
     <th colspan="2" class="text-center small" width="56%">Gobernación de Boyacá <br> Secretaría de Hacienda <br> Dirección de Recaudo y Fiscalización</th>
     <th colspan="1" class="text-center small" width="22%">
           <img src="<?php echo base_url() ?>images/logo.png" height="50" width="80" >
     </th>
   </tr>
 </thead>
 <tbody>
   
<tr>
     
     <td colspan="5"></td>
</tr>
<tr>
     <td colspan="1"><strong>Nombre</strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?></td>

</tr>

 <tr>
     <td colspan="1"><strong>Documento</strong></td>
     <td colspan="3"><?php echo $result->liqu_nit; ?>
     </td>
</tr>


<tr>
     <td colspan="1"><strong>Número de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_numero; ?>
     </td>
     <td colspan="1"><strong>Vigencia</strong></td>
     <td colspan="1"><?php echo $result->liqu_vigencia; ?></td>
</tr>
<tr>
     <td colspan="1"><strong>Valor salario mínimo</strong></td>
     <td colspan="3"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?></td>
</tr>

<tr>
     <td colspan="4"></td>
</tr>
<tr>
     <td colspan="2" class="text-center"><strong>Estampilla</strong></td>
     <td colspan="1" class="text-center"><strong>Porcentaje</strong></td>
     <td colspan="1" class="text-center"><strong>Valor</strong></td>
</tr>

<?php
     //para usuarios con perfil de liquidador
     if(isset($proximaImpresion))
     {
          ?>
          <input type="hidden" id="siguienteEstampilla" value="<?= $proximaImpresion; ?>" >
          <?php
     }
     $x=0;
     
     foreach($facturas as $row2)
     {
          ?>
          <tr>
               <td colspan="2">
                    <?php echo $row2->fact_nombre; ?>
                    <?php
                         if ($row2->fact_rutaimagen)
                         {
                              ?>
                              <img src="<?php echo base_url().$row2->fact_rutaimagen; ?>" height="60" width="60" >
                              <?php
                         }
                    ?>
               </td>
               <td colspan="1" class="text-center"><?php echo $row2->fact_porcentaje; ?>%</td>
               <td colspan="1" class="text-right"><?php echo '$'.number_format($row2->fact_valor, 2, ',', '.'); ?></td>
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
 