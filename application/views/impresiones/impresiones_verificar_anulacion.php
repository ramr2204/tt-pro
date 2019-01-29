<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
 *   Nombre:            impresiones_verificar_anulacion
 *   Ruta:              /application/views/impresiones/impresiones_verificar_anulacion.php
 *   Descripcion:       permite establecer informacion de verificacion de una anulacion de impresion
 *   Fecha Creacion:    29/Ene/2019
 *   @author            Mike Ortiz <engineermikeortiz@gmail.com>
 *   @version           2019-01-29
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
                            echo '<h1>Verificar Anulaci&oacute;n Rotulo<br>('. $objAnulacion->impr_codigopapel .')</h1>';
                        ?>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open('impresiones/post_verificar_anulacion/'. $objAnulacion->impr_id); ?>
                            <div class="form-group">
                                   <label for="observaciones">Observaciones Verificaci&oacute;n</label>
                                   <textarea class="form-control" id="observaciones" name="observaciones" maxlength="250"></textarea>
                                   <?php echo form_error('observaciones','<span class="text-danger">','</span>'); ?>
                            </div>
                            <div class="pull-right">
                                <?php  echo anchor('impresiones/anulaciones', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                            </div>
                        <?php echo form_close();?>                              
                    </div>
                </div>       
            </div>                        
        </div> 
    </div>
</div>