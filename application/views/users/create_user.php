<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<h1><?php echo lang('create_user_heading');?></h1>
<div id="infoMessage"><?php if (isset($message)) { echo $message; } ?></div>

<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                             <div class="panel-body">
                              <?php echo form_open("users/create_user",'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Identificación</label>
                                           <input class="form-control" id="id" type="text" name="id" value="<?php echo set_value('id'); ?>" required="required" />
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="email">Email</label>
                                           <input class="form-control" id="email" type="email" name="email" value="<?php echo set_value('email'); ?>" required="required" />
                                           <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="password">Contraseña</label>
                                           <input class="form-control" id="password" type="password" name="password" required="required" />
                                           <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="password_confirm">Confirmar contraseña</label>
                                           <input class="form-control" id="password_confirm" type="password" name="password_confirm" required="required"/>
                                           <?php echo form_error('password_confirm','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="perfilid">Perfil</label>
                                           <select class="form-control" id="perfilid" name="perfilid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <option value="1">Administrador</option>
                                             <?php  foreach($perfiles as $row) { ?>
                                             <option value="<?php echo $row->perf_id; ?>"><?php echo $row->perf_nombre; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('perfilid','<span class="text-danger">','</span>'); ?>
                                    </div>


                                    <div class="pull-right">
                                     <?php  echo anchor('users', '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-default"'); ?>
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


