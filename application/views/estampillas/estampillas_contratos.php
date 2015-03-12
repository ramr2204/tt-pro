<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/estampillas/estampillas_list.php
*   Descripcion:       permite editar una estampilla
*   Fecha Creacion:    12/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-12
*
*/
 ?>
<div class="row clearfix">
<h1>Editar cobros de la estampilla <?php echo $result->estm_nombre; ?></h1>
            <div class="col-md-12 column">
                  <div class="row clearfix">

                        <div class="col-md-5 column">
                           <div class="panel panel-default">
                           <div class="panel-heading"><h1>Tipos de contrato</h1></div>
                             <div class="panel-body">
                              <table class="table table-bordered">
                                      <tr>
                                         <td class="text-center" width="75%">Tipo de contrato</td>
                                         <td class="text-center" width="15%">Porcentaje</td>
                                         <td class="text-center" width="10%">Acción</td>
                                      </tr>   
                              <?php foreach($tiposcontratos as $row => $value) { ?>
                                      <tr>
                                         <td><?php echo $value; ?></td>
                                         <td class="text-center"><?php echo $porcentajes[$row]; ?>%</td>
                                         <td class="text-center"><a class="btn btn-danger btn-sm confirm" data-toggle="modal" data-target="#myModal" id="<?php echo $row; ?>" data="contrato" ><i class="fa fa-trash-o"></i> </a></td>
                                      </tr>  
                              <?php  } ?>
                                        <tr>
                                         <td class="text-center" colspan="3">Agregar</td>
                                        </tr> 
                                        <tr>
                                         <?php echo form_open('estampillas/agregarcobro','name="form1"');?>
                                         <td class="">
                                         <input class="form-control" id="estampillaid" type="hidden" name="estampillaid" value="<?php echo $result->estm_id; ?>"/>  
                                         <input class="form-control" id="tipocobro" type="hidden" name="tipocobro" value="contrato"/> 

                                           <div class="form-group">
                                               <label for="tipocontratoid">Tipo de contrato</label>
                                               <select class="form-control" id="tipocontratoid" name="tipocontratoid" required="required" >
                                                 <option value="0">Seleccione...</option>
                                                 <?php  foreach($selecttiposcontratos as $key => $value) { ?>
                                                 
                                                     <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            
                                                <?php   } ?>
                                               </select>
                                               <?php echo form_error('tipocontratoid','<span class="text-danger">','</span>'); ?>
                                           </div>


                                         </td>
                                         <td class="text-center">
                                            <div class="form-group">
                                               <label for="porcentaje">Porcentaje</label>
                                               <input class="form-control" id="porcentaje" type="number" name="porcentaje" value="0" required="required" step="0.1" min="0" />
                                               <?php echo form_error('porcentaje','<span class="text-danger">','</span>'); ?>
                                           </div>
                                         </td>
                                         <td class="text-center"><button type="submit" class="btn btn-success btn-sm"><i class="fa fa-floppy-o"></i></button></td>
                                      </tr> 
                                      <?php echo form_close();?>
                              </table>
                                      
                                    <div class="pull-right">
                                     <?php  echo anchor('estampillas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    
                                    </div>
                              
                              </div>
                             </div>
                          
                        </div>








                        <div class="col-md-7 column">
                          <div class="panel panel-default">
                           <div class="panel-heading"><h1>Trámites</h1></div>
                             <div class="panel-body">


                               <table class="table table-bordered">
                                      <tr>
                                         <td class="text-center" width="75%">Trámite</td>
                                         <td class="text-center" width="15%">Porcentaje</td>
                                         <td class="text-center" width="10%">Acción</td>
                                      </tr>   
                              <?php foreach($tramites as $row => $value) { ?>
                                      <tr>
                                         <td><?php echo $value; ?></td>
                                         <td class="text-center"><?php echo $porcentajest[$row]; ?>%</td>
                                         <td class="text-center"><a class="btn btn-danger btn-sm confirm" data-toggle="modal" data-target="#myModal" id="<?php echo $row; ?>" data="tramite" ><i class="fa fa-trash-o"></i> </a></td>
                                      </tr>  
                              <?php  } ?>
                                        <tr>
                                         <td class="text-center" colspan="3">Agregar</td>
                                        </tr> 

                                        <tr>
                                        <?php echo form_open('estampillas/agregarcobro','name="form2"');?>
                                         <td class="">
                                           
                                          <input class="form-control" id="estampillaid" type="hidden" name="estampillaid" value="<?php echo $result->estm_id; ?>"/>  
                                          <input class="form-control" id="tipocobro" type="hidden" name="tipocobro" value="tramite"/> 

                                           <div class="form-group">
                                               <label for="tramiteid">Tipo de trámite</label>
                                               <select class="form-control" id="tramiteid" name="tramiteid" required="required" >
                                                 <option value="0">Seleccione...</option>
                                                 <?php  foreach($selecttramites as $key => $value) { ?>
                                                 
                                                     <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            
                                                <?php   } ?>
                                               </select>
                                               <?php echo form_error('tramiteid','<span class="text-danger">','</span>'); ?>
                                           </div>


                                         </td>
                                         <td class="text-center">
                                            <div class="form-group">
                                               <label for="porcentaje">Porcentaje</label>
                                               <input class="form-control" id="porcentaje" type="number" name="porcentaje" value="0" required="required" step="0.1" min="0" />
                                               <?php echo form_error('porcentaje','<span class="text-danger">','</span>'); ?>
                                           </div>
                                         </td>
                                          <td class="text-center"><button type="submit" class="btn btn-success btn-sm"><i class="fa fa-floppy-o"></i></button></td>
                                         
                                      </tr>
                                     <?php echo form_close();?>
                              </table>
                              




                              
                                   

                                    
                                      
                                    <div class="pull-right">
                                     <?php  echo anchor('estampillas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    
                                    </div>
                                
                              
                              </div>
                             </div>
                        </div>
                  </div> 
            </div>
      </div>

<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/delete')) { ?>
<!-- Modal -->
<?php echo form_open("estampillas/eliminarcobro",'role="form"');?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">¿Confirma que quiere eliminar EL estampilla?</h4>
      </div>
      <div class="modal-body">
         <input class="form-control" id="id" type="hidden" name="id" value=""/>
         <input class="form-control" id="tipo" type="hidden" name="tipo" value=""/>
         <input class="form-control" id="estampillaid" type="hidden" name="estampillaid" value="<?php echo $result->estm_id; ?>"/>
        Si oprime confirmar no podrá recuperar la información de este cobro  <br>
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
      '#tipocontratoid'  : {disable_search_threshold: 10},
      '#tramiteid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>
  <script type="text/javascript">
    $('.confirm').click(function() {
      var id = $(this).attr('id');
      var tipo = $(this).attr('data');
      $('#id').val(id);
      $('#tipo').val(tipo);
});

</script>