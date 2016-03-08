<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/parametros/parametros_edit.php
*   Descripcion:       permite editar un parámetros
*   Fecha Creacion:    19/jul/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-07-19
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
                           <div class="panel-heading"><h1>Editar parámetros</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>

                                    <div class="form-group">
                                           <label for="redondeo">Número de cifras para redondeo de liquidaciones</label>
                                           <input class="form-control" id="redondeo" type="number" name="redondeo" value="<?php echo $result->para_redondeo; ?>" required="required" />
                                           <?php echo form_error('redondeo','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="salariominimo">Valor actual del salario mínimo</label>
                                           <input class="form-control" id="salariominimo" type="number" name="salariominimo" value="<?php echo $result->para_salariominimo; ?>" required="required" />
                                           <?php echo form_error('salariominimo','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <?php 
                                                if($result->para_contingencia == 1)
                                                {
                                                    echo '<input type="checkbox" name="contingencia" value="SI" checked/> Rotulos de Contingencia';
                                                }else
                                                    {
                                                        echo '<input type="checkbox" name="contingencia" value="SI"/> Rotulos de Contingencia';
                                                    }
                                            ?>                                            
                                        </label>
                                        <?php echo form_error('contingencia','<span class="text-danger">','</span>'); ?>
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
