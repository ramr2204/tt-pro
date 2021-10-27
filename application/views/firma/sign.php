<?
    $nombre_usuario = $info['usuario']['first_name'] . ' ' . $info['usuario']['last_name'];
?>

<div>
    <h3 class="text-center">Datos de la firma electrónica</h3>

    <div id="responseMSG"></div>

    <table class="table table-striped table-bordered">
        <tr>
            <th colspan="2" class="text-center">FIRMANTE</th>
        </tr>
        <tr>
            <th>Empresa</th>
            <td><?= isset($info['adicional']['empresa']['nombre']) ? $info['adicional']['empresa']['nombre'].' - '.$info['adicional']['empresa']['nit'] : '' ?></td>
        </tr>
        <tr>
            <th>Nombre</th>
            <td><?= $nombre_usuario ?></td>
        </tr>
        <tr>
            <th>Documento</th>
            <td><?= $info['usuario']['id'] ?></td>
        </tr>
        <tr>
            <th>Tipo</th>
            <td><?= $info['firma']['tipo_nombre'] ?></td>
        </tr>
    </table>

    <div class="panel panel-success">
        <div class="panel-heading text-center"><b>FIRMAS EN DECLARACIÓN</b></div>

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if( isset($v->firmas) && count($v->firmas) > 0 ){   
                        foreach($v->firmas AS $key => $item ){
                            echo "<tr>
                                    <td>".$item['first_name']." ".$item['last_name']."</td>
                                    <td>".$item['id_usuario']."</td>
                                    <td>".$item['label']."</td> 
                                    <td>".$item['fecha_firma']."</td>
                                </tr>";
                        }
                    }else{
                        echo "<tr>
                                <td colspan=\"5\" align=\"center\">No  hay firmas registradas.</td>
                            </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    

    <?php
        if(  isset($v) && $v->state )
        {
            ?>
            <?= form_open('firma/signProcess', 'method="post" id="formulario_firmar"') ?>
                <div class="alert alert-dismissable alert-success">
                    Los siguientes términos y condiciones (los "Términos y Condiciones") rigen el uso que usted le dé a esta declaración, incluyendo contenido derivado de la misma. Acepta que conoce y entiende los términos y condiciones en los que la firma electrónica del usuario es valida y legal.
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="accept" value="1"> Acepto
                    </label>
                </div>

                <p>Enviar código de verificación al correo electrónico (<?php echo $info['usuario']['email'] ?>)</p>

                <div class="form-group">
                    <label for="codigo_v">Código </label>
                    <div class="input-group">
                        <input type="text"
                            class="form-control"
                            name="codigo_v"
                            id="codigo_v"
                            size="35"
                            placeholder="Ingrese el codigo de 6 digitos"
                        >
                        <span class="input-group-btn">
                            <button type="button"
                                class="btn btn-primary"
                                data-destino="<?= $nombre_usuario ?>"
                                id="sendCode"
                                data-id="<?php echo $info['firma']['id'] ?>"
                                data-email="<?php echo $info['usuario']['email'] ?>"
                            >
                                <b>Enviar Código</b>
                            </button>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="codigo_v">Clave de firma </label>
                    <input type="password"
                        name="clave_firma"
                        size="35"
                        class="form-control"
                    >
                </div>

                <div class="text-center">
                    <button class="btn btn-success" type="submit" id="btnSignEnd" title="Firmar Declaración">Firmar</button>
                </div>

                <input type="hidden" name="firma_id" value="<?php echo $info['firma']['id'] ?>">
                <input type="hidden" name="referencia" value="<?php echo $ref ?>">
            <?= form_close() ?>
            <?php
        } else {
            ?>
            <div class="alert alert-dismissable alert-success">
                <?= $v->message ?>
            </div>
            <?php
        }
    ?> 
</div>