<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   @author           David Mahecha
*   @version          2021
*
*/

?>

<div class="row"> 
    <div class="col-sm-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= form_open_multipart('liquidaciones/registrarAdicion','role="form"') ?>
                    <input type="hidden" name="id_contrato" value="<?= $id_contrato ?>">

                    <h4 class="text-center"><b>Registrar Adición</b></h4>
                    <hr>

                    <div class="form-group col-md-12">
                        <label>Valor</label>
                        <input type="text" name="valor" class="form-control numerico_ret" id="valor_cont">
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="observaciones_cont">Observaciones</label>
                        <textarea class="form-control" id="observaciones_cont" name="observaciones"></textarea>
                    </div>

                    <div class="col-xs-12 text-center">
                        <button type="submit" class="btn btn-success">Agregar</button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading text-center">
                <h4><b>Historico de Adiciones</b></h4>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha de creación</th>
                        <th>Valor</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                        if(count($adiciones) > 0)
                        {
                            foreach($adiciones AS $adicion)
                            {
                                ?>
                                <tr>
                                    <td><?= $adicion->fecha_creacion ?></td>
                                    <td>$<?= number_format($adicion->valor, 2, ',', '.') ?></td>
                                    <td><?= $adicion->observaciones ?></td>
                                </tr>
                                <?
                            }
                        }else{
                            ?>
                            <tr>
                                <td colspan="3" class="text-center">Sin datos</td>
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
        $('.numerico_ret').autoNumeric('init',{aSep: '.' , aDec: ',' });
    });
</script>