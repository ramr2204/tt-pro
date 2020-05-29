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
                    <div class="panel-heading"><h1>Liquidar persona trámite</h1></div>
                    <div class="panel-body">
                        <?php echo form_open(current_url(),'role="form"');?>

                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_documento">Tipo Documento</label>
                                <select class="form-control" id="tipo_documento" name="tipo_documento" required="required" >
                                    <?php  foreach($result['tipo_documento'] as $row) { ?>
                                        <option value="<?php echo $row->id; ?>"><?php echo $row->sigla . ' - ' . $row->nombre; ?></option>
                                    <?php   } ?>
                                </select>
                                <?php echo form_error('tipo_documento','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ndocumento">Número Cédula</label>
                                <input class="form-control" id="ndocumento" type="text" name="ndocumento" required="required" />
                                <?php echo form_error('ndocumento','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="primer_nombre">Primer Nombre</label>
                                <input class="form-control" id="primer_nombre" type="text" name="primer_nombre" required="required" />
                                <?php echo form_error('primer_nombre','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="segundo_nombre">Segundo Nombre</label>
                                <input class="form-control" id="segundo_nombre" type="text" name="segundo_nombre" required="required" />
                                <?php echo form_error('segundo_nombre','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="primer_apellido">Primer Apellido</label>
                                <input class="form-control" id="primer_apellido" type="text" name="primer_apellido" required="required" />
                                <?php echo form_error('primer_apellido','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="segundo_apellido">Segundo Apellido</label>
                                <input class="form-control" id="segundo_apellido" type="text" name="segundo_apellido" required="required" />
                                <?php echo form_error('segundo_apellido','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono1">Teléfono 1</label>
                                <input class="form-control" id="telefono1" type="text" name="telefono1" required="required" />
                                <?php echo form_error('telefono1','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono2">Teléfono 2</label>
                                <input class="form-control" id="telefono2" type="text" name="telefono2" />
                                <?php echo form_error('telefono2','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input class="form-control" id="direccion" type="text" name="direccion" required="required" />
                                <?php echo form_error('direccion','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_tramite">Tipo Trámite</label>
                                <select class="form-control" id="tipo_tramite" name="tipo_tramite" required="required" >
                                    <?php  foreach($result['tipo_tramites'] as $row) { ?>
                                        <option value="<?php echo $row->id; ?>"><?php echo $row->nombre . ' - ' . $row->vigencia; ?></option>
                                    <?php   } ?>
                                </select>
                                <?php echo form_error('tipo_tramite','<span class="text-danger">','</span>'); ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-6" style="padding-left: 0px">
                                <div class="form-group">
                                    <label for="departamentoid_tramites">Departamento</label>
                                    <select class="form-control" id="departamentoid_tramites" name="departamento_residencia" required="required" >
                                        <option value="0">Seleccione...</option>
                                        <?php  foreach($result['departamentos'] as $row) { ?>
                                            <option value="<?php echo $row->depa_id; ?>"><?php echo $row->depa_nombre; ?></option>
                                        <?php   } ?>
                                    </select>
                                    <?php echo form_error('departamento_residencia','<span class="text-danger">','</span>'); ?>
                                </div>
                            </div>

                            <div class="col-md-6" style="padding-right: 0px">
                                <div class="form-group">
                                    <label for="municipioid_tramites">Municipio</label>
                                    <select class="form-control" id="municipioid_tramites" name="municipio" required="required" >
                                        <option value="0">Seleccione un departamento</option>
                                    </select>
                                    <?php echo form_error('municipio','<span class="text-danger">','</span>'); ?>
                                </div>
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
        var base_tramite = $('#base_tramite').val();
        var id_persona_tramite = $('#id_persona_tramite').val();

        //style selects
        var config = {
            '#municipioid_tramites'  : {disable_search_threshold: 10},
            '#departamentoid_tramites'  : {disable_search_threshold: 10},
            '#tipo_tramite'  : {disable_search_threshold: 10},
            '#regimenid'  : {disable_search_threshold: 10},
            '#tipocontratistaid'  : {disable_search_threshold: 10},
            '#tipo_documento' : {disable_search_threshold: 10}
        }

        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

        if(id_persona_tramite != '')
        {
            window.open(base_tramite + 'liquidacionTramite/pdf/?id=' + id_persona_tramite, '_blank');
        }

    })

</script>