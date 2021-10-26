<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/users/edit_user.php
*   Descripcion:       permite editar un usuario
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-07-16
*
*/

 ?>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">

                      <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h1>Editar Usuario</h1></div> 
                            <div class="panel-body">
                                <div class="col-md-5">                               
                                  <fieldset>
                                    <?php echo form_open(current_url(),'role="form"');?>
                                         <legend>Datos Personales</legend>
                                         <div class="form-group">
                                                 <label for="id">Identificación</label>
                                                 <input class="form-control" id="id" type="text" name="id" value="<?php echo $result->id; ?>" readonly/>                                                 
                                         </div>
                                         <div class="form-group">
                                                <label for="id">Apellidos</label>
                                                 <input class="form-control" id="apellidos" type="text" name="apellidos" value="<?php echo $result->last_name; ?>" required="required" />
                                                <?php echo form_error('apellidos','<span class="text-danger">','</span>'); ?>
                                         </div>
                                          <div class="form-group">
                                                 <label for="id">Nombres</label>
                                                 <input class="form-control" id="nombres" type="text" name="nombres" value="<?php echo $result->first_name; ?>" required="required" />
                                                 <?php echo form_error('nombres','<span class="text-danger">','</span>'); ?>
                                          </div>
                                            <div class="form-group">
                                                <label for="empresa">Empresa</label>
                                                <select class="form-control" id="empresa" name="empresa">
                                                    <option value="">Seleccione...</option>
                                                        <?php
                                                            foreach($empresas as $empresa)
                                                            {
                                                                ?>
                                                                    <option value="<?= $empresa->id ?>" <?= $result->id_empresa == $empresa->id ? 'selected' : '' ?>><?= $empresa->nombre ?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                <?php echo form_error('empresa','<span class="text-danger">','</span>'); ?>
                                            </div>
                                    </fieldset>                             
                                </div>

                              <div class="col-md-5 col-md-offset-1">                           
                              <fieldset>
                                    <legend>Seguridad</legend>
                                    <p class="help-block">Deje estos campos vacíos si no quiere cambiar la contraseña</p>
                                    <div class="form-group">
                                           <label for="password">Contraseña</label>
                                           <input class="form-control" id="password" type="password" name="password" />
                                           <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="password_confirm">Confirmar contraseña</label>
                                           <input class="form-control" id="password_confirm" type="password" name="password_confirm" />
                                           <?php echo form_error('password_confirm','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="perfilid">Perfil</label>
                                           <select class="form-control" id="perfilid" name="perfilid" required="required" >
                                             <option value="">Seleccione...</option>
                                             <?php  foreach($perfiles as $row) { ?>
                                                 <?php if ($row->perf_id==$result->perfilid) { ?>
                                                 <option selected value="<?php echo $row->perf_id; ?>" ><?php echo $row->perf_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->perf_id; ?>"><?php echo $row->perf_nombre; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('perfilid','<span class="text-danger">','</span>'); ?>
                                    </div>
                              </fieldset>                                                                        
                              </div>                            
                            
                            <div class="col-xs-12">
                            <fieldset>
                            <div class="col-md-12">                                                                                  
                                    <legend>Contacto</legend>
                            </div>

                            <div class="col-md-5">                                      
                                    <div class="form-group">
                                           <label for="email">Telefono</label>
                                           <input class="form-control" id="telefono" type="number" name="telefono" value="<?php echo $result->phone; ?>" required="required" />
                                           <?php echo form_error('telefono','<span class="text-danger">','</span>'); ?>
                                    </div>
                            </div>

                            <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                           <label for="email">Email</label>
                                           <input class="form-control" id="email" type="email" name="email" value="<?php echo $result->email; ?>" required="required" />
                                           <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                    </div>
                            </div>
                            </fieldset>
                            </div>
                                                        
                            <div class="col-md-12">
                                     <?php  echo anchor('users', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <a class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash-o"></i> Eliminar</a>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                            </div>

                            <?php echo form_close();?>
                          </div>
                        </div> 
                    </div>                      

          </div> 
      </div>
</div>

<!-- Modal -->
<?php echo form_open("users/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar El usuario?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->id; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este usuario <br>
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

    perfiles_empresa = JSON.parse('<?= json_encode($perfiles_empresa) ?>');

    $(function () {
        $('#perfilid').change(changePerfil);
        $('#perfilid').change();
    });

    function changePerfil()
    {
        if( perfiles_empresa.includes(Number($(this).val())) ) {
            $('#empresa').closest('.form-group').show();
        }else{
            $('#empresa').closest('.form-group').hide();
        }
    }
</script>