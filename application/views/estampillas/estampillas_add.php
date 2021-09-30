<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/estampillas/estampillas_add.php
*   Descripcion:       permite crear una nueva estampilla
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
                            <div class="panel-heading"><h1>Crear una nueva estampilla</h1></div>
                             <div class="panel-body">
                              <?php echo form_open_multipart(current_url()); ?>

                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="nombre" name="nombre" value="<?php echo set_value('nombre'); ?>" required="required" maxlength="128" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="cuenta">N. de cuenta</label>
                                           <input class="form-control" id="cuenta" type="cuenta" name="cuenta" value="<?php echo set_value('cuenta'); ?>" required="required" maxlength="100" />
                                           <?php echo form_error('cuenta','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="codigoB">Codigo Barras</label>
                                           <input class="form-control" id="codigoB" type="number" name="codigoB" value="<?php echo set_value('codigoB'); ?>" required="required" maxlength="100" />
                                           <?php echo form_error('codigoB','<span class="text-danger">','</span>'); ?>
                                    </div>

                                     <div class="form-group">
                                           <label for="bancoid">Banco</label>
                                           <select class="form-control" id="bancoid" name="bancoid" required="required" >
                                           <option value="0">Seleccione...</option>
                                             <?php  foreach($bancos as $row) { ?>
                                             <option value="<?php echo $row->banc_id; ?>"><?php echo $row->banc_nombre; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('bancoid','<span class="text-danger">','</span>'); ?>
                                    </div>
                                     <div class="form-group">
                                        <label for="imagen">Imagen</label>
                                        <input id="file" type="file" class="file" name="imagen" multiple=false>
                                    </div>
                                    <div class="form-group">
                                      <label for="tipo">Tipo Estampilla</label>
                                      <select class="form-control" id="tipo" name="tipo" required="required" >
                                        <option value="0">Seleccione...</option>
                                        <?php
                                          foreach($tiposEstampillas as $id => $nombre)
                                          {
                                            ?>
                                            <option value="<?= $id ?>"><?= ucfirst($nombre) ?></option>
                                            <?php
                                          }
                                        ?>
                                      </select>
                                      <?php echo form_error('tipo','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="descripcion">Descripción</label>
                                           <textarea class="form-control" id="descripcion" type="descripcion" name="descripcion" maxlength="500"><?php echo set_value('descripcion'); ?></textarea>
                                           <?php echo form_error('descripcion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="pull-right">
                                     <?php  echo anchor('estampillas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
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

<script type="text/javascript">
  //style selects
  var config = {
    '#bancoid'  : {disable_search_threshold: 10}
  }
  for (var selector in config) {
      $(selector).chosen(config[selector]);
  }

</script>

<script type="text/javascript">
    $("#file").fileinput({

        initialCaption: "",
        showCaption: false,
        browseClass: "btn btn-default btn-sm",
        browseLabel: "Cargar imagen",
        showUpload: false,
        showRemove: false,

    });

</script>