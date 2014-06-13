<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<h1><?php echo lang('edit_user_heading');?></h1>
<div id="infoMessage"><?php if (isset($message)) { echo $message; } ?></div>

<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                             <div class="panel-body">
                              <?php echo form_open("users/edit",'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Identificación</label>
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->id; ?>"/>
                                           <p><?php echo $result->id; ?></p>
                                    </div>
                                    <div class="form-group">
                                           <label for="email">Email</label>
                                           <input class="form-control" id="email" type="email" name="email" value="<?php echo $result->email; ?>" required="required" />
                                           <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <p class="help-block">Deje estos campos vacíos si no quiere cambiar la contraseña</p>
                                    <div class="form-group">
                                           <label for="password">Contraseña</label>
                                           <input class="form-control" id="password" type="password" name="password" />
                                           <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
                                    </div>
                  
                                    <div class="form-group">
                                           <label for="password_confirm">Confirmar contraseña </label>
                                           <input class="form-control" id="password_confirm" type="password" name="password_confirm"/>
                                           <?php echo form_error('password_confirm','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="pull-right">
                                     <?php  echo anchor('users', '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-default"'); ?>
                                    <button type="" class="btn btn-danger" data-toggle="modal" data-target="#confirm"><i class="fa fa-trash-o"></i> Eliminar</button>
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


<!-- Modal -->
<div class="modal fade modal-sm" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content panel-primary">
      <div class="modal-header panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i> Eliminar usuario</h4>
      </div>
      <div class="modal-body">
        Si oprime confirmar no podrá recuperar la información de este usuario <br>
        ¿Realmente desea eliminarlo?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="button" class="btn btn-primary"><i class="fa fa-check"></i> Confirmar</button>
      </div>
    </div>
  </div>
</div>