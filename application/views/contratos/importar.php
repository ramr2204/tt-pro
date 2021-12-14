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
                        <?php echo form_open_multipart('contratos/cargarImportarContratos'); ?>
                            <div class="form-group">
                                <label for="vigencia">Archivo</label>
                                <input type="file" name="archivo">
                                <?php echo form_error('vigencia','<span class="text-danger">','</span>'); ?>
                                <br><br>
                                <div class="pull-right btn-group">
                                    <a href="<?= base_url() ?>contratos/plantillaExcel" target="_blank" class="btn btn-default">
                                        <i class="fa fa-file-excel-o"></i> 
                                        <span>Plantilla de Ejemplo</span>
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-level-down"></i> 
                                        <span>Importar</span>
                                    </button>
                                </div>
                            </div>
                        <?php echo form_close();?>
                    </div>
                </div>
                <div class="col-md-4 column">
                </div>
            </div> 
        </div>
    </div>
</div>
