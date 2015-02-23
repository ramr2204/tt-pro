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
     <th colspan="1" class="text-center small" width="20%">
           <img src="<?php echo base_url() ?>images/gobernacion_tolima1.jpg" height="50" width="40" >
     </th>
     <th colspan="3" class="text-center small" width="60%">Gobernación del Tolima <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
     <th colspan="1" class="text-center small" width="20%">
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
     <td colspan="3"><?php echo $result->liqu_nombrecontratista; ?></td>
     <td colspan="1"><?php echo anchor(base_url().'generarpdf/generar_liquidaciontramite/'.$result->liqu_tramiteid,'<i class="fa fa-file-pdf-o fa-2x"></i> PDF','class="btn btn-large  btn-default" target="_blank"'); ?></td>

</tr>

 <tr>
     <td colspan="1"><strong>Documento</strong></td>
     <td colspan="4"><?php echo $result->liqu_nit; ?>
     </td>
</tr>

<tr>
     <td colspan="2"><strong>Salario mínimo diario</strong></td>
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
<?php $x=0; ?>
<?php foreach($facturas as $row2) { ?>
<tr>
     <td colspan="1">
     <?php echo $row2->fact_nombre; ?>
     <?php if ($row2->fact_rutaimagen) { ?>
     <img src="<?php echo base_url().$row2->fact_rutaimagen; ?>" height="60" width="60" >
    <?php } ?>
      </td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_cuenta; ?><br><?php echo $row2->fact_banco; ?></td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_porcentaje; ?>%</td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($row2->fact_valor, 2, ',', '.'); ?>

     </td>
     <td colspan="1" class="text-center">
    <div class="form-group">                            
        <div class="input-group">
            <input id="fecha_pago_<?php echo $x; ?>" type="text" name="fecha_pago_<?php echo $x; ?>" class="form-control date" required="required" value="<?php echo $row2->pago_fecha; ?>"/>                      
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div> 
     <div class="form-group">
             <input id="facturaid" type="hidden" name="facturaid<?php echo $x; ?>" value="<?php echo $row2->fact_id; ?>"/> 
             <input id="file-<?php echo $x; ?>" type="file" class="file" name="comprobante<?php echo $x; ?>" multiple=false>
     </div>
     <?php if ($facturapagada[$row2->fact_id]) {  ?>      
      <div class="bg-success">Pagado: <?php echo '$'.number_format($row2->pago_valor, 2, ',', '.'); ?> <i class="fa fa-check"></i> </div> 
          <?php if ($row2->impr_codigopapel>0) { ?>
                <div class="bg-info">legalizado: <?php echo $row2->impr_codigopapel; ?> <i class="fa fa-gavel"></i> </div>
          <?php  } ?>
     <?php  } else { ?>
     
          <input type="checkbox" name="pago<?php echo $x; ?>" value="<?php echo $row2->fact_valor; ?>">
      
     <?php  } ?>

     </td>
</tr>
<script type="text/javascript">
    $('.date').datepicker({format:'yyyy-mm-dd',type:'component'});

    $("#file-<?php echo $x; ?>").fileinput({
        
      <?php   if ($row2->fact_rutacomprobante != '') { ?>
            
        initialPreview: ["<a href='<?php echo base_url().$row2->fact_rutacomprobante; ?>' target='_blank'><img src='<?php echo base_url().$row2->fact_rutacomprobante; ?>' class='file-preview-image' alt='The Moon' title='The Moon'></a>"
],
        initialCaption: "",

        <?php
        }

        ?>
        showCaption: false,
        browseClass: "btn btn-default btn-sm",
        browseLabel: "Cargar comprobante",
        showUpload: false,
        showRemove: false,

    });

    $("[name='pago<?php echo $x; ?>']").bootstrapSwitch({
        offText:'No pago',
        onText:'Pagado',
        onColor: 'success',
        offColor: 'danger'
    });
</script>
<?php $x++; ?>
<?php } ?>
<tr>
     <td colspan="3" class="text-right"><strong>Total</strong>
     <input type="hidden" name="numeroarchivos" value="<?php echo $x; ?>">
     <input class="form-control" id="tramited" type="hidden" name="tramiteid" value="<?php echo $result->liqu_tramiteid; ?>"/>
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
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>     
         </th>
     </tr>
 </tfoot>   
</table>
<?php echo form_close();?>

<?php if ($todopago) {  ?>
     <?php if ($comprobantes) {  ?>
<?php echo form_open("liquidaciones/legalizar",'role="form"');?>
 <div class="pull-right">
        <input type="hidden" name="numeroarchivos" value="<?php echo $x; ?>">
        <input type="hidden" name="tramiteid" value="<?php echo $result->liqu_tramiteid; ?>">
        <input type="hidden" name="liquidacionid" value="<?php echo $result->liqu_id; ?>">
        <button type="submit" class="btn btn-success">Legalizar</button>      
 </div> 
<?php echo form_close();?>
      <?php  }  ?>
 <?php  }  ?>
</div>
</div>   
      </div>
 
 