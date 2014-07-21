<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/users/change_password.php
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

                                    <p class="help-block">Deje estos campos vacíos si no quiere cambiar la contraseña</p>
                                    <div class="form-group">
                                           <label for="password">Contraseña actual</label>
                                           <input class="form-control" id="oldpassword" type="password" name="oldpassword" />
                                           <?php echo form_error('oldpassword','<span class="text-danger">','</span>'); ?>
                                    </div>
                  
                                   
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
