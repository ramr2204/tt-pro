<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/menus/menus_list.php
*   Descripcion:       permite editar un menú
*   Fecha Creacion:    12/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-12
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
                           <div class="panel-heading"><h1>Editar menú</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Id</label>
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->menu_id; ?>"/>
                                           <p><?php echo $result->menu_id; ?></p>
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo $result->menu_nombre; ?>" required="required" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="controlador">Controlador</label>
                                           <input class="form-control" id="controlador" type="text" name="controlador" value="<?php echo $result->menu_controlador; ?>" required="required" />
                                           <?php echo form_error('controlador','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="metodo">Método</label>
                                           <input class="form-control" id="metodo" type="text" name="metodo" value="<?php echo $result->menu_metodo; ?>" required="required" />
                                           <?php echo form_error('metodo','<span class="text-danger">','</span>'); ?>
                                    </div>
                                      
                                     <div class="form-group">
                                           <label for="moduloid">Módulo</label>
                                           <select class="form-control" id="moduloid" name="moduloid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($modulos as $row) { ?>
                                                 <?php if ($row->modu_id==$result->menu_moduloid) { ?>
                                                 <option selected value="<?php echo $row->modu_id; ?>" ><?php echo $row->modu_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->modu_id; ?>"><?php echo $row->modu_nombre; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('moduloid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                     <div class="form-group">
                                           <label for="estadoid">Estado</label>
                                           <select class="form-control" id="estadoid" name="estadoid" required="required" >
                                             <?php  foreach($estados as $row) { ?>
                                                 <?php if ($row->esta_id==$result->menu_estadoid) { ?>
                                                  <option selected value="<?php echo $row->esta_id; ?>" ><?php echo $row->esta_nombre; ?></option>
                                                 <?php } else { ?>
                                                  <option value="<?php echo $row->esta_id; ?>"><?php echo $row->esta_nombre; ?></option>

                                                 <?php } ?>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('estadoid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="descripcion">Descripción</label>
                                           <textarea class="form-control" id="descripcion" type="descripcion" name="descripcion" maxlength="500"><?php echo $result->menu_descripcion; ?></textarea>
                                           <?php echo form_error('descripcion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="pull-right">
                                     <?php  echo anchor('menus', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <a class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash-o"></i> Eliminar</a>
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

<!-- Modal -->
<?php echo form_open("menus/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar El menú?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->menu_id; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este menú <br>
        ¿Realmente desea eliminarlo?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Confirmar</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>


   <script type="text/javascript">
    //style selects
    var config = {
      '#moduloid'  : {disable_search_threshold: 10},
      '#estadoid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>