<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div id="infoMessage"><?php if (isset($message)) { echo $message; } ?></div>

<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">

                        <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h1><?php echo lang('create_user_heading');?></h1></div>
                            <div class="panel-body">
                                <div class="col-md-5">
                           
                                  <fieldset>
                                    <?php echo form_open("users/create_user",'role="form"');?>
                                         <legend>Datos Personales</legend>
                                         <div class="form-group">
                                                 <label for="id">Identificación</label>
                                                 <input class="form-control" id="id" type="text" name="id" value="<?php echo set_value('id'); ?>" required="required" />
                                                 <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                         </div>
                                         <div class="form-group">
                                                <label for="id">Apellidos</label>
                                                 <input class="form-control" id="apellidos" type="text" name="apellidos" value="<?php echo set_value('apellidos'); ?>" required="required" />
                                                <?php echo form_error('apellidos','<span class="text-danger">','</span>'); ?>
                                         </div>
                                          <div class="form-group">
                                                 <label for="id">Nombres</label>
                                                 <input class="form-control" id="nombres" type="text" name="nombres" value="<?php echo set_value('nombres'); ?>" required="required" />
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
                                                                    <option value="<?= $empresa->id ?>"><?= $empresa->nombre ?></option>
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
                                    <div class="form-group">
                                           <label for="password">Contraseña</label>
                                           <input class="form-control" id="password" type="password" name="password" required="required" />
                                           <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="password_confirm">Confirmar contraseña</label>
                                           <input class="form-control" id="password_confirm" type="password" name="password_confirm" required="required"/>
                                           <?php echo form_error('password_confirm','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="perfilid">Perfil</label>
                                           <select class="form-control" id="perfilid" name="perfilid" required="required" >
                                             <option value="">Seleccione...</option>
                                             <?php  foreach($perfiles as $row) { ?>
                                             <option value="<?php echo $row->perf_id; ?>"><?php echo $row->perf_nombre; ?></option>
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
                                           <input class="form-control" id="telefono" type="number" name="telefono" value="<?php echo set_value('telefono'); ?>" required="required" />
                                           <?php echo form_error('telefono','<span class="text-danger">','</span>'); ?>
                                    </div>
                            </div>

                            <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                           <label for="email">Email</label>
                                           <input class="form-control" id="email" type="email" name="email" value="<?php echo set_value('email'); ?>" required="required" />
                                           <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                    </div>
                            </div>
                            </fieldset>
                            </div>
                                                        
                            <div class="col-md-12">
                                       <?php  echo anchor('users', '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-default"'); ?>
                                      <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                            </div>
                            <?php echo form_close();?>
                          </div>
                        </div> 
                    </div>
                                                                              
                </div> 
            </div>
      </div>

    <script type="text/javascript">
        $(function () {
                $('#perfilid').change(changePerfil);
                $('#perfilid').change();
        });

        function changePerfil()
        {
            if($(this).val() == '<?= $perfil_liquidador ?>') {
                $('#empresa').closest('.form-group').show();
            }else{
                $('#empresa').closest('.form-group').hide();
            }
        }
    </script>
