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
                            <img src="<?php echo base_url() ?>images/gobernacion.jpg" height="60" width="70" >
                        </th>
                        <th colspan="4" class="text-center small" width="60%">Gobernación de Boyacá <br> Secretaría de Hacienda <br> Dirección de Recaudo y Fiscalización</th>
                        <th colspan="1" class="text-center small" width="20%">
                            <img src="<?php echo base_url() ?>images/logo.png" height="50" width="80" >
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(count($facturas_retencion) > 0)
                        {
                            ?>
                            <tr>
                                    <td colspan="6"></td>
                            </tr>
                            <tr>
                                    <td colspan="6" class="text-center"><strong>Facturas por Retención</strong></td>
                            </tr>
                            <tr>
                                    <td colspan="1" class="text-center"><strong>Estampilla</strong></td>
                                    <td colspan="1" class="text-center"><strong>Valor total</strong></td>
                                    <td colspan="1" class="text-center"><strong>Número de cuota</strong></td>
                                    <td colspan="1" class="text-center"><strong>Valor de cuota</strong></td>
                                    <td colspan="1" class="text-center"><strong>Saldo a pagar</strong></td>
                                    <td colspan="1" class="text-center"><strong>Procesos</strong></td>
                            </tr>
                            <?php
                                    $total = 0;
                                    $saldo_total = 0;
                                    $estampillas_pagadas = 0;

                                    foreach($facturas_retencion as $factura)
                                    {
                                        $saldo = floor($factura->valor_total - $factura->valor_pagado);
                                        ?>
                                        <tr>
                                            <td colspan="1">
                                                <?= $factura->fact_nombre; ?>
                                                <?php
                                                        if ($factura->fact_rutaimagen)
                                                        {
                                                            ?>
                                                            <img src="<?= base_url().$factura->fact_rutaimagen; ?>" height="60" width="60" >
                                                            <?php
                                                        }
                                                ?>
                                            </td>
                                            <td colspan="1" class="text-center"><?= '$'.number_format($factura->valor_total, 2, ',', '.') ?></td>
                                            <td colspan="1" class="text-center"><?= $factura->numero_cuota ?> / <?= $factura->cantidad_pagos ?></td>
                                            <td colspan="1" class="text-center"><?= '$'.number_format($factura->valor_cuota, 2, ',', '.') ?></td>
                                            <td colspan="1" class="text-center"><?= '$'.number_format($saldo, 2, ',', '.') ?></td>
                                            <td colspan="1" class="text-center">
                                                <?php
                                                    if($saldo != 0)
                                                    {
                                                        ?>
                                                        <a href="#"
                                                            class="btn btn-info pagar-estampilla"
                                                            title="Registrar pago cuota"
                                                            valor="<?= number_format($factura->valor_cuota, 2, ',', '') ?>"
                                                            fact-nombre="<?= $factura->fact_nombre ?>"
                                                            id-factura="<?= $factura->fact_id ?>"
                                                        >
                                                            <i class="fa fa-shopping-cart"></i>
                                                        </a>
                                                        <a href="#"
                                                            class="btn btn-primary descuento-estampilla"
                                                            title="Registrar descuento"
                                                            fact-nombre="<?= $factura->fact_nombre ?>"
                                                            id-factura="<?= $factura->fact_id ?>"
                                                        >
                                                            <i class="fa fa-minus-circle"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                            $total += $factura->valor_total;
                                            $saldo_total += $saldo;

                                            if($saldo == 0)
                                            {
                                                $estampillas_pagadas++;
                                            }
                                    }
                            ?>
                            <tr>
                                    <td colspan="1" class="text-right"><strong>Total</strong></td>
                                    <td colspan="1" class="text-center"><?= '$'.number_format($total, 2, ',', '.') ?></td>
                                    <td colspan="2"></td>
                                    <td colspan="1" class="text-center"><?= '$'.number_format($saldo_total, 2, ',', '.') ?></td>
                                    <td colspan="1"></td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?
        if($estampillas_pagadas != count($facturas_retencion))
        {
            ?>
            <div class="col-sm-12 text-center">
                <button class="btn btn-info" id="pagar_todo">Pagar Todo</button>
            </div>
            <?
        }
    ?>
    <div class="col-sm-12" style="display:none" id="form_pago_estampilla">
        <div class="row">
            <hr>
            <?= form_open_multipart('liquidaciones/pagarEstampilla','role="form"') ?>
                <input type="hidden" name="id_factura" id="id_factura_cont">
                <input type="hidden" name="id_contrato" class="id_contrato_cont">
                <input type="hidden" name="todos" class="todos_cont" value="0">

                <h4 class="text-center"><b>Pago de Estampillas por Retención</b></h4>

                <div class="form-group col-md-6">
                    <label>Estampilla</label>
                    <input type="text" class="form-control" id="nombre_estampilla" disabled>
                </div>
                <div class="form-group col-md-6">
                    <label>Valor</label>
                    <input type="text" name="valor" class="form-control" id="valor_cont">
                </div>
                <div class="form-group col-sm-12">
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
                <div class="form-group col-sm-12">
                    <input id="soporte_cont" type="file" class="file" name="soporte" multiple="false">
                </div>
                <div class="form-group col-sm-12">
                    <label for="observaciones_cont">Observaciones</label>
                    <textarea class="form-control" id="observaciones_cont" name="observaciones"></textarea>
                </div>

                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-success">Pagar</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
    <div class="col-sm-12" style="display:none" id="form_descuento_estampilla">
        <div class="row">
            <hr>
            <?= form_open_multipart('liquidaciones/descuentoEstampilla','role="form"') ?>
                <input type="hidden" name="id_factura" id="id_factura_desc">
                <input type="hidden" name="id_contrato" class="id_contrato_cont">

                <h4 class="text-center"><b>Descuento de Estampillas por Retención</b></h4>

                <div class="form-group col-md-6">
                    <label>Estampilla</label>
                    <input type="text" class="form-control" id="nombre_estampilla_desc" disabled>
                </div>
                <div class="form-group col-md-6">
                    <label for="valor_desc">Valor</label>
                    <input type="text" name="valor" class="form-control" id="valor_desc">
                </div>
                <div class="form-group col-sm-12">
                    <label for="observaciones_desc">Observaciones</label>
                    <textarea class="form-control" id="observaciones_desc" name="observaciones"></textarea>
                </div>

                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-success">Registrar</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    $('.date').datepicker({format:'yyyy-mm-dd',type:'component'});

    $(document).on('click', '.pagar-estampilla', function(){
        $('#form_descuento_estampilla').hide();
        $('#valor_cont').closest('.form-group').show();

        $('#id_factura_cont').val($(this).attr('id-factura'));
        $('#nombre_estampilla').val($(this).attr('fact-nombre'));

        // El blur es para activar la libreria autoNumeric
        $('#valor_cont').val($(this).attr('valor')).blur();

        $('.todos_cont').val(0);

        $('#form_pago_estampilla').slideDown();
    });

    $(document).on('click', '.descuento-estampilla', function(){
        $('#form_pago_estampilla').hide();

        $('#id_factura_desc').val($(this).attr('id-factura'));
        $('#nombre_estampilla_desc').val($(this).attr('fact-nombre'));
        $('#form_descuento_estampilla').slideDown();
    });

    $(document).on('click', '#pagar_todo', function() {
        // Simula como se hubiera clickeado la primera estampilla a pagar
        $('.pagar-estampilla')[0].click();

        $('.todos_cont').val(1);
        $('#nombre_estampilla').val('Todas una cuota');
        $('#valor_cont').closest('.form-group').hide();
    });

    $("#soporte_cont").fileinput({
        showCaption: false,
        browseClass: "btn btn-default",
        browseLabel: "Cargar soporte",
        showUpload: false,
        showRemove: false,
    });

    $('#valor_cont').autoNumeric('init',{aSep: '.' , aDec: ',' });
    $('#valor_desc').autoNumeric('init',{aSep: '.' , aDec: ',' });
</script>