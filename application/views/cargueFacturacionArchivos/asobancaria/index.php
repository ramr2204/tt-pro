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
                    <div class="panel-heading"><h1>Cargue Facturas Asobancaria</h1></div>
                    <div class="panel-body">
                        <?php echo form_open_multipart('cargueArchivosAsobancaria/save');?>

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
                                <label for="archivo_asobancaria">Adjuntar Archivo</label>
                                <div>
                                    <label class="btn btn-dark btn-block" style="background-color: #f5f5f5;border-color: #dedede;overflow: hidden;white-space: nowrap;">
                                        <span id="label_tramite">Archivo</span> <input type="file" name="archivo_asobancaria"  id="archivo_asobancaria" style="display: none">
                                    </label>
                                </div>
                                <?php echo form_error('archivo_asobancaria','<span class="text-danger">','</span>'); ?>
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