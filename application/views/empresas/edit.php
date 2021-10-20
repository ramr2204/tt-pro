<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div id="infoMessage"><?php if (isset($message)) { echo $message; } ?></div>
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="row clearfix">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h1><?php echo lang('create_user_heading');?></h1></div>
                        <div class="panel-body">
                            <div class="col-md-12">
                                <?php echo form_open("empresas/edit",'role="form"');?>
                                <div class="panel-body">
                                    <?php echo form_open(current_url(),'role="form"');?>
                                    <legend>Editar Datos Empresa</legend>
                                    <input type="hidden" name="id" value="<?php echo $result->id; ?>">
                                    <div class="col-md-6">
                                        <label>Nit</label>
                                        <input class="form-control" name="nit" value="<?php echo $result->nit; ?>" required="required" />
                                        <?php echo form_error('nit','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id">Nombre</label>
                                        <input class="form-control" name="nombre" value="<?php echo $result->nombre; ?>" required="required" />
                                        <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id">Email</label>
                                        <input class="form-control" name="email" value="<?php echo $result->email; ?>" required="required" />
                                        <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id">Direccion</label>
                                        <input class="form-control" name="direccion" value="<?php echo $result->direccion; ?>" required="required" />
                                        <?php echo form_error('direccion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id">Telefono</label>
                                        <input class="form-control" name="telefono" value="<?php echo $result->telefono; ?>" required="required" />
                                             <?php echo form_error('telefono','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id">Nombre Representante</label>
                                        <input class="form-control" name="nombre_representante" value="<?php echo $result->nombre_representante; ?>" required="required" />
                                        <?php echo form_error('nombre_representante','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id">Identificador Representante</label>
                                        <input class="form-control" name="identificador_representante" value="<?php echo $result->identificador_representante; ?>" required="required" />
                                        <?php echo form_error('identificador_representante','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="municipioid">Municipio</label>
                                        <select class="form-control" id="municipioid" name="id_municipio" required="required" >
                                            <option value="0">Seleccione...</option>
                                            <?php  foreach($municipios as $row) { ?>
                                            <option <?php if($result->id_municipio == $row->muni_id){ echo 'selected'; } ?> value="<?php echo $row->muni_id; ?>"><?php echo $row->muni_nombre.' ( '.$row->depa_nombre.' )'; ?></option>
                                            <?php   } ?>
                                        </select>
                                       <?php echo form_error('id_municipio','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="pull-right" style="margin-top: 12px">
                                        <?php  echo anchor('empresas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                         
                                        <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('empresas/delete')) { ?>
                                            <a class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash-o"></i> Eliminar</a>
                                         <?php } ?>

                                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                                    </div>
                                </div>                        
                            </div>                    
                            <?php echo form_close();?>
                          </div>
                    </div> 
                </div>                                                            
            </div> 
        </div>
    </div>

<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('empresas/delete')) { ?>
    <!-- Modal -->
    <?php echo form_open("empresas/delete",'role="form"');?>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar EL régimen?</h4>
          </div>
          <div class="modal-body">
             <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->id; ?>"/>
            Si oprime confirmar no podrá recuperar la información de este régimen <br>
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
      '#municipioid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
  </script>

