<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            contratantes_add
*   Ruta:              /application/views/contratantes/contratantes_add.php
*   Descripcion:       permite crear un nuevo contratante
*   Fecha Creacion:    18/dic/2018
*   @author            Michael Angelo Ortiz Trivinio <engineermikeortiz@gmail.com>
*   @version           2018-12-08
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
                            <div class="panel-heading"><h1>Crear un nuevo contratante</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url()); ?>
                                     <div class="form-group">
                                           <label for="tipocontratistaid">Tipo de contratante</label>
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
                                        <label for="id">Email</label>
                                        <input class="form-control" name="email" value="<?php echo set_value('email'); ?>" required="required" />
                                        <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="id">Direccion</label>
                                        <input class="form-control" name="direccion" value="<?php echo set_value('direccion'); ?>" required="required" />
                                        <?php echo form_error('direccion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="id">Telefono</label>
                                        <input class="form-control" name="telefono" value="<?php echo set_value('telefono'); ?>" required="required" />
                                            <?php echo form_error('telefono','<span class="text-danger">','</span>'); ?>
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

                                     <div class="form-group">
                                           <label for="regimenid">Tipo de régimen</label>
                                           <select class="form-control" id="regimenid" name="regimenid" required="required" >
                                           <option value="0">Seleccione...</option>
                                             <?php  foreach($regimenes as $row) { ?>
                                             <option value="<?php echo $row->regi_id; ?>"><?php echo $row->regi_nombre; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('regimenid','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="pull-right">
                                     <?php  echo anchor('contratantes', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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
      '#municipioid'  : {disable_search_threshold: 10},
      '#regimenid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>