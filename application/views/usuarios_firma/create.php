<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratistas/contratistas_add.php
*   Descripcion:       permite crear un nuevo contratista
*   Fecha Creacion:    12/may/2014
*   @author            David Mahecha <david.mahecha@turrisystem.com>
*   @version           2014-05-12
*
*/
?>
<br>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix">
            <div class="col-xs-12 column" id="notificacion">
            </div>
            <div class="col-md-2 column">
            </div>
            <div class="col-md-8 column">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Asignar Firma Electrónica</h1></div>
                    <div class="panel-body">
                        <?= form_open(current_url()); ?>

                            <div class="col-md-6 column form-group">
                                <label for="empresa">Empresa</label>
                                <select class="form-control chosen-select" id="empresa" name="empresa" required="required" >
                                    <option value="0">Seleccione...</option>
                                    <?php
                                        foreach($empresas AS $row)
                                        {
                                            ?>
                                            <option
                                                value="<?= $row->id; ?>"
                                                <?= set_select('empresa', $row->id) ?>
                                            >
                                                <?= $row->nombre; ?>
                                            </option>
                                            <?php
                                        }
                                    ?>
                                </select>
                                
                                <?= form_error('empresa','<span class="text-danger">','</span>'); ?>
                            </div>

                            <div class="col-md-6 column form-group">
                                <label for="usuario">Usuario</label>
                                <select class="form-control chosen-select" id="usuario" name="usuario" valor-anterior="<?= set_value('usuario'); ?>">
                                    <option value="0">Seleccione...</option>
                                </select>
                                <?= form_error('usuario','<span class="text-danger">','</span>'); ?>
                            </div>

                            <div class="col-md-6 column form-group">
                                <label for="tipo_usuario">Tipo de usuario</label>
                                <select class="form-control chosen-select" id="tipo_usuario" name="tipo_usuario" required="required" >
                                    <option value="0">Seleccione...</option>
                                    <?php
                                        foreach($tipos_usuarios AS $id => $nombre)
                                        {
                                            ?>
                                            <option
                                                value="<?= $id; ?>"
                                                <?= set_select('tipo_usuario', $id) ?>
                                            >
                                                <?= $nombre ?>
                                            </option>
                                            <?php
                                        }
                                    ?>
                                </select>
                                <?= form_error('tipo_usuario','<span class="text-danger">','</span>'); ?>
                            </div>

                            <div class="col-md-12 column form-group text-center">
                                <?= anchor('declaraciones', '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-default"'); ?>
                                <button
                                    class="btn btn-primary" type="submit"
                                >Guardar</button>
                            </div>
                        <?= form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('.chosen-select').chosen({disable_search_threshold: 10});

        $('#empresa').change(cambioEmpresa);
        $('#empresa').change();
    });

    function cambioEmpresa() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: base_url + 'index.php/usuariosFirma/buscarUsuariosEmpresa/'+$(this).val(),
            success: function (data) {
                var options = '<option value="0">Seleccione...</option>';
                var valor_anterior = $('#usuario').attr('valor-anterior');

                for(var usuario of data) {
                    options += '<option value="'+ usuario.id +'" '+(valor_anterior == usuario.id ? 'selected' : '')+'>'+ usuario.nombre +'</option>';
                }

                $('#usuario').html(options).trigger('chosen:updated');
            }
        });
    }
</script>