<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/papeles/papeles_add.php
*   Descripcion:       permite crear una nueva estampilla
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-12
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
                            <div class="panel-heading"><h1>Ingreso de papelería para estampillas</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url()); ?>
                                    <div class="form-group">
                                           <label for="responsablePapel">Nombre Responsable</label>
                                           <input class="form-control responsable" id="responsable" type="text" name="responsablePapel" placeholder="Nombre" required autocomplete="off"/>
                                           <?php echo form_error('responsablePapel','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="documentoRespPapel">Documento</label>
                                           <input class="form-control" id="docuResponsable" type="text" name="documentoRespPapel" placeholder="Documento" readonly />
                                    </div>

                                    <div class="form-group">
                                           <label for="codigoinicial">Código inicial</label>
                                           <input class="form-control" id="codigoinicial" type="number" name="codigoinicial" value="<?php echo $maxcodigofinal['pape_codigofinal']+1; ?>" maxlength="3" min="0" step="0" />
                                           <?php echo form_error('codigoinicial','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="form-group">
                                           <label for="codigofinal">Código final</label>
                                           <input class="form-control" id="codigofinal" type="number" name="codigofinal" value="<?php echo $maxcodigofinal['pape_codigofinal']+2; ?>" maxlength="3" min="0" step="0" />
                                           <?php echo form_error('codigofinal','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="form-group">
                                           <label for="cantidad">Cantidad</label>
                                           <input class="form-control" id="cantidad" type="number" name="cantidad" value="<?php echo set_value('cantidad'); ?>" maxlength="3" min="0" step="0" readonly />
                                           <?php echo form_error('cantidad','<span class="text-danger">','</span>'); ?>
                                    </div>


                                    <div class="form-group">
                                           <label for="observaciones">Observaciones</label>
                                           <textarea class="form-control" id="observaciones" type="observaciones" name="observaciones" maxlength="500"><?php echo set_value('observaciones'); ?></textarea>
                                           <?php echo form_error('observaciones','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="pull-right">
                                     <?php  echo anchor('papeles', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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

  <script type="text/javascript">
    $("#codigofinal").change(function(){
        var cantidad= $("#codigofinal").val() - $("#codigoinicial").val();
        $("#cantidad").val(cantidad+1);
    });
  </script>
