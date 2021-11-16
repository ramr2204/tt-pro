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

<script type="text/javascript" language="javascript" charset="utf-8">

    meses = JSON.parse('<?= json_encode($meses) ?>');
    tipos_declaraciones = JSON.parse('<?= json_encode($tipos_declaraciones) ?>');
    firma = JSON.parse('<?= json_encode($firma) ?>');
    permiso_firmar = <?= (int)($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/firmar')) ?>;
    permiso_liberar_firmas = <?= (int)($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/liberarFirmas')) ?>;

    $(function () {
        construirTablaDeclaraciones()

        $(document).on('click', '.btn-sign', renderSign)
        $(document).on('submit', '#formulario_firmar', submitFirmar);
        $(document).on('click', '#sendCode', enviarCodigoVerificacion);
        $(document).on('click', '.sign-modal', openModalSign);
        $(document).on('click', '.free-sign', liberarFirma);
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

        if(firma.id && permiso_firmar) {
            var cambio_firma = firma.change_password == 1 ? 1 : 0

            boton_firmar = `<button type="button"
                    class="btn-sign sign btn btn-success"
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
            'aoColumns': [
                { 'sClass': 'center','sWidth': '5%' }, /*id 0*/
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item', 'bSortable': false,'bSearchable': false },
                { 'sClass': 'item', 'bSortable': false,'bSearchable': false },
                { 'sClass': 'item' },
                { 'sClass': 'center','bSortable': false,'bSearchable': false },
                { 'sClass': 'item','bSortable': false,'bSearchable': false, 'bVisible': false },
            ],
            'fnRowCallback':function( nRow, aData, iDataIndex ) {

                var fecha_partida = aData[3].split('-')

                $('td:eq(3)', nRow).html(capitalize(meses[Number(fecha_partida[1])]) + ' ' + fecha_partida[0])

                $('td:eq(4)', nRow).html(tipos_declaraciones[aData[4]])

                var acciones = '';

                // Inicializada
                if(aData[6] == 1) {
                    acciones += boton_firmar.replaceAll(':id', aData[0])
                } else if(aData[6] == 2) {
                    acciones += `<a class="btn btn-danger"
                        href="${base_url}uploads/declaraciones/comprobante_declaracion_${aData[0]}.pdf"
                        title="Ver declaración"
                        target="_blank"
                    >
                        <i class="fa fa-file-pdf-o"></i>
                    </a>`;

                    if(permiso_liberar_firmas) {
                        acciones += `<button
                            class="btn btn-primary sign-modal"
                            data-cod="${aData[0]}"
                            title="Visualizar Firmas"
                        >
                            <i class="fa fa-eye"></i>
                        </button>`
                    }
                }

                if(aData[7] != '') {
                    acciones += `<a class="btn btn-info"
                        href="${base_url}${aData[7]}"
                        title="Ver anexo"
                        target="_blank"
                    >
                        <i class="fa fa-files-o"></i>
                    </a>`
                }

                acciones = acciones ? '<div class="btn-group">'+ acciones +'</div>' : ''

                $('td:eq(6)', nRow).html(acciones);
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
</script>