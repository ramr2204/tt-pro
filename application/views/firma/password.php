<div id="changePws">
    <div class="text-center">
        <h3>Debe asignar una segunda clave para firmar electrónicamente.</h3>
        <p>Recuerde que la contraseña debe cumplir con lo siguiente.</p>
        <ul style="list-style:none;padding: 0;">
            <li>2 numeros</li>
            <li>1 letra may&uacute;scula</li>
            <li>1 letra min&uacute;scula</li>
            <li>m&iacute;nimo 8 caracteres</li>
        </ul>
    </div>
    <div class="row">
        <!-- Alertas -->
        <div class="col-md-12" id="responseSign"></div>

        <div class="col-md-6 column form-group">
            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave" class="pwd-sign form-control">
        </div>
        <div class="col-md-6 column form-group">
            <label for="confirm">Confirmar Clave</label>
            <input type="password" name="confirm" id="confirm" class="pwd-sign form-control">
        </div>

        <div class="col-md-12 text-center">
            <input type="button"
                class="btn btn-success"
                onclick="generarClave()"
                value="Generar Contrase&ntilde;a">
        </div>
    </div>

    <input type="hidden" name="codigo" class="pwd-sign" value="<?= $user ?>">
</div>