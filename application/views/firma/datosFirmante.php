<style>
    .datos-firmante tr td:first-child{
        background-color: #00632dbf;
        color: #fff;
    }
</style>

<?
    if( isset($info['firma']['id']) )
    {
        ?>
        <div classs="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <table class="table table-bordered datos-firmante">
                    <tr>
                        <td><b>Tipo</b></td>
                        <td><?= $info['firma']['tipo_nombre'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Documento</b></td>
                        <td><?= $info['usuario']['id'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Nombre</b></td>
                        <td><?= $info['usuario']['first_name'] . ' ' . $info['usuario']['last_name'] ?></td>
                    </tr>
                    <tr>
                        <td><b>E-mail</b></td>
                        <td><?= $info['usuario']['email'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Empresa</b></td>
                        <td><?= $info['adicional']['empresa']['nombre'] ?></td>
                    </tr>
                    <tr>
                        <td><b>NIT</b></td>
                        <td><?= $info['adicional']['empresa']['nit'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Desde</b></td>
                        <td><?= $info['firma']['created_at'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?
    } else {
        ?>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <div class="alert alert-dismissable alert-success">
                    <h3>La firma ingresada no es valida, verifique el código de barras del archivo.</h3>
                </div>
            </div>
        </div>
        <?
    }

