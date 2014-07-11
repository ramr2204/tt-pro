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
 <br>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-2 column">
                        </div>
                        <div class="col-md-8 column">
                           <div class="panel panel-default">
                           <div class="panel-heading"><h1>Editar contratista</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url(),'role="form"');?>

                                   <div class="form-group">
                                           <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->cntr_id; ?>"/>
                                        
                                           <?php echo form_error('id','<span class="text-danger">','</span>'); ?>
                                    </div>

                                   <div class="col-md-12 column">
                                       <div class="form-group">
                                           <label for="contratistaid">Contratista</label>
                                           <select class="form-control" id="contratistaid" name="contratistaid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($contratistas as $row) { ?>
                                                 <?php if ($row->cont_id==$result->cntr_contratistaid) { ?>
                                                 <option selected value="<?php echo $row->cont_id; ?>" ><?php echo $row->cont_nit.' - '.$row->cont_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->cont_id; ?>"><?php echo $row->cont_nit.' - '.$row->cont_nombre; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('contratistaid','<span class="text-danger">','</span>'); ?>
                                       </div>
                                     </div>

                                     <div class="col-md-6 column">
                                         <div class="form-group">
                                           <label for="tipocontratoid">Tipo de contrato</label>
                                           <select class="form-control" id="tipocontratoid" name="tipocontratoid" required="required" >
                                             <option value="0">Seleccione...</option>
                                             <?php  foreach($tiposcontratos as $row) { ?>
                                                 <?php if ($row->tico_id==$result->cntr_tipocontratoid) { ?>
                                                 <option selected value="<?php echo $row->tico_id; ?>" ><?php echo $row->tico_nombre; ?></option>
                                                 <?php } else { ?>
                                                 <option value="<?php echo $row->tico_id; ?>"><?php echo $row->tico_nombre; ?></option>
                                                 <?php } ?>

                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('tipocontratoid','<span class="text-danger">','</span>'); ?>
                                          </div>
                                         

                                     </div>

                                     <div class="col-md-6 column">
                                       <div class="form-group">
                                         <label for="fecha">Fecha de la firma del contrato</label>
                                         <div class='input-group date' id='datetimepicker5' data-date-format="YYYY-MM-DD">
                                             <input type='text' class="form-control" name="fecha" value="<?php echo $result->cntr_fecha_firma; ?>" required="required"/>
                                             <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                             </span>
                                          </div>
                                          <?php echo form_error('fecha','<span class="text-danger">','</span>'); ?>
                                       </div>
                                     </div>
                                     <div class="col-md-6 column">

                                          <div class="form-group">
                                            <label for="numero">Número de contrato</label>
                                            <input class="form-control" id="numero" type="number" name="numero" value="<?php echo $result->cntr_numero; ?>" required="required" min="0" />
                                            <?php echo form_error('numero','<span class="text-danger">','</span>'); ?>
                                          </div>

                                     </div>

                                      <div class="col-md-6 column">

                                          <div class="form-group">
                                            <label for="valor">Valor</label>
                                             <div class="input-group">
                                              <div class="input-group-addon">$</div>
                                                <input class="form-control" id="valor" type="text" name="valor" value="<?php echo $result->cntr_valor; ?>" required="required" maxlength="100" />
                                              </div>
                                            <?php echo form_error('valor','<span class="text-danger">','</span>'); ?>
                                          </div>

                                     </div>

                                     <div class="col-md-12 column">

                                          <div class="form-group">
                                           <label for="objeto">Objeto</label>
                                           <textarea class="form-control" id="objeto" name="objeto" maxlength="1000" required="required"><?php echo $result->cntr_objeto; ?></textarea>
                                           <?php echo form_error('objeto','<span class="text-danger">','</span>'); ?>
                                          </div>
                                     </div>  
   
                

                                    <div class="pull-right">
                                     <?php  echo anchor('contratos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                     
                                     <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/delete')) { ?>
                                      <a class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash-o"></i> Eliminar</a>
                                     <?php } ?>

                                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                                    </div>
                                <?php echo form_close();?>
                              
                              </div>
                             </div>
       
                        </div>
                        <div class="col-md-2 column">
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
         <input class="form-control" id="id" type="hidden" name="id" value="<?php echo $result->cntr_id; ?>"/>
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
      $(function () {
          $('#datetimepicker5').datetimepicker({
              pickTime: false
          });
      });
  </script>
  <script type="text/javascript">
    //style selects
    var config = {
      '#contratistaid'  : {disable_search_threshold: 10},
      '#tipocontratoid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>