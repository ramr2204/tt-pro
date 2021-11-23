<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   @author           David Mahecha
*   @version          2021
*
*/

?>

<div class="row"> 
    <div class="col-sm-12 col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center">
                    <i
                        class="glyphicon glyphicon-<?= $estilos[$notificacion->tipo]['icono'] ?> text-<?= $estilos[$notificacion->tipo]['color'] ?>"
                        style="font-size: 50px;"
                    ></i>
                    <h4><b><?= $descripciones[$notificacion->tipo] ?></b></h4>
                    <span class="text-muted"><?= $notificacion->fecha ?></span>
                </div>
                <hr>

                <p><?= $notificacion->texto ?></p>

                <?
                    if($notificacion->tipo == EquivalenciasNotificaciones::solicitudCorreccion())
                    {
                        ?>
                        <?= form_open_multipart('declaraciones/contestarCorreccion','role="form"') ?>

                            <input type="hidden" name="id_notificacion" value="<?= $notificacion->id ?>">
                            <input type="hidden" name="id_correccion" value="<?= $notificacion->adicional ?>">
                            
                            <div class="form-group">
                                <textarea name="observaciones" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="text-center">
                                <div class="btn-group">
                                    <button name="confirmar"
                                        value="1"
                                        class="btn btn-success"
                                    >Confirmar</button>
                                    <button name="rechazar"
                                        value="1"
                                        class="btn btn-danger"
                                    >Rechazar</button>
                                </div>
                            </div>
                        <?= form_close() ?>
                        <?
                    }
                ?>
            </div>
        </div>
    </div>
</div>