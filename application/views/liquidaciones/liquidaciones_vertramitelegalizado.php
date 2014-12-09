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
     <td colspan="1"><strong>Nombre</strong></td>
     <td colspan="4"><?php echo $result->liqu_nombrecontratista; ?></td>

</tr>

 <tr>
     <td colspan="1"><strong>Documento</strong></td>
     <td colspan="4"><?php echo $result->liqu_nit; ?>
     </td>
</tr>


<tr>
     <td colspan="1"><strong>Número de contrato</strong></td>
     <td colspan="1"><?php echo $result->liqu_numero; ?>
     </td>
     <td colspan="1"><strong>Vigencia</strong></td>
     <td colspan="2"><?php echo $result->liqu_vigencia; ?></td>
</tr>
<tr>
     <td colspan="2"><strong>Valor salario mínimo</strong></td>
     <td colspan="3"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?></td>
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

<?php //para usuarios con perfil de liquidador
      if(isset($proximaImpresion)){ ?>
<input type="hidden" id="siguienteEstampilla" value="<?= $proximaImpresion; ?>" >
<?php } ?>

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
     <div class="bg-info">legalizado: <?php  ?> <i class="fa fa-gavel"></i> </div>

                <?php //verifica si el perfil del usuario logueado es 
                      //de liquidador para permitir o denegar la orden 
                      //de impresion de estampillas   
              
                      $usuarioLogueado=$this->ion_auth->user()->row();

                      if ($usuarioLogueado->perfilid==4)
                      { 
                          //Verifica si hay registrado un estado de impresion
                          //y si es 2 (anulado) para habilitar el boton                         
                          if($row2->impr_estado)
                          {
                              if($row2->impr_estado == 2)
                              { 
                                  echo anchor(base_url().'liquidaciones/procesarConsecutivos/'.$row2->fact_id,'<i class="fa fa-print"></i> Imprimir estampilla','class="btn btn-large btn-default confirmar_impresion" target="_blank"');

                              }else{ ?>
                                     <a href="#" class="btn btn-large  btn-default" disabled><i class="fa fa-print"></i>Imprimir estampilla</a>    
                     <?php         }  
                          }else{ //si no hay registrado un estado de impresion es porque no se ha impreso
                                 //entonces se habilita el boton
                                    echo anchor(base_url().'liquidaciones/procesarConsecutivos/'.$row2->fact_id,'<i class="fa fa-print"></i> Imprimir estampilla','class="btn btn-large btn-default confirmar_impresion" target="_blank"'); 
                                }

                      }else
                           { ?>

                            <a href="#" class="btn btn-large  btn-default" disabled><i class="fa fa-print"></i>Imprimir estampilla</a>

                   <?php   } ?>
     


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
 