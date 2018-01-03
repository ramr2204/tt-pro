<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/documentosNormativos/documentosNormativos_edit.php
*   Descripcion:       permite editar un documento normativo
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
                    <div class="panel-heading"><h1>Editar Documento Normativo</h1></div>
                        <div class="panel-body">
                            <?php echo form_open_multipart('documentosNormativos/update'); ?>
                                <input type="hidden" name="docnor_id" value="<?php echo $documentoN->docnor_id;?>">
                                <div class="form-group">
                                    <label for="docnor_tipo">Tipo de Documento</label>
                                    <select class="form-control chosen" id="docnor_tipo" name="docnor_tipo" required="required" >
                                        <option value="0">Seleccione...</option>
                                            <?php  
                                                foreach($tiposDocumentoN as $row) 
                                                { 
                                                    if ($row->tidocn_id == $documentoN->docnor_tipo) 
                                                    { ?>
                                                        <option selected value="<?php echo $row->tidocn_id; ?>" ><?php echo $row->tidocn_nombre; ?></option>
                                            <?php   }else 
                                                        { ?>
                                                            <option value="<?php echo $row->tidocn_id; ?>"><?php echo $row->tidocn_nombre; ?></option>
                                            <?php       } 
                                                } ?>
                                    </select>
                                    <?php echo form_error('docnor_tipo','<span class="text-danger">','</span>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="docnor_fecha">Fecha Expedición</label>
                                    <div class='input-group date' id='datetimepicker_fechadocnor' data-date-format="YYYY-MM-DD">
                                        <input type='text' class="form-control" name="docnor_fecha" required="required" value="<?php echo $documentoN->docnor_fecha;?>" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>                      
                                </div>
                                <div class="form-group">
                                    <label for="docnor_iniciovigencia">Fecha Inicio Vigencia</label>
                                    <div class='input-group date' id='datetimepicker_iniciodocnor' data-date-format="YYYY-MM-DD">
                                        <input type='text' class="form-control" name="docnor_iniciovigencia" required="required" value="<?php echo $documentoN->docnor_iniciovigencia;?>"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>                      
                                </div>
                                <div class="form-group">
                                    <label for="docnor_numero">Número de documentoN</label>
                                    <input class="form-control" id="docnor_numero" type="text" name="docnor_numero" required="required" maxlength="100" value="<?php echo $documentoN->docnor_numero;?>" />
                                    <?php echo form_error('docnor_numero','<span class="text-danger">','</span>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="archivo">Soporte</label>
                                    <input id="file" type="file" class="file" name="archivo" multiple=false>
                                </div>
                                <div class="pull-right">
                                    <?php  echo anchor('documentosNormativos', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" class="btn btn-success" id="btn-documentosNormativosAdd"><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
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
        
      <?php   if ($documentoN->docnor_rutadocumento != '') { ?>
            
        initialPreview: ["<a href='<?php echo base_url().$documentoN->docnor_rutadocumento; ?>'class='btn btn-success' target='_blank'><img src='<?php echo base_url().$documentoN->docnor_rutadocumento; ?>' class='file-preview-image' alt='documentoN' title='documentoN'></a>"
],
        initialCaption: "",

        <?php
        }

        ?>
        showCaption: false,
        browseClass: "btn btn-default btn-sm",
        browseLabel: "Cargar Archivo",
        showUpload: false,
        showRemove: false,

    });
</script> 
