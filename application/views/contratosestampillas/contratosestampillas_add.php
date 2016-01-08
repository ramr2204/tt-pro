<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratosestampillas/contratosestampillas_add.php
*   Descripcion:       permite crear un nuevo contrato
*   Fecha Creacion:    05/Ene/2016
*   @author            Mike Ortiz <engineermikeortiz@gmail.com>
*   @version           2016-01-05
*
*/
?>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix">                
            <div class="col-md-4 col-md-offset-4 column">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Agregar Contrato Estampillas</h1></div>
                        <div class="panel-body">
                            <?php echo form_open_multipart('contratoEstampillas/save'); ?>
                                <div class="form-group">
                                    <label for="conpap_fecha">Fecha Contrato</label>
                                    <div class='input-group date' id='datetimepicker_fechaContratoE' data-date-format="YYYY-MM-DD">
                                        <input type='text' class="form-control" name="conpap_fecha" required="required"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>                      
                                </div>                                
                                <div class="form-group">
                                    <label for="conpap_numero">Número de Contrato</label>
                                    <input class="form-control" id="conpap_numero" type="number" name="conpap_numero" required="required" maxlength="100" />
                                    <?php echo form_error('conpap_numero','<span class="text-danger">','</span>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="conpap_cantidad">Cantidad</label>
                                    <input class="form-control" id="conpap_cantidad" type="number" name="conpap_cantidad" required="required" maxlength="100" />
                                    <?php echo form_error('conpap_cantidad','<span class="text-danger">','</span>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="conpap_observaciones">Observaciones</label>
                                    <textarea class="form-control" id="conpap_observaciones" name="conpap_observaciones" required="required" maxlength="450" /></textarea>
                                    <?php echo form_error('conpap_observaciones','<span class="text-danger">','</span>'); ?>
                                </div>
                                
                                <div class="pull-right">
                                    <?php  echo anchor('contratoEstampillas', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" class="btn btn-success" id="btn-conpapAdd"><i class="fa fa-floppy-o"></i> Guardar</button>
                                </div>
                                <?php echo form_close();?>                              
                        </div>
                </div>       
            </div>                        
        </div> 
    </div>
</div>
