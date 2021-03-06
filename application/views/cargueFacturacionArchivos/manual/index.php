<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');

/**
*   Nombre:            admin template
*   Ruta:              /application/views/modulos/modulos_list.php
*   Descripcion:       permite editar un módulo
*   Fecha Creacion:    12/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-12
*
*/
?>
<input id="base_tramite" type="hidden" value="<?php echo base_url(); ?>">
<input id="id_persona_tramite" type="hidden" value="<?php echo $this->session->flashdata('id') ?>">
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix">
            <div class="col-md-3 column">
            </div>
            <div class="col-md-6 column">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Cargue Facturas Manual</h1></div>
                    <div class="panel-body">
                        <?php echo form_open_multipart('cargueArchivosManual/save');?>

                         <div class="col-md-6">
                            <div class="">
                                <label for="fecha_pago_tramite">Fecha Pago</label>
                                <div class="input-group">
                                    <input id="fecha_pago_tramite" type="text" name="fecha_pago_tramite" class="form-control date" required="required" value="<?php echo date('Y-m-d') ?>"/>                      
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="banco_tramite">Banco</label>
                                <select class="form-control" id="banco_tramite" name="banco_tramite" required="required" >
                                    <?php foreach ($result['bancos'] as $banco) { ?>
                                        <option value="<?php echo $banco->banc_id ?>"><?php echo $banco->banc_nombre ?></option>
                                    <?php } ?>
                                    <?php echo form_error('banco_tramite','<span class="text-danger">','</span>'); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero_factura_tramite">Número Factura</label>
                                <input class="form-control" id="numero_factura_tramite" type="text" name="numero_factura_tramite" required="required" />
                                <?php echo form_error('numero_factura_tramite','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="imagen_tramite">Adjuntar Imagen</label>
                                <div>
                                    <label class="btn btn-dark btn-block" style="background-color: #f5f5f5;border-color: #dedede;overflow: hidden;white-space: nowrap;">
                                        <span id="label_tramite">Archivo</span> <input type="file" name="imagen_tramite"  id="imagen_tramite" style="display: none">
                                    </label>
                                </div>
                                <?php echo form_error('imagen_tramite','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>


                        <div class="pull-right">
                            <?php  echo anchor('', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                        </div>
                       <?php echo form_close();?>

                    </div>
                </div>
            </div>
            <div class="col-md-3 column">
            </div>
        </div> 
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function()
    {

        $('.date').datepicker({format:'yyyy-mm-dd',type:'component'});
        var base_tramite = $('#base_tramite').val();
        var id_persona_tramite = $('#id_persona_tramite').val();

        //style selects
        var config = {
            '#banco_tramite'  : {disable_search_threshold: 10}
        }

        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

    })

</script>