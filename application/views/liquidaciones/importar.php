<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratos/contratos_importarcontratos.php
*   Descripcion:       permite crear un nuevo contrato
*   Fecha Creacion:    18/jul/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-07-18
*
*/
?>
<br>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix">
            <div class="col-md-4 column">
            </div>
            <div class="col-md-4 column">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Importar Liquidaciones</h1></div>
                    <div class="panel-body">
                        <?= form_open_multipart('liquidaciones/importarLiquidaciones') ?>
                            <div class="form-group">
                                <label for="vigencia">Archivo</label>
                                <input type="file" name="archivo">
                                <?php echo form_error('vigencia','<span class="text-danger">','</span>'); ?>
                                <br><br>
                                <div class="pull-right btn-group">
                                    <a href="<?= base_url() ?>uploads/Plantilla_cargue_liquidaciones.xlsx" target="_blank" class="btn btn-default">
                                        <i class="fa fa-file-excel-o"></i> 
                                        <span>Plantilla</span>
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-eye"></i> 
                                        <span>Previzualizar</span>
                                    </button>
                                </div>
                            </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <?
                if($datosCargue)
                {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="8" class="text-center"><strong>Facturas Liquidadas</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><strong>Número de contrato</strong></td>
                                    <td class="text-center"><strong>Vigencia</strong></td>
                                    <td class="text-center"><strong>Estampilla</strong></td>
                                    <td class="text-center"><strong>Porcentaje</strong></td>
                                    <td class="text-center"><strong>Valor total</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    # Se usa para que las celdas con rowspan no se repitan
                                    $indentificador = null;

                                    foreach($datosCargue AS $dato)
                                    {
                                        $cantidad_estampillas = $dato['facturas']['estampillas'];
                                        $cantidad_estampillas = !$cantidad_estampillas ? 0 : $cantidad_estampillas;

                                        foreach($dato['facturas']['estampillas'] AS $factura)
                                        {
                                            ?>
                                            <tr>
                                                <?
                                                    if($indentificador !== $dato['id'])
                                                    {
                                                        ?>
                                                        <td class="text-center"
                                                            rowspan="<?= count($cantidad_estampillas) ?>"
                                                        >
                                                            <?= $dato['numero'] ?>
                                                        </td>
                                                        <td class="text-center"
                                                            rowspan="<?= count($cantidad_estampillas) ?>"
                                                        >
                                                            <?= $dato['vigencia'] ?>
                                                        </td>
                                                        <?
                                                    }
                                                ?>
                                                <td><?= $factura->estm_nombre ?></td>
                                                <td class="text-center"><?= number_format($factura->esti_porcentaje, 2, ',', '.') ?>%</td>
                                                <td class="text-center"><?= '$'.number_format($dato['facturas']['est_totalestampilla'][$factura->estm_id], 2, ',', '.') ?></td>
                                            </tr>
                                            <?php
                                            $indentificador = $dato['id'];
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <?= form_open_multipart('liquidaciones/cargarLiquidacionesEstampillas') ?>

                        <input type="hidden" name="rutaArchivo" value="<?= $rutaArchivo ?>">

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-level-down"></i> 
                                <span>Cargar</span>
                            </button>
                        </div>
                    <?= form_close() ?>
                    <?
                }
            ?>
        </div>
    </div>
</div>
