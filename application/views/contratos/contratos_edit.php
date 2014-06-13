<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/modulos/modulos_list.php
*   Descripcion:       permite editar un módulo
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
                           <div class="panel-heading"><h1>Editar contratista</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Id</label>
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->cont_id; ?>"/>
                                           <p><?php echo $result->cont_id; ?></p>
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="nit">NIT</label>
                                           <input class="form-control" id="nit" type="text" name="nit" value="<?php echo $result->cont_nit; ?>" required="required" />
                                           <?php echo form_error('nit','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo $result->cont_nombre; ?>" required="required" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>
                                      
                                    <div class="form-group">
                                           <label for="direccion">Dirección</label>
                                           <input class="form-control" id="direccion" type="text" name="direccion" value="<?php echo $result->cont_direccion; ?>" required="required" />
                                           <?php echo form_error('direccion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                      
                                    <div class="form-group">
                                           <label for="municipioid">Municipio</label>
                                           <select class="form-control" id="municipioid" name="municipioid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($municipios as $row) { ?>
                                                 <?php if ($row->muni_id==$result->cont_municipioid) { ?>
                                                 <option selected value="<?php echo $row->muni_id; ?>" ><?php echo $row->muni_nombre.' ('.$row->depa_nombre.')'; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->muni_id; ?>"><?php echo $row->muni_nombre.' ('.$row->depa_nombre.')'; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('municipioid','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="regimenid">Tipo de contratista</label>
                                           <select class="form-control" id="regimenid" name="regimenid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($regimenes as $row) { ?>
                                                 <?php if ($row->regi_id==$result->cont_regimenid) { ?>
                                                 <option selected value="<?php echo $row->regi_id; ?>" ><?php echo $row->regi_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->regi_id; ?>"><?php echo $row->regi_nombre; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('regimenid','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="tributarioid">Tipo tributario</label>
                                           <select class="form-control" id="tributarioid" name="tributarioid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($tributarios as $row) { ?>
                                                 <?php if ($row->trib_id==$result->cont_tributarioid) { ?>
                                                 <option selected value="<?php echo $row->trib_id; ?>" ><?php echo $row->trib_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->trib_id; ?>"><?php echo $row->trib_nombre; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('tributarioid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="pull-right">
                                     <?php  echo anchor('contratistas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                     
                                     <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/delete')) { ?>
                                      <a class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash-o"></i> Eliminar</a>
                                     <?php } ?>

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

<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/delete')) { ?>
<!-- Modal -->
<?php echo form_open("contratistas/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar EL contratista?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->cont_id; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este contratista <br>
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
<?php } ?>

  <script type="text/javascript">
    //style selects
    var config = {
      '#municipioid'  : {disable_search_threshold: 10},
      '#regimenid'  : {disable_search_threshold: 10},
      '#tributarioid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>