<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-3 column">
                        </div>
                        <div class="col-md-6 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1><?php echo lang('reset_password_heading');?></h1></div>
                             <div class="panel-body">
                             
                              <?php echo form_open('users/reset_password/' . $code);?>

                                    <div class="form-group">
                                           <label for="new_password">Nueva contraseña</label>
                                           <input class="form-control" id="new_password" type="password" name="new_password" value="" required="required" maxlength="128" />
                                           <?php echo form_error('new_password','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="new_confirm">Confirmar contraseña</label>
                                           <input class="form-control" id="new_confirm" type="password" name="new_confirm" value="" required="required" maxlength="128" />
                                           <?php echo form_error('new_confirm','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <?php echo form_input($user_id);?>
	                                <?php echo form_hidden($csrf); ?>

                                    <div class="pull-right">
                                    <button type="submit" class="btn btn-primary btn-primary2 "><i class="fa fa-check-square-o"></i> Aceptar</button>
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
