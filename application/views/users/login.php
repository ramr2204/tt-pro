<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>



<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-3 column">
                        </div>
                        <div class="col-md-6 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1><?php echo lang('login_heading');?></h1></div>
                             <div class="panel-body">
                             
                              <p><?php echo lang('login_subheading');?></p>
                              <?php echo form_open("users/login");?>

                                    <div class="form-group">
                                           <label for="identity">Email</label>
                                           <input class="form-control" id="identity" type="email" name="identity" value="<?php echo set_value('identity'); ?>" required="required" maxlength="128" />
                                           <?php echo form_error('identity','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="password">Password</label>
                                           <input class="form-control" id="password" type="password" name="password" value="" required="required" maxlength="128" />
                                           <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="checkbox">
                                      <?php echo lang('login_remember_label', 'remember');?>
                                      <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                                    </div>

                                    <div class="pull-right">
                                    <button type="submit" class="btn btn-primary btn-primary2 "><i class="fa fa-share"></i> Ingresar</button>
                                    </div>
                                <?php echo form_close();?>
                                <p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
                              </div>
                             </div>
       
                        </div>
                        <div class="col-md-3 column">
                        </div>
                  </div> 
            </div>
      </div>