<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratistas/contratistas_add.php
*   Descripcion:       permite crear un nuevo contratista
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-12
*
*/
?>
<br>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-xs-12 column" id="notificacion">
                        </div>
                        <div class="col-md-2 column">
                        </div>
                        <div class="col-md-8 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1>Ingreso manual de contratos</h1></div>
                             <div class="panel-body">
                                

                              <?php echo form_open(current_url()); ?>
                                  
                                    <div class="col-md-12 column">
                                        <div class="form-group">
                                           <label for="contratistaid">Contratista</label>
                                           <select class="form-control chosen" id="contratistaid" name="contratistaid" required="required" >
                                           <option value="0">Seleccione...</option>
                                             <?php  foreach($contratistas as $row) { ?>
                                             <option value="<?php echo $row->cont_id; ?>"><?php echo $row->cont_nit.' - '.$row->cont_nombre; ?></option>
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
                                              <option value="<?php echo $row->tico_id; ?>"><?php echo $row->tico_nombre; ?></option>
                                              <?php   } ?>
                                            </select>
                                            <?php echo form_error('estadoid','<span class="text-danger">','</span>'); ?>
                                        </div>
                                    </div>

                                     <div class="col-md-6 column">
                                       <div class="form-group">
                                         <label for="fecha">Fecha de la firma del contrato</label>
                                         <div class='input-group date' id='datetimepicker5' data-date-format="YYYY-MM-DD">
                                             <input type='text' class="form-control" name="fecha" value="<?php echo set_value('fecha'); ?>" required="required"/>
                                             <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                             </span>
                                          </div>
                                          <?php echo form_error('fecha','<span class="text-danger">','</span>'); ?>
                                       </div>
                                     </div>                                    

                                     <div class="col-md-6 column">

                                          <div class="form-group">
                                            <label for="numero">Número de contrato</label>
                                            <input class="form-control" id="numero" type="number" name="numero" value="<?php echo set_value('numero'); ?>" required="required" min="0" />
                                            <?php echo form_error('numero','<span class="text-danger">','</span>'); ?>
                                          </div>

                                     </div>

                                    <div class="col-md-6 column">
                                          <div class="form-group">
                                            <label for="valor">Valor</label>
                                             <div class="input-group">
                                              <div class="input-group-addon">$</div>
                                                <input class="form-control" id="valor" type="text" name="valor" value="<?php echo set_value('valor'); ?>" required="required" maxlength="100" />
                                              </div>
                                            <?php echo form_error('valor','<span class="text-danger">','</span>'); ?>
                                          </div>
                                    </div>

                                    <div class="col-xs-12 column" id="cont_iva_otros" style="display:none;">
                                        <div class="form-group">
                                            <label for="valor_iva_otros">Valor IVA</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">$</div>
                                                    <input class="form-control" id="valor_iva_otros" type="text" name="valor_iva_otros" value="<?php echo set_value('valor_iva_otros'); ?>" maxlength="100" />
                                                </div>
                                            <?php echo form_error('valor_iva_otros','<span class="text-danger">','</span>'); ?>
                                        </div>
                                    </div>

                                      <div class="col-md-12 column">
                                      <div class="form-group">
                                           <label for="cntr_municipio_origen">Municipio Origen del Contrato</label>
                                           <select class="form-control" id="cntr_municipio_origen" name="cntr_municipio_origen" required="required" >
                                           <option value="0">Seleccione...</option>
                                             <?php  foreach($municipios as $row) { ?>
                                             <option value="<?php echo $row->muni_id; ?>"><?php echo $row->muni_nombre; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('cntr_municipio_origen','<span class="text-danger">','</span>'); ?>
                                    </div>

                                     <div class="col-md-12 column">

                                          <div class="form-group">
                                           <label for="objeto">Objeto</label>
                                           <textarea class="form-control" id="objeto" name="objeto" maxlength="1000" required="required"><?php echo set_value('objeto'); ?></textarea>
                                           <?php echo form_error('objeto','<span class="text-danger">','</span>'); ?>
                                          </div>
                                     </div>



                                    <div class="pull-right">
                                     <?php  echo anchor('contratistas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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
      '#municipioid'  : {disable_search_threshold: 10},
      '#tipocontratoid'  : {disable_search_threshold: 10},      
      '#cntr_municipio_origen'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    /*
    * Evento para establecer validacion de regimen de contratista para
    * permitir ingresar el valor del IVA en contratos con AUI
    */
    $('#contratistaid').chosen({disable_search_threshold: 10}).change(validarRegimenContratista);

  </script>
  <script type="text/javascript">
      $(function () {
            $('#valor').autoNumeric('init',{aSep: '.' , aDec: ',' }); 
            $('#valor_iva_otros').autoNumeric('init',{aSep: '.' , aDec: ',' }); 
      });
  </script>