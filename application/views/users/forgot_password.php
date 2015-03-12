<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/menus/menus_add.php
*   Descripcion:       permite crear un nuevo menú
*   Fecha Creacion:    30/jul/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-07-30
*
*/

?>
     <div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-3 column">
                        </div>
                        <div class="col-md-6 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1><?php echo lang('forgot_password_heading');?></h1></div>
                             <div class="panel-body">
                             
                              <?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?>
                              <?php echo form_open("users/forgot_password");?>

                                    <div class="form-group">
                                           <label for="email">Email</label>
                                           <input class="form-control" id="email" type="email" name="email" value="<?php echo set_value('email'); ?>" required="required" maxlength="128" />
                                           <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="pull-right">
                                     <?php  echo anchor('users/login', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-share"></i> Enviar</button>
                                    </div>
                                <?php echo form_close();?>
                              
                              </div>
                             </div>
       
                        </div>
                        <div class="col-md-3 column">
                        </div>
                  </div> 
            </div>
      </div>


