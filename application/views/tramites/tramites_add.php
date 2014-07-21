<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/tramites/tramites_add.php
*   Descripcion:       permite crear un nuevo tramites
*   Fecha Creacion:    21/jul/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-07-21
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
                            <div class="panel-heading"><h1>Crear un trámite</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url()); ?>

                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo set_value('nombre'); ?>" required="required" maxlength="200" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="descripcion">Descripción</label>
                                           <textarea class="form-control" id="descripcion" type="descripcion" name="descripcion" maxlength="1000"><?php echo set_value('descripcion'); ?></textarea>
                                           <?php echo form_error('descripcion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                     

                                      Eliga el porcentaje de salario mínimo para cada estampilla

                                      <table class="table table-condensed">
                                       <tr>
                                         <td class="text-center"><strong>Estampilla</strong></td>
                                         <td class="text-center"><strong>Porcentaje</strong></td>
                                       </tr>
                                       <?php $x=0;  ?> 
                                       <?php  foreach($estampillas as $row) { ?>
                                       <?php $x++;  ?> 
                                          <tr>
                                            <td><?php echo $row->estm_nombre; ?> <?php echo $x; ?></td>
                                            <td>
                                            <input type="hidden" name="estampillaid<?php echo $x; ?>" value="<?php echo $row->estm_id; ?>">
                                            <input class="form-control" id="porcentaje<?php echo $x; ?>" type="number" name="porcentaje<?php echo $x; ?>" value="<?php echo set_value('porcentaje'.$x); ?>" maxlength="3" min="0" step="0.1" />
                                            <?php echo form_error('porcentaje'.$x,'<span class="text-danger">','</span>'); ?>
                                            </td>
                                          </tr>

                                       <?php   } ?>
                                      </table>
                                      <input type="hidden" name="numero" value="<?php echo $x; ?>">




                                    <div class="pull-right">
                                     <?php  echo anchor('tramites', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
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

<ul id="porcentajes">
  
</ul>

