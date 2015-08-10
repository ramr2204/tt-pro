<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/items/items_edit.php
*   Descripcion:       permite modificar un item para ordenanza
*   Fecha Creacion:    10/Ago/2015
*   @author            Mike Ortiz <engineermikeortiz@gmail.com>
*   @version           2015-08-10
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
                            <div class="panel-heading"><h1>Editar Item</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(base_url().'index.php/items/edit/<?php echo $result->itod_id; ?>'); ?>

                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo $item->itod_nombre; ?>" required="required" maxlength="128" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="tabla">Tabla</label>
                                           <input class="form-control" id="tabla" type="text" name="tabla" value="<?php echo $item->itod_tabla; ?>" required="required" maxlength="128" />
                                           <?php echo form_error('tabla','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="key">Key</label>
                                           <input class="form-control" id="key" type="text" name="key" value="<?php echo $item->itod_campoid; ?>" required="required" maxlength="128" />
                                           <?php echo form_error('key','<span class="text-danger">','</span>'); ?>
                                    </div>                                                                  

                                    <div class="form-group">
                                           <label for="descripcion">Descripción</label>
                                           <textarea class="form-control" id="descripcion" name="descripcion" maxlength="1000"><?php echo $item->itod_descripcion; ?></textarea>
                                           <?php echo form_error('descripcion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="pull-right">
                                     <?php  echo anchor('items', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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