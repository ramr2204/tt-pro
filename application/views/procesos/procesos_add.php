<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1>Crear un nuevo proceso</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url()); ?>
                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="nombre" name="nombre" value="<?php echo set_value('nombre'); ?>" required="required" maxlength="128" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="estadoid">Estado</label>
                                           <select class="form-control" id="estadoid" name="estadoid" required="required" >
                                             <?php  foreach($estados as $row) { ?>
                                             <option value="<?php echo $row->esta_id; ?>"><?php echo $row->esta_nombre; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('estadoid','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="descripcion">Descripción</label>
                                           <textarea class="form-control" id="descripcion" type="descripcion" name="descripcion" maxlength="1000"><?php echo set_value('descripcion'); ?></textarea>
                                           <?php echo form_error('descripcion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="pull-right">
                                     <?php  echo anchor('procesos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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