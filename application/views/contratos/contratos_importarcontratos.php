<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratos/contratos_importarcontratos.php
*   Descripcion:       permite crear un nuevo contrato
*   Fecha Creacion:    18/jul/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-07-18
*
*/
?>
<br>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1>Importar contratos</h1></div>
                             <div class="panel-body">
                                

                              <?php echo form_open(current_url()); ?>
                          
                                      
                                      <div class="form-group">
                                           <label for="vigencia">Vigencia</label>
                                           <select class="form-control" id="vigencia" name="vigencia" required="required" >
                                             <?php foreach($vigencias as $row) { ?>
                                             <option value="<?php echo $row; ?>"><?php echo $row; ?></option>
                                             <?php   } ?>
                                           </select>
                                           <?php echo form_error('vigencia','<span class="text-danger">','</span>'); ?>


                                      <br><br>
                                    <div class="pull-right">
                                     <?php  echo anchor('contratos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" id="cargaImportacion" class="btn btn-success ladda-button" data-style="expand-right"><i class="fa fa-level-down"></i> <span class="ladda-label">Importar</span></button>
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
      '#vigencia'  : {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

  </script>
