<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<style>
    .spinning {
        animation: spin 1s infinite linear;
    }

    @keyframes spin{
        0%{
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100%{
            -webkit-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>

<div class="row"> 
    <div class="col-sm-12">
        <h1>Declaraciones</h1>
        <?php
            if( $this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/add') ) {
                echo anchor(base_url().'declaraciones/create','<i class="fa fa-plus"></i> '. lang('index_create_user_link'),'class="btn btn-large  btn-primary"');
            }
        ?>
        <br><br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Empresa</th>
                        <th>Estampilla</th>
                        <th>Periodo</th>
                        <th>Tipo de Declaracion</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>
 </div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="zoneModal"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSign" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="contentSign"></div>
        </div>
    </div>
</div>

<!-- Modal cargue soporte -->
<div class="modal fade" id="modalSoporte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body paga">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo form_open_multipart("declaraciones/cargarPago",'role="form"');?>
                            <input id="declaracion" type="hidden" name="declaracion"/>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered " id="tablaq">
                                    <thead>
                                        <tr>
                                            <th colspan="1" class="text-center small" width="20%">
                                                <img src="<?php echo base_url() ?>images/gobernacion.jpg" height="60" width="70" >
                                            </th>
                                            <th colspan="3" class="text-center small" width="60%">Gobernación de Boyacá <br> Secretaría de Hacienda <br> Dirección de Recaudo y Fiscalización</th>
                                            <th colspan="1" class="text-center small" width="20%">
                                                <img src="<?php echo base_url() ?>images/logo.png" height="50" width="80" >
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5"></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5">
                                                <div class="col-xs-12 text-center">
                                                        <label>REGISTRAR SOPORTE DE PAGO DE LA DECLARACION</label>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-sm-offset-4 text-center form-group">
                                                        <input id="soporte_pago" type="file" class="file" name="soporte_pago" multiple=false >
                                                </div>
                                                <div class="col-xs-12 text-center">
                                                        <button type="submit" class="btn btn-primary">Cargar</button>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php echo form_close();?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                <small> "Boyacá Avanza"<br>
                  Palacio de la Torre, Calle 20 No. 9 – 90 <br>
                  Teléfono PBX+(57)608742 0150<br>
                  contactenos@boyaca.gov.co </small> 
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">

    meses = JSON.parse('<?= json_encode($meses) ?>');
    tipos_declaraciones = JSON.parse('<?= json_encode($tipos_declaraciones) ?>');
    firma = JSON.parse('<?= json_encode($firma) ?>');
    estados_declaraciones = JSON.parse('<?= json_encode($estados_declaraciones) ?>');

    declaracion_inicial = '<?= $declaracion_inicial ?>';

    permiso = {
        'firmar': <?= (int)($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/firmar')) ?>,
        'liberar_firmas': <?= (int)($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/liberarFirmas')) ?>,
        'cargar_pago': <?= (int)($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/cargarPago')) ?>,
        'detalles': <?= (int)($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/detalles')) ?>,
        'solicitar_correccion': <?= (int)$this->ion_auth->in_menu('declaraciones/solicitarCorreccion') ?>,
        'corregir': <?= (int)($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/corregir')) ?>,
        'comprobar': <?= (int)($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/comprobar')) ?>,
    }

    $(function () {
        construirTablaDeclaraciones()

        $(document).on('click', '.btn-sign', renderSign)
        $(document).on('submit', '#formulario_firmar', submitFirmar);
        $(document).on('click', '#sendCode', enviarCodigoVerificacion);
        $(document).on('click', '.sign-modal', openModalSign);
        $(document).on('click', '.free-sign', liberarFirma);
        $(document).on('click', '.cargar-soporte', cargarSoporte);
        $(document).on('click', '.solicitar-correccion', solicitarCorreccion);
        $(document).on('click', '.corregir', corregir);
        $(document).on('click', '.comprobar', comprobar);

        $('#soporte_pago').fileinput({
            showCaption: false,
            browseClass: 'btn btn-default btn-sm',
            browseLabel: 'Archivo',
            showUpload: false,
            showRemove: false,
        });
    });

    function updatePage() {
        location.reload(true);
    }

    function capitalize(string) {
        if(string) {
            return string[0].toUpperCase() + string.slice(1)
        }
        return '';
    }

    function construirTablaDeclaraciones() {
        var boton_firmar = ''

        if(firma.id && permiso.firmar) {
            var cambio_firma = firma.change_password == 1 ? 1 : 0

            boton_firmar = `<button type="button"
                    class="btn-sign sign btn btn-success btn-xs"
                    data-st="${cambio_firma}"
                    data-user="${firma.id}"
                    data-ref=":id"
                    title="${ (cambio_firma ? 'Asignar segunda clave' : 'Firmar Declaraci&oacute;n') }"
                >
                ${ (cambio_firma ? '<i class="fa fa-key"></i>' : '<i class="fa fa-file"></i>') }
            </button>`
        }

        var oTable = $('#tablaq').dataTable( {
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?php echo base_url(); ?>index.php/declaraciones/dataTable',
            'sServerMethod': 'POST',
            'aaSorting': [[0,'desc']],
            'aoColumns': [
                { 'sClass': 'center','sWidth': '5%' }, /*id 0*/
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item', 'bSortable': false,'bSearchable': false },
                { 'sClass': 'item', 'bSortable': false,'bSearchable': false },
                { 'sClass': 'item' },
                { 'sClass': 'item','bSortable': false,'bSearchable': false },
                { 'sClass': 'center','bSortable': false,'bSearchable': false },
            ],
            'fnRowCallback':function( nRow, aData, iDataIndex ) {

                var fecha_partida = aData[3].split('-')

                $('td:eq(3)', nRow).html(capitalize(meses[Number(fecha_partida[1])]) + ' ' + fecha_partida[0])

                $('td:eq(4)', nRow).html(tipos_declaraciones[aData[4]])

                var acciones = '';

                acciones += `<a class="btn btn-default btn-xs"
                    href="${base_url}declaraciones/info/${aData[0]}"
                    title="Ver información"
                >
                    <i class="fa fa-eye"></i>
                </a>`;

                if(permiso.detalles) {
                    acciones += `<a class="btn btn-info btn-xs"
                        href="${base_url}declaraciones/detalles/${aData[0]}"
                        title="Ver detalles"
                    >
                        <i class="fa fa-list"></i>
                    </a>`;
                }

                if(aData[7] != '') {
                    acciones += `<a class="btn btn-primary btn-xs"
                        href="${base_url}${aData[7]}"
                        title="Ver anexo"
                        target="_blank"
                    >
                        <i class="fa fa-files-o"></i>
                    </a>`;
                }

                switch (aData[6]) {
                    // Inicializada
                    case '1':
                        acciones += boton_firmar.replaceAll(':id', aData[0])
                        break;
                    // Firmada
                    case '2':
                        if(permiso.liberar_firmas) {
                            acciones += `<button
                                class="btn btn-primary sign-modal btn-xs"
                                data-cod="${aData[0]}"
                                title="Visualizar Firmas"
                            >
                                <i class="fa fa-eye"></i>
                            </button>`;
                        }

                        if(permiso.cargar_pago) {
                            acciones += `<button type="button"
                                class="btn btn-primary cargar-soporte btn-xs"
                                title="Cargar soporte"
                                data-ref="${aData[0]}"
                            >
                                <i class="fa fa-upload"></i>
                            </button>`;
                        }
                        break;
                    // Pagada
                    case '3':
                        if(permiso.comprobar) {
                            acciones += `<button
                                class="btn btn-info comprobar btn-xs"
                                data-cod="${aData[0]}"
                                title="Comprobar"
                            >
                                <i class="fa fa-check-square"></i>
                            </button>`;
                        }
                        break;
                }

                // Permita solicitar correccion si es iniciada y es inicial
                if(aData[6] == '1' && aData[4] == declaracion_inicial) {
                    if(permiso.corregir) {
                        acciones += `<button
                            class="btn btn-info corregir btn-xs"
                            data-cod="${aData[0]}"
                            title="Corregir"
                        >
                            <i class="fa fa-wrench"></i>
                        </button>`;
                    }
                    if(permiso.solicitar_correccion) {
                        acciones += `<button
                            class="btn btn-info solicitar-correccion btn-xs"
                            data-cod="${aData[0]}"
                            title="Solicitar Corrección"
                        >
                            <i class="fa fa-send"></i>
                        </button>`;
                    }
                }

                if(['2', '3', '6','7'].includes(aData[6])){
                    acciones += `<a class="btn btn-danger btn-xs"
                        href="${base_url}uploads/declaraciones/comprobante_declaracion_${aData[0]}.pdf"
                        title="Ver declaración"
                        target="_blank"
                    >
                        <i class="fa fa-file-pdf-o"></i>
                    </a>`;
                }

                acciones = acciones ? '<div class="btn-group">'+ acciones +'</div>' : ''

                $('td:eq(7)', nRow).html(acciones);
                $('td:eq(6)', nRow).html(estados_declaraciones[aData[6]]);
            }, 

        } );

        oTable.fnSearchHighlighting();
    }

    function renderSign() {
        var element = $(this);
        var st = $(this).data('st');
        var user = $(this).data('user');
        var ref = $(this).data('ref');

        var parametros = { st: st, user: user, ref: ref };

        $.ajax({
            url: base_url + 'index.php/firma/renderSignDeclaracion',
            type: 'POST',
            data: parametros,
            success: function (response) {
                $('#zoneModal').html(response);
                $('#myModal').modal('show');
            },
        });
    }

    function crearAleta(mensaje, tipo) {
        return `<div class="alert alert-dismissable alert-${tipo}">
                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-hidden="true"
                    >×</button>
                    <p>${mensaje}</p>
                </div>`;
    }

    function generarClave() {
        var pwds = $('.pwd-sign');
        var data = {};
        $.each(pwds, function (i, item) {
            data[$(item).attr('name')] = $(item).val();
        });

        $.ajax({
            url: base_url + 'index.php/usuariosFirma/asignarClave',
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.hasOwnProperty('state')) {
                    var color = 'danger';
                    if (response.state == 1) {
                        color = 'success';
                    }
                    
                    $('#responseSign').html(crearAleta(response.message, color))

                    if (response.state == 1) {
                        setTimeout(updatePage, 10000)
                    }
                }
            },
        });
    }

    function setNotifySign(msg='', color='info') {
        if (msg.trim() != '') {
            msg = msg + ' <span class="fa fa-spinner spinning" style="font-size: 20px;"></span>';
        }

        $('#responseMSG').html(crearAleta(msg, color));
    }

    function getElementParent(element, tag) {
        var tagEle = element.tagName;
        while (tag != tagEle) {
            element = element.parentNode;
            tagEle = element.tagName;
        }
        return element;
    }

    function submitFirmar(e) {
        e.preventDefault();

        if (!$('[name="accept"]:checked').length) {
            swal('Atenci\u00F3n', 'Debe aceptar los terminos y condiciones!', 'error');
            return false;
        }

        if ($('[name="clave_firma"]').val().length < 8) {
            swal('Atenci\u00F3n', 'Debe ingresar una clave valida!', 'error');
            return false;
        }

        if ($('[name="codigo_v"]').val().length != 6) {
            swal('Atenci\u00F3n', 'Debe ingresar un c\u00F3digo valido!', 'error');
            return false;
        }

        if (!confirm('\u00BFEsta seguro de firmar el elemento ?')) {
            swal('Atenci\u00F3n', 'Cancelado por usuario!!!', 'error');
            return false;
        }

        setNotifySign('La firma electr\u00F3nica se esta procesando... ', 'info');

        var form = getElementParent(e.target, 'FORM');
        var route = form.getAttribute('action');
        var formData = new FormData(form);
        $.ajax({
            url: route,
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                if (response.hasOwnProperty('state')) {
                    var band = false;
                    var color = '';

                    if (response.state == '1') {
                        band = true;
                        color = 'success';
                    } else {
                        color = 'danger';
                    }

                    $('#responseMSG').html(crearAleta(response.message, color));
                    $('#myModal').animate({ scrollTop: 0 }, 'slow');

                    if (response.hasOwnProperty('url') && response.url != null) {
                        window.open(base_url + response.url, '_blank');
                    }
                    if (band) {
                        setTimeout(updatePage, 15000);
                    }
                } else {
                    alert('Se Presento un error al procesar la firma');
                }
            },
        });
    }

    function enviarCodigoVerificacion(e) {
        setNotifySign('El c&oacute;digo esta siendo enviado ', 'info');
        var element = getElementParent(e.target, 'BUTTON');
        var mail = element.dataset.email;
        var id = element.dataset.id;
        var destino = element.dataset.destino;
        var parametros = { mail: mail, id: id, destino: destino };

        $.ajax({
            url: base_url + 'index.php/firma/sendMail',
            type: 'POST',
            data: parametros,
            success: function (response) {
                if (response.hasOwnProperty('status')) {
                    var obj = JSON.stringify(response);
                    console.log(JSON.parse(obj));
                    if (response.status == 1) {
                        $('#responseMSG').html(crearAleta(response.message, 'info'));
                        swal("Atenci\u00F3n", "" + response.message + "", "success");
                    } else {
                        $('#responseMSG').html(crearAleta('Se presento un error', 'danger'));
                        console.log(response.message);
                    }
                } else {
                    $('#responseMSG').html(crearAleta('Se presento un error', 'danger'));
                    console.log(response.message);
                }
            },
        });
    }

    function openModalSign() {
        var elemento = this;
        var codigo = $(elemento).data('cod');

        $('#contentSign').html('');

        //Contenido Ajax
        $.ajax({
            url: base_url + 'index.php/firma/obtenerFirmas',
            type: 'POST',
            data: { codigo: codigo },
            success: function (response) {
                $('#contentSign').html(response);
                $('#modalSign').modal('show');
            },
        });
    }

    function liberarFirma() {
        var elemento = this;
        var opt = confirm(
            '¿Realmente desea liberar la firma ?, recuerde que una vez liberada no se podrá restaurar.'
        );
        if (opt != true) {
            alert('Operación cancelada por el usuario');
            return false;
        }
        var codigo = $(elemento).data('codigo');
        var declaracion = $(elemento).data('declaracion');

        $.ajax({
            url: base_url + 'index.php/firma/liberarFirma',
            type: 'POST',
            data: { codigo: codigo },
            success: function (response) {
                $('#contentSign').html(`
                    <div class="text-center">
                        <h4>${response}</h4>
                        <button type="button"
                            class="btn btn-primary sign-modal"
                            data-cod="${declaracion}"
                        >
                            <i class="glyphicon glyphicon-th-list"></i> Regresar a firmas
                        </button>
                    </div>
                `);
            },
        });
    }

    function cargarSoporte() {
        var ref = $(this).data('ref');

        $('#declaracion').val(ref);
        $('#modalSoporte').modal('show');
    }

    function solicitarCorreccion() {
        var cod = $(this).data('cod');

        swal({
            title: '¿Esta seguro de solicitar una corrección?',
            text: 'Una vez solicitada la corrección no se podrá realizar acciones sobre la declaración hasta que obtenga una respuesta.',
            icon: 'warning',
            buttons: true,
            buttons: ['Cancelar', 'Aceptar'],
        })
        .then(function(confirmo) {
            if (confirmo) {
                $.ajax({
                    url: base_url + 'index.php/declaraciones/solicitarCorreccion',
                    type: 'POST',
                    dataType: 'json',
                    data: {declaracion: cod},
                    success: respuestaGenerica,
                });
            }
        });
    }

    function corregir() {
        var cod = $(this).data('cod');

        swal({
            title: '¿Esta seguro de corregir esta declaración?',
            content: { element: 'textarea', attributes: {'placeholder': 'Observaciones'} },
            icon: 'warning',
            buttons: true,
            buttons: ['Cancelar', 'Aceptar'],
        }).then(function(confirmo) {
            if(confirmo) {
                var observaciones = document.querySelector('.swal-content__textarea').value;

                $.ajax({
                    url: base_url + 'index.php/declaraciones/corregir',
                    type: 'POST',
                    dataType: 'json',
                    data: {declaracion: cod, observaciones: observaciones},
                    success: respuestaGenerica,
                });
            }
        });
    }

    function comprobar() {
        var cod = $(this).data('cod');

        swal({
            title: '¿Esta seguro de comprobar esta declaración?',
            content: { element: 'textarea', attributes: {'placeholder': 'Observaciones'} },
            icon: 'warning',
            buttons: {
                cancel: 'Cancelar',
                catch: {
                    text: 'Aceptar',
                    value: 'aceptar',
                },
                defeat: {
                    text: 'Rechazar',
                    value: 'rechazar',
                    className: 'swal-button--danger',
                },
            },
        }).then(function(opcion) {
            if(opcion) {
                var observaciones = document.querySelector('.swal-content__textarea').value;

                $.ajax({
                    url: base_url + 'index.php/declaraciones/comprobar',
                    type: 'POST',
                    dataType: 'json',
                    data: {declaracion: cod, observaciones: observaciones, opcion: opcion},
                    success: respuestaGenerica,
                });
            }
        });
    }

    function respuestaGenerica(response) {
        if(response.exito) {
            swal('Atenci\u00F3n', response.mensaje, 'success');
        } else {
            swal('Error', response.mensaje, 'error');
        }
    }
</script>