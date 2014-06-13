
<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                           <div class="panel-heading"><h1>Editar perfil</h1></div>
                             <div class="panel-body">
                              <?php echo form_open("perfiles/edit",'role="form"');?>
                                    <div class="form-group">
                                           <label for="idperfil">Id</label>
                                           <input class="form-control" id="idperfil" type="hidden" name="idperfil" value="<?php echo $result->idperfil; ?>"/>
                                           <p><?php echo $result->idperfil; ?></p>
                                           <?php echo form_error('idperfil','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="nombreperfil">Nombre</label>
                                           <input class="form-control" id="nombreperfil" type="text" name="nombreperfil" value="<?php echo $result->nombreperfil; ?>" required="required" />
                                           <?php echo form_error('nombreperfil','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="descripcionperfil">Descripción</label>
                                           <textarea class="form-control" id="descripcionperfil" type="descripcionperfil" name="descripcionperfil" maxlength="500"><?php echo $result->descripcionperfil; ?></textarea>
                                           <?php echo form_error('descripcionperfil','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="pull-right">
                                     <?php  echo anchor('perfiles', '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-default"'); ?>
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
<?php echo form_open("perfiles/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar el perfil?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="idperfil" type="hidden" name="idperfil" value="<?php echo $result->idperfil; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este perfil <br>
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