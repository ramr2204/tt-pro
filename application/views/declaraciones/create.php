<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratistas/contratistas_add.php
*   Descripcion:       permite crear un nuevo contratista
*   Fecha Creacion:    12/may/2014
*   @author            David Mahecha <david.mahecha@turrisystem.com>
*   @version           2014-05-12
*
*/
?>
<br>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix">
            <div class="col-xs-12 column" id="notificacion">
            </div>
            <div class="col-md-1 column">
            </div>
            <div class="col-md-10 column">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex;align-items: end;">
                        <h1 style="flex: 1 0 auto;"><?= $esVisualizar ? 'Ver Declaración' : 'Generación de Declaraciones' ?></h1>
                        <?
                            if(count($consulta) != 0 && ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/detalles')))
                            {
                                ?>
                                <a href="<?php echo base_url(); ?>declaraciones/detalles/?empresa=<?= set_value('empresa', '', $esVisualizar) ?>&tipo_estampilla=<?= set_value('tipo_estampilla', '', $esVisualizar) ?>&periodo=<?= set_value('periodo', '', $esVisualizar) ?>"
                                    target="_blank"
                                    class="btn btn-primary"
                                    type="button"
                                    title="Previsualizar detalles"
                                >
                                    <i class="fa fa-align-justify"></i>
                                </a>
                                <?
                            }
                        ?>
                    </div>
                    <div class="panel-body">
                        <?
                            if(count($consulta) == 0)
                            {
                                ?>
                                <?= form_open('declaraciones/create') ?>

                                    <div class="col-md-6 column form-group">
                                        <label for="empresa">Empresa</label>
                                        <select class="form-control chosen-select" id="empresa" name="empresa" required="required" >
                                            <option value="0">Seleccione...</option>
                                            <?php
                                                foreach($empresas as $row)
                                                {
                                                    ?>
                                                    <option
                                                        value="<?= $row->id; ?>"
                                                        <?= set_select('empresa', $row->id, false, $esVisualizar) ?>
                                                    >
                                                        <?= $row->nombre; ?>
                                                    </option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                        <?= form_error('empresa','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="col-md-6 column form-group">
                                        <label for="tipo_estampilla">Tipo Estampilla</label>
                                        <select class="form-control chosen-select" id="tipo_estampilla" name="tipo_estampilla">
                                            <option value="0">Seleccione...</option>
                                            <?php
                                                foreach($estampillas as $estampilla)
                                                {
                                                    ?>
                                                    <option value="<?= $estampilla->id; ?>"
                                                        <?= set_select('tipo_estampilla', $estampilla->id, false, $esVisualizar) ?>
                                                    ><?= $estampilla->nombre; ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                        <?= form_error('tipo_estampilla','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="col-md-6 column form-group">
                                        <label for="periodo">Período gravable</label>
                                        <input type='text'
                                            class="form-control fechas-mes"
                                            name="periodo"
                                            id="periodo"
                                            value="<?= set_value('periodo', '', $esVisualizar); ?>"
                                            required="required"
                                            autocomplete="off"
                                        />
                                        <?= form_error('periodo','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="col-md-12 column form-group text-center">
                                        <?= anchor('declaraciones', '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-default"'); ?>
                                        <button name="acc" value="consultar"
                                            class="btn btn-primary" type="submit"
                                        >Consultar</button>
                                    </div>
                                <?= form_close() ?>
                                <?
                            }
                        ?>

                        <?
                            if(count($consulta) > 0)
                           // $prueba2 = set_value('recaudado', 0, $esVisualizar);
                            //$prueba3 = set_value('sanciones', 0, $esVisualizar);
                            //$prueba4 = set_value('intereses', 0, $esVisualizar);
                            {
                                ?>
                                <?= form_open_multipart('declaraciones/create', 'role="form"'); ?>

                                    <input type="hidden" name="empresa" value="<?= set_value('empresa', '', $esVisualizar) ?>">
                                    <input type="hidden" name="tipo_estampilla" value="<?= set_value('tipo_estampilla', '', $esVisualizar) ?>">
                                    <input type="hidden" name="periodo" value="<?= set_value('periodo', '', $esVisualizar) ?>">

                                    <div class="col-md-6 column form-group">
                                        <label for="tipo_declaracion">Tipo de declaración</label>
                                        <select class="form-control chosen-select" id="tipo_declaracion" name="tipo_declaracion">
                                            <option value="0">Seleccione...</option>
                                            <option value="1" <?= set_select('tipo_declaracion', '1', false, $esVisualizar) ?>>Inicial</option>
                                            <option value="2" <?= set_select('tipo_declaracion', '2', false, $esVisualizar) ?>>Corrección</option>
                                        </select>
                                        <?= form_error('tipo_declaracion','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="col-md-6 column form-group">
                                        <label for="recaudado">Valor recaudado</label>
                                        <input id="recaudado"
                                            type="number"
                                            name="recaudado"
                                            value="<?= set_value('recaudado', 0, $esVisualizar); ?>"
                                            class="form-control"
                                            required="required"
                                            min="0" />
                                        <?= form_error('recaudado','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6 column form-group">
                                        <label for="sanciones">Valor sanciones</label>
                                        <input id="sanciones"
                                            type="number"
                                            name="sanciones"
                                            value="<?= set_value('sanciones', 0, $esVisualizar); ?>"
                                            class="form-control"
                                            required="required"
                                            min="0" />
                                        <?= form_error('sanciones','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    <div class="col-md-6 column form-group">
                                        <label for="intereses">Valor intereses</label>
                                        <input id="intereses"
                                            type="number"
                                            name="intereses"
                                            value="<?= set_value('intereses', 0, $esVisualizar); ?>"
                                            class="form-control"
                                            required="required"
                                            min="0" />
                                        <?= form_error('intereses','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <!-- Correccion -->
                                    <div id="contenedor-correccion" style="display:none">
                                        <div class="col-xs-12">
                                            <legend>Datos de Corrección</legend>
                                        </div>
                                        <div class="col-md-6 column form-group">
                                            <label for="declaracion_correccion">No. declaración</label>
                                            <input id="declaracion_correccion"
                                                type="number"
                                                name="declaracion_correccion"
                                                value="<?= set_value('declaracion_correccion', '', $esVisualizar); ?>"
                                                class="form-control"
                                                min="0" />
                                            <?= form_error('declaracion_correccion','<span class="text-danger">','</span>'); ?>
                                        </div>

                                        <div class="col-md-6 column form-group">
                                            <label for="radicacion_correccion">No. de radicación</label>
                                            <input id="radicacion_correccion"
                                                type="number"
                                                name="radicacion_correccion"
                                                value="<?= set_value('radicacion_correccion', '', $esVisualizar); ?>"
                                                class="form-control"
                                                min="0" />
                                            <?= form_error('radicacion_correccion','<span class="text-danger">','</span>'); ?>
                                        </div>

                                        <div class="col-md-6 column form-group">
                                            <label for="fecha_correccion">Fecha</label>
                                            <input type='text'
                                                class="form-control fechas"
                                                name="fecha_correccion"
                                                id="fecha_correccion"
                                                value="<?= set_value('fecha_correccion', '', $esVisualizar); ?>"
                                                autocomplete="off"
                                            />
                                            <?= form_error('fecha_correccion','<span class="text-danger">','</span>'); ?>
                                        </div>

                                        <div class="col-md-6 column form-group">
                                            <label for="periodo_correccion">Periodo gravable</label>
                                            <input type='text'
                                                class="form-control fechas-mes"
                                                name="periodo_correccion"
                                                id="periodo_correccion"
                                                value="<?= set_value('periodo_correccion', '', $esVisualizar); ?>"
                                                autocomplete="off"
                                            />
                                            <?= form_error('periodo_correccion','<span class="text-danger">','</span>'); ?>
                                        </div>
                                    </div>

                                    <!-- Liquidacion -->
                                    <div>
                                        <div class="col-xs-12">
                                            <legend>Liquidación</legend>
                                        </div>
                                        
                                        <table class="table table-striped table-bordered">
                                            <thead class="text-center">
                                                <tr>
                                                    <td rowspan="2"> R</td>
                                                    <td rowspan="2"> D.1. Clase</td>
                                                    <td colspan="3"> D.2. Valor</td>
                                                    <td rowspan="2"> D.3. Tarifa</td>
                                                    <td rowspan="2"> D.4. Valor recaudo <?= strtolower($estampilla->nombre) ?></td>
                                                </tr>
                                                <tr>
                                                    <td> Valor base</td>
                                                    <td> Vigencia actual</td>
                                                    <td> Vigencia anterior</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?


                                                
                                                
                                                    foreach($consulta AS $detalle)
                                                    
                                                    {
                                                      
                                                        ?>
                                                        <tr class="text-center">
                                                            <td> <?= $detalle->clasificacion ?></td>
                                                            <td class="text-left"> <?= $detalle->clase ?></td>
                                                            <td>
                                                                <input name="detalle_base[<?= $detalle->clasificacion ?>]"
                                                                    value="<?= $detalle->base ?>"
                                                                    type="number"
                                                                    class="form-control base_detalle"
                                                                >
                                                            </td>
                                                            <td>
                                                                <input name="detalle_vigencia_actual[<?= $detalle->clasificacion ?>]"
                                                                    type="number"
                                                                    class="form-control"
                                                                    autocomplete="off"
                                                                    value="<?= set_value(
                                                                        'detalle_vigencia_actual['. $detalle->clasificacion .']',
                                                                        (isset($detalle->vigencia_actual) ? $detalle->vigencia_actual : 0),
                                                                        $esVisualizar
                                                                    ); ?>"

                                                                >
                                                            </td>
                                                            <td>
                                                                <input name="detalle_vigencia_anterior[<?= $detalle->clasificacion ?>]"
                                                                    type="number"
                                                                    class="form-control"
                                                                    autocomplete="off"
                                                                    value="<?= set_value(
                                                                        'detalle_vigencia_anterior['. $detalle->clasificacion .']',
                                                                        (isset($detalle->vigencia_anterior) ? $detalle->vigencia_anterior : 0),
                                                                        $esVisualizar
                                                                    ); ?>"
                                                                >

                                                            </td>
                                                            <td>
                                                                <?= number_format($detalle->porcentaje, 2, ',', '.') ?>%
                                                                <input name="detalle_porcentaje[<?= $detalle->clasificacion ?>]"
                                                                    value="<?= $detalle->porcentaje ?>"
                                                                    type="hidden"
                                                                    class="form-control porcentaje_detalle"
                                                                >
                                                            </td>
                                                            <td>
                                                                
                                                                <input name="detalle_pagado[<?= $detalle->clasificacion ?>]"
                                                                
                                                                    value="<?= $detalle->pagado  ?> "
                                                                    type="number"
                                                                    class="form-control pagado_detalle"
                                                                    readonly
                                                                >                                                           </td>
                                                        </tr>
                                                        <?
                                                    }
                                                ?>
                                                <tr class="text-center">
                                                    <!-- Al ultimo renglon se le adiciona uno -->
                                                    <td> <?= ++$detalle->clasificacion ?></td>
                                                    <td class="text-left"> Total a favor del departameto</td>
                                                    <td>
                                                        <input id="total_base" name="total_base" type="number" readonly class="form-control">
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <input id="total_pagado" type="number" readonly class="form-control">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagos -->
                                    <!-- a qui va una etiqueta de apertura de php
                                    $prueba5 = set_value('saldo_periodo_anterior', 0, $esVisualizar);
                                    $prueba6 = set_value('sanciones_pago', 0, $esVisualizar);
                                    $prueba7 = set_value('intereses_mora', 0, $esVisualizar);
                                    $prueba8 = set_value('saldo_favor', 0, $esVisualizar);
                                    -->
                                    <div>
                                        <div class="col-xs-12">
                                            <legend>Pagos</legend>
                                        </div>
                                        <div class="col-md-6 column form-group">
                                            <label for="valor_liquidado">Valor liquidado <?= strtolower($estampilla->nombre) ?></label>
                                            <input id="valor_liquidado"
                                                name="total_estampillas"
                                                type="number"
                                                class="form-control"
                                                readonly
                                                min="0" />
                                            <?= form_error('total_estampillas','<span class="text-danger">','</span>'); ?>
                                        </div>

                                        <div class="col-md-6 column form-group">
                                            <label for="saldo_periodo_anterior">- Saldo a favor período anterior</label>
                                            <input id="saldo_periodo_anterior"
                                                type="number"
                                                name="saldo_periodo_anterior"
                                                value="<?= set_value('saldo_periodo_anterior', 0, $esVisualizar); ?>"
                                                class="form-control"
                                                required="required"
                                                min="0" />
                                            <?= form_error('saldo_periodo_anterior','<span class="text-danger">','</span>'); ?>
                                        </div>

                                        <div class="col-md-6 column form-group">
                                            <label for="sanciones_pago">+ Valor sanciones</label>
                                            <input id="sanciones_pago"
                                                type="number"
                                                name="sanciones_pago"
                                                value="<?= set_value('sanciones_pago', 0, $esVisualizar); ?>"
                                                class="form-control"
                                                required="required"
                                                min="0" />
                                            <?= form_error('sanciones_pago','<span class="text-danger">','</span>'); ?>
                                        </div>

                                        <div class="col-md-6 column form-group">
                                            <label for="intereses_mora">+ Intereses de mora</label>
                                            <input id="intereses_mora"
                                                type="number"
                                                name="intereses_mora"
                                                value="<?= set_value('intereses_mora', 0, $esVisualizar); ?>"
                                                class="form-control"
                                                required="required"
                                                min="0" />
                                            <?= form_error('intereses_mora','<span class="text-danger">','</span>'); ?>
                                        </div>

                                        <div class="col-md-6 column form-group">
                                            <label for="total_pagos">Total a cargo por recaudo estampilla, sanciones e intereses</label>
                                            <input id="total_pagos"
                                                name="total_cargo"
                                                type="number"
                                                class="form-control"
                                                readonly
                                                min="0" />
                                            <?= form_error('total_cargo','<span class="text-danger">','</span>'); ?>
                                        </div>

                                        <div class="col-md-6 column form-group">
                                            <label for="saldo_favor">Saldo a favor</label>
                                            <input id="saldo_favor"
                                                type="number"
                                                name="saldo_favor"
                                                value="<?= set_value('saldo_favor', 0, $esVisualizar); ?>"
                                                class="form-control"
                                                required="required"
                                                min="0" />
                                            <?= form_error('saldo_favor','<span class="text-danger">','</span>'); ?>
                                        </div>
                                    </div>

                                    <div class="pull-right">
                                        <?= anchor('declaraciones', '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-default"'); ?>
                                        <?
                                            if(!$esVisualizar)
                                            {
                                                ?>
                                                <button name="acc" value="generar"
                                                    type="submit" class="btn btn-success"
                                                ><i class="fa fa-floppy-o"></i> Generar</button>
                                                <?
                                            }
                                        ?>
                                    </div>
                                <?= form_close();?>
                                <?
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        calculados_pagos = {
            'saldo_periodo_anterior': '-',
            'sanciones_pago': '+',
            'intereses_mora': '+'
        };

        $('.fechas-mes').datepicker({
            format: 'yyyy-mm',
            viewMode: 'months',
            minViewMode: 'months',
            autoclose: true
        });

        $('.fechas').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('.fechas-anio').datepicker({
            format: 'yyyy',
            viewMode: 'years',
            minViewMode: 'years',
            autoclose: true
        });

        $("#soporte").fileinput({
            showCaption: false,
            browseClass: 'btn btn-default',
            browseLabel: 'Cargar soporte de pago',
            showUpload: false,
            showRemove: false,
        });

        $('.chosen-select').chosen({disable_search_threshold: 10});

        $('#tipo_declaracion').change(changeTipoDeclaracion);
        $('#tipo_declaracion').change();

        // Se calcula para que las llaves se puedan seleccionar por jquery
        calculados_pagos_j = $( Object.keys(calculados_pagos).map(a => '#'+a).join(', ') );

        var totales_detalles_j = $('.base_detalle');
        totales_detalles_j.change(changeValorTotal);
        totales_detalles_j.change();

        calculados_pagos_j.change(calcularTotalCargo);
        calculados_pagos_j.change();
    });

    function changeTipoDeclaracion() {
        if($(this).val() == 2) {
            $('#contenedor-correccion').show();
        } else {
            $('#contenedor-correccion').hide();
        }
    }

    function changeValorTotal() {
        var total_base = 0;
        var total_pagado = 0;
        var indice = $('.base_detalle').index($(this));
        var valor_pagado = 0;

        $('.base_detalle').each(function(i, elemento) {
            total_base +=Math.round( Number(elemento.value));
        });

        valor_pagado = Math.round((( $(this).val() * $('.porcentaje_detalle').eq(indice).val() ) / 100) /100 )*100 ;
        $('.pagado_detalle').eq(indice).val(valor_pagado);

        $('.pagado_detalle').each(function(i, elemento) {
            total_pagado += Number(elemento.value);
        });

        $('#total_base').val(total_base);
        $('#total_pagado, #valor_liquidado').val(total_pagado);

        // Activa los eventos de suma que dependen del valor modificado
        calculados_pagos_j.first().change()
    }

    function calcularTotalCargo() {
        var total = Number($('#valor_liquidado').val());

        calculados_pagos_j.each(function(i, elemento){
            var elemento_j = $(elemento);
            var signo = calculados_pagos[elemento_j.attr('id')];

            total = eval( total + signo + Number(elemento_j.val()) ) ;
        });

        $('#total_pagos').val(total);
    }
</script>