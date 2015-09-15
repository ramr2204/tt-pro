<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/ordenanzas/ordenanzas_edit.php
*   Descripcion:       permite editar una ordenanza
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-12
*
*/
?>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix">                
            <div class="col-md-4 col-md-offset-4 column">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Editar Ordenanza</h1></div>
                        <div class="panel-body">
                            <?php echo form_open_multipart('ordenanzas/update'); ?>
                                <input type="hidden" name="id" value="<?php echo $ordenanza->orde_id;?>">
                                <div class="form-group">
                                    <label for="orde_fecha">Fecha Expedición</label>
                                    <div class='input-group date' id='datetimepicker_fechaOrdenanza' data-date-format="YYYY-MM-DD">
                                        <input type='text' class="form-control" name="orde_fecha" required="required" value="<?php echo $ordenanza->orde_fecha;?>" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>                      
                                </div>
                                <div class="form-group">
                                    <label for="orde_iniciovigencia">Fecha Inicio Vigencia</label>
                                    <div class='input-group date' id='datetimepicker_inicioOrdenanza' data-date-format="YYYY-MM-DD">
                                        <input type='text' class="form-control" name="orde_iniciovigencia" required="required" value="<?php echo $ordenanza->orde_iniciovigencia;?>"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>                      
                                </div>
                                <div class="form-group">
                                    <label for="orde_numero">Número de Ordenanza</label>
                                    <input class="form-control" id="orde_numero" type="number" name="orde_numero" required="required" maxlength="100" value="<?php echo $ordenanza->orde_numero;?>" />
                                    <?php echo form_error('orde_numero','<span class="text-danger">','</span>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="archivo">Soporte</label>
                                    <input id="file" type="file" class="file" name="archivo" multiple=false>
                                </div>
                                <div class="pull-right">
                                    <?php  echo anchor('ordenanzas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" class="btn btn-success" id="btn-ordenanzasAdd"><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
                                </div>
                                <?php echo form_close();?>                              
                        </div>
                </div>       
            </div>                        
        </div> 
    </div>
</div>

<script type="text/javascript">
    $("#file").fileinput({
        
      <?php   if ($ordenanza->orde_rutadocumento != '') { ?>
            
        initialPreview: ["<a href='<?php echo base_url().$ordenanza->orde_rutadocumento; ?>'class='btn btn-success' target='_blank'><img src='<?php echo base_url().$ordenanza->orde_rutadocumento; ?>' class='file-preview-image' alt='Ordenanza' title='Ordenanza'></a>"
],
        initialCaption: "",

        <?php
        }

        ?>
        showCaption: false,
        browseClass: "btn btn-default btn-sm",
        browseLabel: "Cargar Archivo",
        showUpload: false,
        showRemove: false,

    });
</script> 
