<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            tramites
*   Ruta:              /application/views/tramites/tramites_list.php
*   Descripcion:       permite editar un trámite
*   Fecha Creacion:    21/jul/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-07-21
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
                           <div class="panel-heading"><h1>Editar trámite</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Id</label>
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->tram_id; ?>"/>
                                           <p><?php echo $result->tram_id; ?></p>
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo $result->tram_nombre; ?>" required="required" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="observaciones">Observacioines</label>
                                           <textarea class="form-control" id="observaciones" type="observaciones" name="observaciones" maxlength="500"><?php echo $result->tram_observaciones; ?></textarea>
                                           <?php echo form_error('observaciones','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                      <table class="table table-condensed">
                                       <tr>
                                         <td class="text-center"><strong>Estampilla</strong></td>
                                         <td class="text-center"><strong>Porcentaje</strong></td>
                                       </tr>
                                       <?php $x=0;  ?> 
                                       <?php  foreach($estampillas as $row) { ?>
                                       <?php $x++;  ?> 
                                          <tr>
                                            <td><?php echo $row->estm_nombre; ?> </td>
                                            <td>
                                            <input type="hidden" name="estampillaid<?php echo $x; ?>" value="<?php echo $row->estm_id; ?>">
                                            <input type="hidden" name="estiid<?php echo $x; ?>" value="<?php echo $row->estr_id; ?>">
                                            <input class="form-control" id="porcentaje<?php echo $x; ?>" type="number" name="porcentaje<?php echo $x; ?>"  maxlength="3" min="0" step="0.1" value="<?php echo $row->estr_porcentaje; ?>" />
                                            <?php echo form_error('porcentaje'.$x,'<span class="text-danger">','</span>'); ?>
                                            </td>
                                          </tr>

                                       <?php   } ?>
                                      </table>
                                      <input type="hidden" name="numero" value="<?php echo $x; ?>">
                                    <div class="pull-right">
                                     <?php  echo anchor('tramites', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                     
                                     <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tramites/delete')) { ?>
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

<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tramites/delete')) { ?>
<!-- Modal -->
<?php echo form_open("tramites/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar EL trámite?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->tram_id; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este trámite <br>
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