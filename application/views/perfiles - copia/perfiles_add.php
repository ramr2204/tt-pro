<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1>Crear un nuevo perfil</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url()); ?>
                                    <div class="form-group">
                                           <label for="nombreperfil">Nombre</label>
                                           <input class="form-control" id="nombreperfil" type="nombreperfil" name="nombreperfil" value="<?php echo set_value('nombreperfil'); ?>" required="required" maxlength="128" />
                                           <?php echo form_error('nombreperfil','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="descripcionperfil">Descripción</label>
                                           <textarea class="form-control" id="descripcionperfil" type="descripcionperfil" name="descripcionperfil" maxlength="1000"><?php echo set_value('descripcionperfil'); ?></textarea>
                                           <?php echo form_error('descripcionperfil','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="pull-right">
                                     <?php  echo anchor('perfiles', '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-default"'); ?>
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