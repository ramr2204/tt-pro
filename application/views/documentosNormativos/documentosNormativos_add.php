<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/documentosNormativos/documentosNormativos_add.php
*   Descripcion:       permite crear un nuevo documento normativo
*   Fecha Creacion:    06/Ene/2016
*   @author           Mike Ortiz <engineermikeortiz@gmail.com>
*   @version          2016-01-06
*
*/
?>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix">                
            <div class="col-md-4 col-md-offset-4 column">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Agregar Documento Normativo</h1></div>
                        <div class="panel-body">
                            <?php echo form_open_multipart('documentosNormativos/save'); ?>
                                <div class="form-group">
                                    <label for="docnor_tipo">Tipo de Documento</label>
                                    <select class="form-control chosen" id="docnor_tipo" name="docnor_tipo" required="required" >
                                        <option value="0">Seleccione...</option>
                                            <?php  foreach($tiposDocumentoN as $row) { ?>
                                                <option value="<?php echo $row->tidocn_id; ?>"><?php echo $row->tidocn_nombre; ?></option>
                                            <?php   } ?>
                                    </select>
                                    <?php echo form_error('docnor_tipo','<span class="text-danger">','</span>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="docnor_fecha">Fecha Expedición</label>
                                    <div class='input-group date' id='datetimepicker_fechadocnor' data-date-format="YYYY-MM-DD">
                                        <input type='text' class="form-control" name="docnor_fecha" required="required"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>                      
                                </div>
                                <div class="form-group">
                                    <label for="docnor_iniciovigencia">Fecha Inicio Vigencia</label>
                                    <div class='input-group date' id='datetimepicker_iniciodocnor' data-date-format="YYYY-MM-DD">
                                        <input type='text' class="form-control" name="docnor_iniciovigencia" required="required"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>                      
                                </div>
                                <div class="form-group">
                                    <label for="docnor_numero">Número de Documento</label>
                                    <input class="form-control" id="docnor_numero" type="text" name="docnor_numero" required="required" maxlength="100" />
                                    <?php echo form_error('docnor_numero','<span class="text-danger">','</span>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="archivo">Soporte</label>
                                    <input id="file" type="file" class="file" name="archivo" multiple=false>
                                </div>
                                <div class="pull-right">
                                    <?php  echo anchor('documentosNormativos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" class="btn btn-success" id="btn-documentosNormativosAdd"><i class="fa fa-floppy-o"></i> Guardar</button>
                                </div>
                                <?php echo form_close();?>                              
                        </div>
                </div>       
            </div>                        
        </div> 
    </div>
</div>

<script type="text/javascript">
    $("#file").fileinput({
        initialCaption: "",
        showCaption: false,
        browseClass: "btn btn-default btn-sm",
        browseLabel: "Cargar Archivo",
        showUpload: false,
        showRemove: false,

    });
</script> 
