<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/bancos/bancos_list.php
*   Descripcion:       permite editar un banco
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
                           <div class="panel-heading"><h1>Editar banco</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Id</label>
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->banc_id; ?>"/>
                                           <p><?php echo $result->banc_id; ?></p>
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo $result->banc_nombre; ?>" required="required" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="descripcion">Descripción</label>
                                           <textarea class="form-control" id="descripcion" type="descripcion" name="descripcion" maxlength="500"><?php echo $result->banc_descripcion; ?></textarea>
                                           <?php echo form_error('descripcion','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="pull-right">
                                     <?php  echo anchor('bancos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                     
                                     <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/delete')) { ?>
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

<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/delete')) { ?>
<!-- Modal -->
<?php echo form_open("bancos/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar EL tipo banco?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->banc_id; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este tipo banco <br>
        ¿Realmente desea eliminarlo?
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