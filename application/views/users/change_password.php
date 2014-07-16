<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/users/edit_user.php
*   Descripcion:       permite editar un usuario
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-07-16
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
                           <div class="panel-heading"><h1>Editar mis datos</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>
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

                                     <div class="form-group">
                                           <label for="perfilid">Perfil</label>
                                           <select class="form-control" id="perfilid" name="perfilid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($perfiles as $row) { ?>
                                                 <?php if ($row->perf_id==$result->perfilid) { ?>
                                                 <option selected value="<?php echo $row->perf_id; ?>" ><?php echo $row->perf_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->perf_id; ?>"><?php echo $row->perf_nombre; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('perfilid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="password">Contraseña actual</label>
                                           <input class="form-control" id="password" type="password" name="password" />
                                           <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
                                    </div>
                  
                                    <p class="help-block">Deje estos campos vacíos si no quiere cambiar la contraseña</p>
                                    <div class="form-group">
                                           <label for="password">Contraseña nueva</label>
                                           <input class="form-control" id="password" type="password" name="password" />
                                           <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
                                    </div>
                  
                                    <div class="form-group">
                                           <label for="password_confirm">Confirmar contraseña nueva</label>
                                           <input class="form-control" id="password_confirm" type="password" name="password_confirm"/>
                                           <?php echo form_error('password_confirm','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="pull-right">
                                     <?php  echo anchor('users', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <a class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash-o"></i> Eliminar</a>
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


















<h1><?php echo lang('change_password_heading');?></h1>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/change_password");?>

      <p>
            <?php echo lang('change_password_old_password_label', 'old_password');?> <br />
            <?php echo form_input($old_password);?>
      </p>

      <p>
            <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label> <br />
            <?php echo form_input($new_password);?>
      </p>

      <p>
            <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?> <br />
            <?php echo form_input($new_password_confirm);?>
      </p>

      <?php echo form_input($user_id);?>
      <p><?php echo form_submit('submit', lang('change_password_submit_btn'));?></p>

<?php echo form_close();?>
