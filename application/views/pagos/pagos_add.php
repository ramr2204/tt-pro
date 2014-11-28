<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/bancos/bancos_add.php
*   Descripcion:       permite crear una nueva aplicación
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
                            <div class="panel-heading"><h1>Cargar un Nuevo Archivo de Pagos</h1></div>
                             <div class="panel-body">
                              <?php echo form_open_multipart('pagos/doadd', array('id' => 'cargarTxtPagos')); ?>

                              
                                    <div class="form-group">
                                        <label for="archivo">Formato (txt)</label>
                                        <input id="file" type="file" class="file" name="archivo" multiple=false>
                                    </div>

                                    <div class="pull-right">
                                     <?php  echo anchor('bancos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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
    $("#file").fileinput({

        initialCaption: "",
        showCaption: false,
        browseClass: "btn btn-default",
        browseLabel: "Cargar Archivo",
        showUpload: false,
        showRemove: false,

    });

</script>
