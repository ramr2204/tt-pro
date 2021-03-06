
<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                           <div class="panel-heading"><h1>Editar proceso</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>
                                    <div class="form-group">
                                           <label for="id">Id</label>
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->proc_id; ?>"/>
                                           <p><?php echo $result->proc_id; ?></p>
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="nombre">Nombre</label>
                                           <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo $result->proc_nombre; ?>" required="required" />
                                           <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="estadoid">Estado</label>
                                           <select class="form-control" id="estadoid" name="estadoid" required="required" >
                                             <?php  foreach($estados as $row) { ?>
                                                 <?php if ($row->esta_id==$result->proc_estadoid) { ?>
                                                  <option selected value="<?php echo $row->esta_id; ?>" ><?php echo $row->esta_nombre; ?></option>
                                                 <?php } else { ?>
                                                  <option value="<?php echo $row->esta_id; ?>"><?php echo $row->esta_nombre; ?></option>

                                                 <?php } ?>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('estadoid','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="descripcion">Descripci??n</label>
                                           <textarea class="form-control" id="descripcion" type="descripcion" name="descripcion" maxlength="500"><?php echo $result->proc_descripcion; ?></textarea>
                                           <?php echo form_error('descripcion','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="pull-right">
                                     <?php  echo anchor('procesos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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
<?php echo form_open("procesos/delete",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">??Confirma que quiere eliminar el proceso?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->proc_id; ?>"/>
        Si oprime confirmar no podr?? recuperar la informaci??n de este proceso <br>
        ??Realmente desea eliminarlo?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Confirmar</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>