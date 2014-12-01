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
                                                <input class="form-control" id="documento" type="text" name="documento" value="<?php echo set_value('documento'); ?>" required="required" maxlength="200" />
                                              
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
      '#tramiteid'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>
  <script type="text/javascript">
      $( "#documento" ).change(function() {
         var documento = $(this).val();
         var nombre = $('#nombre').val();
         $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/liquidaciones/consultardocumento",
            data: { documento: documento }
          }).done(function( msg ) {
            if (msg==0) {
                $('#encontrado').val(0);  
                $('#nombre').val(nombre);
            } else {
                $('#nombre').attr('readonly', 'readonly');
                $('#nombre').val(msg);
                $('#encontrado').val(1); 
            }
            
         });
      });
  </script>