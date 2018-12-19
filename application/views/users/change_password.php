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
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Editar mis datos</h1></div> 
                    <div class="panel-body">
                        <?php echo form_open('users/postEditMe');?>
                        <div class="col-md-5">                               
                            <fieldset>
                                <legend>Datos Personales</legend>
                                <div class="form-group">
                                    <label for="id">Identificación</label>
                                    <input class="form-control" id="id" type="text" name="id" value="<?php echo $result->id; ?>" readonly/>                                                 
                                </div>
                                <div class="form-group">
                                    <label for="id">Apellidos</label>
                                    <input class="form-control" id="apellidos" type="text" name="apellidos" value="<?php echo $result->last_name; ?>" required="required" />
                                    <?php echo form_error('apellidos','<span class="text-danger">','</span>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="id">Nombres</label>
                                    <input class="form-control" id="nombres" type="text" name="nombres" value="<?php echo $result->first_name; ?>" required="required" />
                                    <?php echo form_error('nombres','<span class="text-danger">','</span>'); ?>
                                </div>
                            </fieldset>                             
                        </div>
                        <div class="col-md-5 col-md-offset-1">                           
                            <fieldset>
                                <legend>Seguridad</legend>
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
                            </fieldset>                                                                        
                        </div>                            
                        <div class="col-xs-12">
                            <fieldset>
                            <legend>Contacto</legend>                        
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="email">Telefono</label>
                                    <input class="form-control" id="telefono" type="number" name="telefono" value="<?php echo $result->phone; ?>" required="required" />
                                    <?php echo form_error('telefono','<span class="text-danger">','</span>'); ?>
                                </div>
                            </div>                        
                            <div class="col-md-5 col-md-offset-1">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input class="form-control" id="email" type="email" name="email" value="<?php echo $result->email; ?>" required="required" />
                                    <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                </div>
                            </div>
                            </fieldset>
                        </div>                                
                        <div class="col-xs-12">
                            <?php  echo anchor('liquidaciones/liquidar', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>                                    
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                        </div>
                        <?php echo form_close();?>
                    </div>
                </div> 
            </div>
        </div> 
    </div>
</div>