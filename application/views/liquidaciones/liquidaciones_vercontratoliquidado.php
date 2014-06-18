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
     <td colspan="3"></td>
     <th colspan="1"><?php echo anchor(base_url().'generarpdf/generar_liquidacion/'.$result->liqu_contratoid,'<i class="fa fa-file-pdf-o fa-2x"></i> PDF','class="btn btn-large  btn-default" target="_blank"'); ?></th>

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
     <td colspan="1"><?php echo $result->liqu_tipocontrato; ?></td>
     <td colspan="2" class="text-center"><strong>Régimen <?php echo $result->liqu_regimen; ?></strong>
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
<?php $x=0; ?>
<?php foreach($facturas as $row2) { ?>
<tr>
     <td colspan="1"><?php echo $row2->fact_nombre; ?> </td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_cuenta; ?><br><?php echo $row2->fact_banco; ?></td>
     <td colspan="1" class="text-center"><?php echo $row2->fact_porcentaje; ?>%</td>
     <td colspan="1" class="text-right"><?php echo '$'.number_format($row2->fact_valor, 2, ',', '.'); ?>

     <div class="form-group">
             <input id="facturaid" type="hidden" name="facturaid<?php echo $x; ?>" value="<?php echo $row2->fact_id; ?>"/> 
             <input id="file-<?php echo $x; ?>" type="file" class="file" name="comprobante<?php echo $x; ?>" multiple=false>
     </div>

     </td>
</tr>

<script>
    $("#file-<?php echo $x; ?>").fileinput({
        showCaption: false,
        browseClass: "btn btn-success btn-sm",
        browseLabel: "Comprobante",
        fileType: "pdf",
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
     <td colspan="1" class="text-right"><?php echo '$'.number_format($result->liqu_valortotal, 2, ',', '.'); ?>
     </td>
</tr>
 </tbody>     
</table>
</div>
</div>   
      </div>
 