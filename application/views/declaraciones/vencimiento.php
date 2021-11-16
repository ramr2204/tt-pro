<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="row"> 
    <div class="col-sm-8 col-sm-offset-2">
        <h1>Vencimientos de Declaraciones</h1>
        <br><br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                        <th>Último dígito</th>
                        <th>Día del mes</th>
                        <th>Última modificación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                        foreach($vencimientos AS $vencimiento)
                        {
                            ?>
                            <tr>
                                <td><?= $vencimiento->ultimo_digito ?></td>
                                <td>
                                    <input type="text"
                                        value="<?= $vencimiento->dia ?>"
                                        id="dia-<?= $vencimiento->id ?>"
                                        required
                                        class="form-control"
                                    >
                                </td>
                                <td id="modificado-<?= $vencimiento->id ?>"><?= $vencimiento->modificado ?></td>
                                <td>
                                    <button class="btn btn-primary btn-xs editar-dia"
                                        title="Editar día"
                                        id="<?= $vencimiento->id ?>"
                                    >
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
 </div>


<script type="text/javascript" language="javascript" charset="utf-8">

    $(function () {
        $('.editar-dia').click(editarDia)
    });

    function editarDia() {
        var id = $(this).attr('id');

        var parametros = {
            id: id,
            dia: $('#dia-'+id).val()
        };

        $.ajax({
            url: base_url + 'index.php/vencimientoDeclaraciones/editar',
            type: 'POST',
            data: parametros,
            success: function (respuesta) {
                if(respuesta.exito) {
                    swal('Atenci\u00F3n', 'Se edit\u00F3 correctamente', 'success');
                    $('#modificado-'+id).text(respuesta.modificado);
                } else {
                    // swal('Atenci\u00F3n', respuesta.errores, 'error');

                    const contenedor = document.createElement('div');
                    contenedor.innerHTML = respuesta.errores;

                    swal({
                        icon: 'error',
                        title: 'Atenci\u00F3n',
                        content: contenedor
                    });
                }
            },
        });
    }
</script>