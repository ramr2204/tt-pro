<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/bancos/bancos_add.php
*   Descripcion:       permite crear una nueva aplicación
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-12
*
*/

?>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1>Cargar un Nuevo Archivo de Pagos</h1></div>
                             <div class="panel-body">
                              <?php echo form_open_multipart('pagos/doadd',array('id'=>'form-conciliacion')); ?>

                                    <div class="form-group">
                                        <label for="bancoid">Banco</label>
                                        <select class="form-control chosen" id="bancoid" name="bancoid" required="required" >
                                            <option value="0">Seleccione...</option>
                                            <?php  foreach($bancos as $id => $valor) { ?>
                                                <option value="<?php echo $id; ?>"><?php echo $valor; ?></option>
                                            <?php   } ?>
                                        </select>
                                           <?php echo form_error('bancoid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="f_inicial">Fecha Pago</label>
                                        <div class='input-group date' id='datetimepicker_conciliacion' data-date-format="YYYY-MM-DD">
                                            <input type='text' class="form-control" name="f_conciliacion" required="required"/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>                      
                                    </div>

                                    <div class="form-group">
                                        <label for="archivo">Formato (txt)</label>
                                        <input id="file" type="file" class="file" name="archivo" multiple=false>
                                    </div>

                                    <div class="pull-right">
                                        <?php  echo anchor('bancos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                        <button type="submit" id="btn-conciliacion" class="btn btn-success ladda-button" data-style="expand-right"><i class="fa fa-floppy-o"></i> <span class="ladda-label">Guardar</span></button>                                        
                                    </div>
                                <?php echo form_close();?>
                              
                              </div>
                             </div>
       
                        </div>
                        <div class="col-md-4 column">
                        </div>
                  </div> 
            </div>
      </div>

<div id="not_conciliacion" class="notificacion_carga">
    <span id="not_animada" class="animated infinite fadeInDown">Realizando Conciliación . . .</span>    
</div>

<div class="pantalla_not"></div>

<script type="text/javascript">
    $("#file").fileinput({

        initialCaption: "",
        showCaption: false,
        browseClass: "btn btn-default",
        browseLabel: "Cargar Archivo",
        showUpload: false,
        showRemove: false,

    });

</script>
