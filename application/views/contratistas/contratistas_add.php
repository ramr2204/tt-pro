<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratistas/contratistas_add.php
*   Descripcion:       permite crear un nuevo contratista
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-12
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
                            <div class="panel-heading"><h1>Crear un nuevo contratista</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url()); ?>
                                     <div class="form-group">
                                           <label for="tipocontratistaid">Tipo de contratista</label>
                                           <select class="form-control" id="tipocontratistaid" name="tipocontratistaid" required="required" >
                                           <option value="0">Seleccione...</option>
                                             <?php  foreach($tiposcontratistas as $row) { ?>
                                             <option value="<?php echo $row->tpco_id; ?>"><?php echo $row->tpco_nombre; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('tipocontratistaid','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="nit">NIT</label>
                                           <input class="form-control" id="nit" type="nit" name="nit" value="<?php echo set_value('nit'); ?>" required="required" maxlength="100" />
                                           <?php echo form_error('nit','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="nombre" name="nombre" value="<?php echo set_value('nombre'); ?>" required="required" maxlength="128" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="direccion">Dirección</label>
                                           <input class="form-control" id="direccion" type="direccion" name="direccion" value="<?php echo set_value('direccion'); ?>" required="required" maxlength="256" />
                                           <?php echo form_error('direccion','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="telefono">Telefono</label>
                                           <input class="form-control" id="telefono" type="telefono" name="telefono" value="<?php echo set_value('telefono'); ?>" required="required" maxlength="15" />
                                           <?php echo form_error('telefono','<span class="text-danger">','</span>'); ?>
                                    </div>

                                   <div class="form-group">
                                          <label for="email">Email</label>
                                          <input class="form-control" id="email" type="email" name="email" value="<?php echo set_value('email'); ?>" required="required" />
                                          <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                   </div>

                                    <div class="form-group">
                                           <label for="municipioid">Municipio</label>
                                           <select class="form-control" id="municipioid" name="municipioid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($municipios as $row) { ?>
                                             <option value="<?php echo $row->muni_id; ?>"><?php echo $row->muni_nombre.' ( '.$row->depa_nombre.' )'; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('municipioid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="pull-right">
                                     <?php  echo anchor('contratistas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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

  <script type="text/javascript">
    //style selects
    var config = {
      '#tipocontratistaid'  : {disable_search_threshold: 10},
      '#municipioid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>
