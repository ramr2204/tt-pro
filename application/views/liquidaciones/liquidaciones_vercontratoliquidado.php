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
   <?php echo form_open_multipart("liquidaciones/cargar_comprobante",'role="form"');?>
    <div class="table-responsive">
      <table class="table table-striped table-bordered " id="tablaq">
 <thead>
    <tr>
     
     <th colspan="5" class="text-center small">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
   
   </tr>
 </thead>
 <tbody>
   
<tr>
     
     <td colspan="5"></td>
</tr>
<tr>
     <td colspan="1"><strong>Nombre del contratista</strong></td>
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?></td>
     <td colspan="1"><?php echo anchor(base_url().'generarpdf/generar_liquidacion/'.$result->liqu_contratoid,'<i class="fa fa-file-pdf-o fa-2x"></i> PDF','class="btn btn-large  btn-default" target="_blank"'); ?></td>

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
     <td colspan="1"><?php echo $row2->fact_nombre; ?> </td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_cuenta; ?><br><?php echo $row2->fact_banco; ?></td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_porcentaje; ?>%</td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($row2->fact_valor, 2, ',', '.'); ?>

     </td>
     <td colspan="1" class="text-center">
       
     <div class="form-group">
             <input id="facturaid" type="hidden" name="facturaid<?php echo $x; ?>" value="<?php echo $row2->fact_id; ?>"/> 
             <input id="file-<?php echo $x; ?>" type="file" class="file" name="comprobante<?php echo $x; ?>" multiple=false>
     </div>
     <?php if ($facturapagada[$row2->fact_id]) {  ?>
      <div class="bg-success">Pagado: <?php echo '$'.number_format($row2->pago_valor, 2, ',', '.'); ?> <i class="fa fa-check"></i> </div> 
     <?php  } else { ?>
      <div class="bg-danger">Pagado: <?php echo '$'.number_format($row2->pago_valor, 2, ',', '.'); ?> <i class="fa fa-times"></i> </div> 
     <?php  } ?>

     </td>
</tr>
<script>
    $("#file-<?php echo $x; ?>").fileinput({
        
      <?php   if ($row2->fact_rutacomprobante != '') { ?>
            
        initialPreview: ["<a href='<?php echo base_url().$row2->fact_rutacomprobante; ?>' target='_blank'><img src='<?php echo base_url().$row2->fact_rutacomprobante; ?>' class='file-preview-image' alt='The Moon' title='The Moon'></a>"
],
        initialCaption: "The Moon and the Earth",

        <?php
        }

        ?>
        showCaption: false,
        browseClass: "btn btn-default btn-sm",
        browseLabel: "Cargar comprobante",
        showUpload: false,
        showRemove: false,

    });
</script>

<?php $x++; ?>
<?php } ?>
<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>
     <input type="hidden" name="numeroarchivos" value="<?php echo $x; ?>">
     <input class="form-control" id="contratoid" type="hidden" name="contratoid" value="<?php echo $result->liqu_contratoid; ?>"/>
     </td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($result->liqu_valortotal, 2, ',', '.'); ?></td>
     <td>
         <?php if ($comprobantes) {  ?>
                <div class="bg-success">Comprobantes: <?php echo $ncomprobantescargados.'/'.$numerocomprobantes; ?> <i class="fa fa-check"></i> </div> 
         <?php  } else { ?>
                <div class="bg-danger">Comprobantes: <?php echo $ncomprobantescargados.'/'.$numerocomprobantes; ?> <i class="fa fa-times"></i> </div>
         <?php  }  ?>
         <?php if ($todopago) {  ?>
                <div class="bg-success">Pagado: <?php echo '$'.number_format($totalpagado, 2, ',', '.'); ?> <i class="fa fa-check"></i> </div> 
         <?php  } else { ?>
                <div class="bg-danger">Pagado: <?php echo '$'.number_format($totalpagado, 2, ',', '.'); ?> <i class="fa fa-times"></i> </div>
         <?php  }  ?>

     </td>
</tr>
 </tbody>  
 <tfoot>
     <tr>
         <th colspan="5">
         <div class="pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar comprobantes</button>
        </div>     
         </th>
     </tr>
 </tfoot>   
</table>
<?php echo form_close();?>
 <div class="pull-right">
     <?php echo form_open("liquidaciones/legalizar",'role="form"');?>
        <?php if ($completado) {  ?>
        <button type="submit" class="btn btn-success">Legalizar</button>
        <?php  }  ?>
     <?php echo form_close();?>
 </div> 

</div>
</div>   
      </div>
 