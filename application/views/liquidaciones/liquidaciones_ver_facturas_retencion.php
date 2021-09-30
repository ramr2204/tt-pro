<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratos/contratos_list.php
*   Descripcion:       tabla que mustra todos los contratos existentes
*   Fecha Creacion:    20/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-05-20
*
*/
?>

<div class="row"> 
    <div class="col-sm-12">    
        <div id="errorModal"></div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered " id="tablaq">
                <thead>
                    <tr>
                        <th colspan="1" class="text-center small" width="20%">
                            <img src="<?php echo base_url() ?>images/gobernacion_cauca1.jpg" height="60" width="70" >
                        </th>
                        <th colspan="3" class="text-center small" width="60%">Gobernación del Cauca <br> Departamento Administrativo de Asuntos Jurídicos <br> Dirección de Contratación</th>
                        <th colspan="1" class="text-center small" width="20%">
                            <img src="<?php echo base_url() ?>images/gobernacion_cauca2.png" height="50" width="80" >
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(count($facturas_retencion) > 0)
                        {
                            ?>
                            <tr>
                                    <td colspan="5"></td>
                            </tr>
                            <tr>
                                    <td colspan="5" class="text-center"><strong>Facturas por Contingenia</strong></td>
                            </tr>
                            <tr>
                                    <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
                                    <td colspan="1" class="text-center"><strong>Valor total</strong></td>
                                    <td colspan="1" class="text-center"><strong>Número de cuota</strong></td>
                                    <td colspan="1" class="text-center"><strong>Valor de cuota</strong></td>
                                    <td colspan="1" class="text-center"><strong>Procesos</strong></td>
                            </tr>
                            <?php
                                    $total = 0;
                                    foreach($facturas_retencion as $factura)
                                    {
                                        ?>
                                        <tr>
                                            <td colspan="1">
                                                <?php echo $factura->fact_nombre; ?>
                                                <?php
                                                        if ($factura->fact_rutaimagen)
                                                        {
                                                            ?>
                                                            <img src="<?php echo base_url().$factura->fact_rutaimagen; ?>" height="60" width="60" >
                                                            <?php
                                                        }
                                                ?>
                                            </td>
                                            <td colspan="1" class="text-center"><?= '$'.number_format($factura->valor_total, 2, ',', '.') ?></td>
                                            <td colspan="1" class="text-center"><?= $factura->numero_cuota ?> / <?= $factura->cantidad_pagos ?></td>
                                            <td colspan="1" class="text-center"><?= '$'.number_format($factura->valor_cuota, 2, ',', '.') ?></td>
                                            <td colspan="1" class="text-center">
                                                <?php
                                                        if($factura->numero_cuota < $factura->cantidad_pagos)
                                                        {
                                                            ?>
                                                            <a href="#"
                                                                class="btn btn-info pagar-estampilla"
                                                                title="Registrar pago cuota"
                                                                valor="<?= number_format($factura->valor_cuota, 2, ',', '.') ?>"
                                                                id-factura="<?= $factura->fact_id ?>"
                                                            >
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </a>
                                                            <?php
                                                        }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                            $total += $factura->valor_total;
                                    }
                            ?>
                            <tr>
                                    <td colspan="1" class="text-right"><strong>Total</strong></td>
                                    <td colspan="1" class="text-center"><?= '$'.number_format($total, 2, ',', '.') ?></td>
                                    <td colspan="3"></td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-12" style="display:none" id="form_pago_estampilla">
        <?= form_open_multipart('liquidaciones/pagarEstampilla','role="form"') ?>
            <input type="hidden" name="id_factura" id="id_factura_cont">
            <input type="hidden" name="id_contrato" id="id_contrato_cont">

            <h4 class="text-center"><b>Pago de Estampillas por Contingencia</b></h4>

            <div class="form-group">
                <label>Valor</label>
                <input type="text" class="form-control" id="valor_cont" disabled>
            </div>
            <div class="form-group">
            <label for="fecha_cont">Fecha</label>
                <div class="input-group">
                    <input
                        id="fecha_cont"
                        type="text"
                        name="fecha"
                        class="form-control date"
                        required
                        autocomplete="off"
                    />
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
            <div class="form-group">
                <input id="soporte_cont" type="file" class="file" name="soporte" multiple="false" required>
            </div>
            <div class="form-group">
                <label for="observaciones_cont">Observaciones</label>
                <textarea class="form-control" id="observaciones_cont" name="observaciones"></textarea>
            </div>

            <button type="submit" class="btn btn-success btn-block">Pagar</button>
        <?= form_close() ?>
    </div>
</div>

<script>
    $('.date').datepicker({format:'yyyy-mm-dd',type:'component'});

    $(document).on('click', '.pagar-estampilla', function(){
        $('#id_factura_cont').val($(this).attr('id-factura'));
        $('#valor_cont').val($(this).attr('valor'));
        $('#form_pago_estampilla').slideDown();
    });

    $("#soporte_cont").fileinput({
        showCaption: false,
        browseClass: "btn btn-default",
        browseLabel: "Cargar soporte",
        showUpload: false,
        showRemove: false,
    });
</script>