<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/estampillas/estampillas_list.php
*   Descripcion:       permite editar una estampilla
*   Fecha Creacion:    12/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-12
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
                           <div class="panel-heading"><h1>Editar estampilla</h1></div>
                             <div class="panel-body">
                              <?php echo form_open_multipart(current_url(),'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Id</label>
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->estm_id; ?>"/>
                                           <p><?php echo $result->estm_id; ?></p>
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo $result->estm_nombre; ?>" required="required" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="cuenta">N. de cuenta</label>
                                           <input class="form-control" id="cuenta" type="text" name="cuenta" value="<?php echo $result->estm_cuenta; ?>" required="required" />
                                           <?php echo form_error('cuenta','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="codigoB">Codigo Barras</label>
                                           <input class="form-control" id="codigoB" type="number" name="codigoB" value="<?php echo $result->estm_codigoB; ?>" required="required" maxlength="100" />
                                           <?php echo form_error('codigoB','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="bancoid">Banco</label>
                                           <select class="form-control" id="bancoid" name="bancoid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($bancos as $row) { ?>
                                                 <?php if ($row->banc_id==$result->estm_bancoid) { ?>
                                                 <option selected value="<?php echo $row->banc_id; ?>" ><?php echo $row->banc_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->banc_id; ?>"><?php echo $row->banc_nombre; ?></option>
                                                 <?php } ?>

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
                                            <option value="<?= $id ?>" <?= ($result->tipo == $id ? 'selected' : '') ?>><?= ucfirst($nombre) ?></option>
                                            <?php
                                          }
                                        ?>
                                      </select>
                                      <?php echo form_error('tipo','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="descripcion">Descripción</label>
                                           <textarea class="form-control" id="descripcion" type="descripcion" name="descripcion" maxlength="500"><?php echo $result->estm_descripcion; ?></textarea>
                                           <?php echo form_error('descripcion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                      
                                    <div class="pull-right">
                                     <?php  echo anchor('estampillas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                     
                                     <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/delete')) { ?>
                                      <a class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash-o"></i> Eliminar</a>
                                     <?php } ?>

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

<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/delete')) { ?>
<!-- Modal -->
<?php echo form_open("estampillas/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar EL estampilla?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->estm_id; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este estampilla <br>
        ¿Realmente desea eliminarla?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Confirmar</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>
<?php } ?>

  <script type="text/javascript">
    //style selects
    var config = {
      '#municipioid'  : {disable_search_threshold: 10},
      '#bancoid'  : {disable_search_threshold: 10},
      '#tributarioid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>
  <script type="text/javascript">
    $("#file").fileinput({
      <?php
        if ($result->estm_rutaimagen != '')
        {
          ?>
          initialPreview: ["<a href='<?php echo base_url().$result->estm_rutaimagen; ?>' target='_blank'><img src='<?php echo base_url().$result->estm_rutaimagen; ?>' class='file-preview-image' alt='The Moon' title=''></a>"],
          initialCaption: "",
          <?php
        }
      ?>
        showCaption: false,
        browseClass: "btn btn-default btn-sm",
        browseLabel: "Cargar imagen",
        showUpload: false,
        showRemove: false,
    });

</script>