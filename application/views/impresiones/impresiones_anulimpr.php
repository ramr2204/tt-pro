<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/impresiones/impresiones_anulimpr.php
*   Descripcion:       permite crear una nueva aplicación
*   Fecha Creacion:    09/mar/2016
*   @author            Mike Ortiz <engineermikeortiz@gmail.com>
*   @version           2016-03-09
*
*/
?>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix">                        
            <div class="col-md-4 column col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                                <?php 
                                    if($objContin->para_contingencia == 1)
                                    {
                                        echo '<h1>Anular Impresión<br>(CONTINGENCIA)</h1>';
                                    }else
                                        {
                                            echo '<h1>Anular Impresión<br>(NUMERADAS)</h1>';
                                        }
                                ?>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open(current_url()); ?>
                            <div class="form-group">
                                   <label for="codigopapel">Consecutivo del papel</label>
                                   <input class="form-control" id="codigopapel" type="number" name="codigopapel" value="<?php echo set_value('codigopapel'); ?>" step="" min="1" />
                                   <?php echo form_error('codigopapel','<span class="text-danger">','</span>'); ?>
                            </div>
                            <div class="form-group">
                                   <label for="observaciones">Observaciones</label>
                                   <textarea class="form-control" id="observaciones" name="observaciones" maxlength="1000"><?php echo set_value('observaciones'); ?></textarea>
                                   <?php echo form_error('observaciones','<span class="text-danger">','</span>'); ?>
                            </div>
                            <div class="pull-right">
                                <?php  echo anchor('impresiones', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                            </div>
                        <?php echo form_close();?>                              
                    </div>
                </div>       
            </div>                        
        </div> 
    </div>
</div>