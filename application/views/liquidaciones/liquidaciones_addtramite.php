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
                        <div class="col-md-2 column">
                        </div>
                        <div class="col-md-8 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1>Ingreso de datos </h1></div>
                             <div class="panel-body">
                                

                              <?php echo form_open(current_url()); ?>                                                                       
                                     
                                       <input class="form-control" id="encontrado" type="hidden" name="encontrado" value="0"/>
                                       <div class="col-md-4 column">
                                          <div class="form-group">
                                            <label for="documento">Documento</label>
                                                <input class="form-control" id="documento" type="number" name="documento" value="<?php echo set_value('documento'); ?>" required="required" maxlength="200" />
                                              
                                            <?php echo form_error('documento','<span class="text-danger">','</span>'); ?>
                                          </div>
                                        </div>
                                         
                                         <div class="col-md-8 column">
                                          <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                                <input class="form-control" id="nombre" type="text" name="nombre" value="<?php echo set_value('nombre'); ?>" required="required" maxlength="200" />
                                              
                                            <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                                          </div>
                                        </div>

                                        <div class="col-md-4 column">
                                            <div class="form-group">
                                                <label for="telefono">Telefono</label>
                                                <input class="form-control" id="telefono" type="telefono" name="telefono" value="<?php echo set_value('telefono'); ?>" required="required" maxlength="15" />
                                                <?php echo form_error('telefono','<span class="text-danger">','</span>'); ?>
                                            </div>
                                        </div>

                                        <div class="col-md-8 column">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input class="form-control" id="email" type="email" name="email" value="<?php echo set_value('email'); ?>" required="required" />
                                                <?php echo form_error('email','<span class="text-danger">','</span>'); ?>
                                            </div>
                                        </div>

                                        <div class="col-md-12 column">
                                            <div class="form-group">
                                                <label for="direccion">Dirección</label>
                                                <input class="form-control" id="direccion" type="direccion" name="direccion" value="<?php echo set_value('direccion'); ?>" required="required" maxlength="256" />
                                                <?php echo form_error('direccion','<span class="text-danger">','</span>'); ?>
                                            </div>
                                        </div>

                                     <div class="col-md-12 column">
                                         <div class="form-group">
                                           <label for="tramiteid">Trámite</label>
                                           <select class="form-control" id="tramiteid" name="tramiteid" required="required" >
                                           <option value="0">Seleccione...</option>
                                             <?php  foreach($tramites as $row) { ?>
                                             <option value="<?php echo $row->tram_id; ?>"><?php echo $row->tram_nombre; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('tramiteid','<span class="text-danger">','</span>'); ?>
                                    </div>                                         
                                     </div>

                                    <div id="contene_placaVehiculo" style="display:none;">
                                        <div class="row panel-body">
                                            <div class="col-md-4"> <label for="placa">Placa del Vehiculo</label> </div>
                                            <div class="col-md-8"> <input class="form-control" id="placa" type="text" name="placa" value="<?php echo set_value('placa'); ?>" maxlength="8" /></div>
                                            <?php echo form_error('placa','<span class="text-danger">','</span>'); ?>
                                        </div>
                                    </div>

                                     <div class="col-md-12 column">

                                          <div class="form-group">
                                           <label for="observaciones">Observaciones</label>
                                           <textarea class="form-control" id="observaciones" name="observaciones" maxlength="500" ><?php echo set_value('observaciones'); ?></textarea>
                                           <?php echo form_error('observaciones','<span class="text-danger">','</span>'); ?>
                                          </div>
                                     </div>



                                    <div class="pull-right">
                                     <?php  echo anchor('liquidaciones/liquidartramites', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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
    //style selects
    var config = {
      '#tramiteid'  : {disable_search_threshold: 10,no_results_text: "No se encuentra"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]).change(switchPlacaVehiculo);
    }

    /*
    * Funcion de apoyo que muestra u oculta el input
    * de placa de vehiculo
    */
    function switchPlacaVehiculo(e)
    {
        var tipoTramite = $(this).val();
    
        if(tipoTramite == 16)
        {
            $('#contene_placaVehiculo').slideDown(300);                
        }else
            {
                $('#contene_placaVehiculo').slideUp(300);
            }
    }
  </script>
  <script type="text/javascript">
      $( "#documento" ).change(function() {
         var documento = $(this).val();
         var campos = ['nombre','telefono','email','direccion'];
         $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/liquidaciones/consultardocumento",
            data: { documento: documento }
          }).done(function( msg ) {
            if (msg==0) {
                campos.forEach(function(campo){
                    $('#'+campo).attr('readonly', false);
                    $('#'+campo).val('');
                });
                $('#encontrado').val(0);
            } else {
                var tramitador = JSON.parse(msg);

                campos.forEach(function(campo){
                    $('#'+campo).attr('readonly', 'readonly');
                    $('#'+campo).val(tramitador[campo]);
                });
                $('#encontrado').val(1);
            }
            
         });
      });
  </script>