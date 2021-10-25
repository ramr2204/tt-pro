<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="row"> 
    <div class="col-sm-12">    
        <h1><?php echo lang('index_heading');?></h1>
        <?php echo anchor(base_url().'usuariosFirma/create','<i class="fa fa-plus"></i> '. lang('index_create_user_link'),'class="btn btn-large  btn-primary"'); ?>
        <br><br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tablaq">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>E-mail</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>     
            </table>
        </div>
    </div>   
 </div>

<script type="text/javascript" language="javascript" charset="utf-8">

    tipos_usuarios = JSON.parse('<?= json_encode($tipos_usuarios) ?>');
    estado_activo = '<?= $estado_activo ?>';

    $(function () {
        construirTablaFirmas();

        $(document).on('click', '.state-change', cambiarEstadoFirma);
        $(document).on('click', '.change-key', solicitarCambioDeClaveContribuyente);
    });

    function construirTablaFirmas() {
        var oTable = $('#tablaq').dataTable( {
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?php echo base_url(); ?>index.php/usuariosFirma/dataTable',
            'sServerMethod': 'POST',
            'aoColumns': [
                { 'sClass': 'center','sWidth': '5%' }, /*id 0*/
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item' },
                { 'sClass': 'item', 'bSortable': false,'bSearchable': false },
                { 'sClass': 'item' },
                { 'sClass': 'center','bSortable': false,'bSearchable': false,'sWidth': '5%' },
            ],
            'fnRowCallback':function( nRow, aData, iDataIndex ) {

                var procesos = '';

                if(aData[7] == estado_activo) {
                    procesos += `<button class="btn btn-success btn-xs state-change" data-st="${aData[7]}" type="button" data-id="${aData[0]}" title="Inactivar la Firma">
                        <i class="glyphicon glyphicon-ok"></i> Activa
                    </button>`;
                } else {
                    procesos += `<button class="btn btn-danger btn-xs state-change" type="button"  data-st="${aData[7]}" data-id="${aData[0]}" title="Activar la firma">
                        <i class="glyphicon glyphicon-remove"></i> Inactiva
                    </button>`;
                }

                procesos += `<button class="btn btn-info btn-xs change-key" type="button" data-id="${aData[0]}" title="Forzar cambio de clave al usuario">
                    <i class="glyphicon glyphicon-tag"></i> Cambio clave
                </button>`;

                
                $('td:eq(5)', nRow).html(tipos_usuarios[aData[5]])
                $('td:eq(7)', nRow).html(procesos)
            }, 

        } );

        oTable.fnSearchHighlighting();
    }

    function cambiarEstadoFirma(e) {
        var item = $(this);
        var formData = new FormData();
        formData.append('st', $(this).data('st'));
        formData.append('id', $(this).data('id'));
        axios
            .post(base_url + 'index.php/usuariosFirma/estadoFirma', formData)
            .then(response => {
                if (response.data.hasOwnProperty('id')) {
                    $(item).data('st', response.data.estado);
                    if (response.data.estado == 1) {
                        $(item).removeClass('btn-danger').addClass('btn-success');
                        $(item).html('<i class="glyphicon glyphicon-ok"></i> Activa');
                        $(item).attr('title', 'Inactivar la Firma');
                        swal('Correcto', 'Se activo la firma correctamente', 'success');
                    } else {
                        $(item).removeClass('btn-success').addClass('btn-danger');
                        $(item).html('<i class="glyphicon glyphicon-remove"></i> Inactiva');
                        $(item).attr('title', 'Activar la Firma');
                        swal('Correcto', 'Se inactivo la firma correctamente', 'success');
                    }
                } else {
                    console.log(response);
                    swal(
                        'Errores!',
                        'Se Presento un problema, intente mas tarde!',
                        'error'
                    );
                }
            })
            .catch((error) => {
                console.log('Error: ', error);
            });
    }

    function solicitarCambioDeClaveContribuyente() {
        var formData = new FormData();
        formData.append('id', $(this).data('id'));
        axios
            .post(base_url + 'index.php/usuariosFirma/requestChange', formData)
            .then((response) => {
                if (response.data.hasOwnProperty('id')) {
                    swal(
                        '',
                        'Se activo el cambio de clave al usuario, la pr\u00F3xima vez que vaya a firmar electr\u00F3nicamente le solicitara asignar una nueva clave.',
                        'success'
                    );
                } else {
                    console.log(response);
                    swal(
                        'Errores!',
                        'No se puede forzar el cambio de clave, el usuario ya tiene esta caracteristica activa o es invalido.',
                        'error'
                    );
                }
            })
            .catch((error) => {
                console.log('Error: ', error);
            });
    }

</script>