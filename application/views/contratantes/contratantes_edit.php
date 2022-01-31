<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            contratantes_edit
*   Ruta:              /application/views/contratantes/contratantes_edit.php
*   Descripcion:       permite editar un contratante
*   Fecha Creacion:    19/dic/2018
*   @author           Michael Angelo Ortiz Trivinio <engineermikeortiz@gmail.com>
*   @version          2018-12-19
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
                           <div class="panel-heading"><h1>Editar contratante</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Id</label>
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->id; ?>"/>
                                           <p><?php echo $result->id; ?></p>
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="tipocontratistaid">Tipo de contratante</label>
                                           <select class="form-control" id="tipocontratistaid" name="tipocontratistaid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($tiposcontratistas as $row) { ?>
                                                 <?php if ($row->tpco_id==$result->tipocontratistaid) { ?>
                                                 <option selected value="<?php echo $row->tpco_id; ?>" ><?php echo $row->tpco_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->tpco_id; ?>"><?php echo $row->tpco_nombre; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('tipocontratistaid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="nit">NIT</label>
                                           <input class="form-control" id="nit" type="text" name="nit" value="<?php echo $result->nit; ?>" required="required" />
                                           <?php echo form_error('nit','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo $result->nombre; ?>" required="required" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="id">Email</label>
                                        <input class="form-control" name="email" value="<?php echo $result->email; ?>" required="required" />
                                        <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="id">Direccion</label>
                                        <input class="form-control" name="direccion" value="<?php echo $result->direccion; ?>" required="required" />
                                        <?php echo form_error('direccion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="id">Telefono</label>
                                        <input class="form-control" name="telefono" value="<?php echo $result->telefono; ?>" required="required" />
                                             <?php echo form_error('telefono','<span class="text-danger">','</span>'); ?>
                                    </div>
                                      
                                    <div class="form-group">
                                           <label for="municipioid">Municipio</label>
                                           <select class="form-control" id="municipioid" name="municipioid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($municipios as $row) { ?>
                                                 <?php if ($row->muni_id==$result->municipioid) { ?>
                                                 <option selected value="<?php echo $row->muni_id; ?>" ><?php echo $row->muni_nombre.' ('.$row->depa_nombre.')'; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->muni_id; ?>"><?php echo $row->muni_nombre.' ('.$row->depa_nombre.')'; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('municipioid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="pull-right">
                                     <?php  echo anchor('contratantes', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                     
                                     <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/delete')) { ?>
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

<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/delete')) { ?>
<!-- Modal -->
<?php echo form_open("contratantes/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar el contratante?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->id; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este contratante <br>
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
      '#tipocontratistaid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>