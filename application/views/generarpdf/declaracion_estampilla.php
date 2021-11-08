<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            generarpdf_relacionEntregaEstampillas.php
*   Ruta:              /application/views/generarpdf/generarpdf_relacionEntregaEstampillas.php
*   Descripcion:       contiene la estructura del pdf del listado de impresiones del día
*   Fecha Creacion:    22/ene/2015
*   @author           Mike Ortiz <michael.ortiz@turrisystem.com>
*   @version          2015-01-13
*
*/
?>

<html>
    <head>
        <meta charset="UTF-8">
        <style type="text/css">
            body{
                font-size: 11px;
            }
            .text-center{
                text-align: center !important;
            }

            .text-left{
                text-align: left;
            }

            .text-right{
                text-align: right;
            }

            .m20{
                margin: 20px;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            .borde, .borde td, .borde th {
                border: 1px solid #000;
            }

            .borde-externo{
                border: 1px solid #000;
            }

            .borde-especifico{
                border: 1px solid #000;
            }

            .v-center{
                vertical-align: middle;
            }

            .v-bot{
                vertical-align: bottom;
            }

            .v-top{
                vertical-align: top;
            }

            .bold{
                font-weight: bold;
            }

            .subtitulo{
                font-weight: bold;
            }

            @page {
                margin: 10mm;
                /* margin: 10mm; */
                /* margin-header: 0mm;
                margin-footer: 0mm; */
            }

            .mb-10{
                margin-bottom: 10px
            }

            .pb-10{
                padding-bottom: 10px
            }

            .bg-verde{
                background-color: #c6efce;
                color: #006100;
            }

            .bg-amarillo{
                background-color: #ffff00;
            }

            .bg-gris{
                background-color: #acb9ca;
            }
        </style>
    </head>
    <body>

        <table class="borde-externo">
            <tr>
                <td style="padding:7px 12px">

                    <table cellpadding="2" class="borde mb-10">
                        <thead>
                            <tr>
                                <td class="text-center" rowspan="2">
                                    <img src="<?php echo $this->config->item('application_root'); ?>/images/escudo_largo.png" style="height: 9mm ;margin: 10px;">
                                </td>
                                <td class="text-center bold" rowspan="2">
                                    FORMATO
                                </td>
                                <td class="bold"> VERSIÓN: 2</td>
                            </tr>
                            <tr>
                                <td class="bold"> CÓDIGO: FF-P06-F02</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center bold" style="height:30px">
                                    DECLARACIÓN <?= mb_strtoupper($declaracion->estampilla, 'UTF-8') ?>
                                </td>
                                <td class="bold">
                                    FECHA: 26/Jul/2016
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="40%" rowspan="6" class="text-center bold">
                                    GOBERNACIÓN DE BOYACÁ<br>
                                    NIT. 891.800.498-1<br>
                                    CALLE 20 N° 9 - 90 TUNJA
                                </td>
                                <td width="60%" colspan="2" class="text-center">
                                    PARA USO OFICIAL EXCLUSIVAMENTE
                                </td>
                            </tr>
                            <tr>
                                <td>No. DE RADICACIÓN:</td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td>No. DECLARACIÓN:</td>
                                <td>
                                    <?= str_pad($declaracion->id, 10, '0', STR_PAD_LEFT) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>FECHA</td>
                                <td style="padding:0">
                                    <table>
                                        <tr>
                                            <td class="text-center">AÑO</td>
                                            <td class="text-center">MES</td>
                                            <td class="text-center">DÍA</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><?= date('Y', strtotime($declaracion->fecha)) ?></td>
                                            <td class="text-center"><?= date('m', strtotime($declaracion->fecha)) ?></td>
                                            <td class="text-center"><?= date('d', strtotime($declaracion->fecha)) ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>NOMBRE FUNCIONARIO:</td>
                                <td>
                                    <?= mb_strtoupper(($funcionario->first_name . ' ' . $funcionario->last_name), 'UTF-8') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>FIRMA:</td>
                                <td>

                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 class="subtitulo">SECCIÓN A. TIPO DE DECLARACIÓN</h4>

                    <table class="borde-externo mb-10">
                        <tbody>
                            <tr>
                                <td width="15%"> A.1. INICIAL</td>
                                <td width="4%" >
                                    <table class="borde"
                                        style="height:10px; width:10px;
                                            <?= $tipo_correccion != $declaracion->tipo_declaracion ? 'background-color:#000': '' ?>">
                                        <tr>
                                            <td style="height:10px; width:10px"></td>
                                        </tr>
                                    </table>
                                </td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td rowspan="3" class="v-top"> A.2. CORRECCIÓN</td>
                                <td rowspan="3" class="v-top">
                                    <table class="borde"
                                        style="height:10px; width:10px;
                                            <?= $tipo_correccion == $declaracion->tipo_declaracion ? 'background-color:#000': '' ?>">
                                        <tr>
                                            <td style="height:10px; width:10px"></td>
                                        </tr>
                                    </table>
                                </td>
                                <td rowspan="3" width="36%">
                                    <table class="borde">
                                        <tr>
                                            <td width="55%"> No. DECLARACIÓN:</td>
                                            <td width="45%"> <?= $declaracion->declaracion_correccion ?></td>
                                        </tr>
                                        <tr>
                                            <td> No. DE RADICACIÓN:</td>
                                            <td> <?= $declaracion->radicacion_correccion ?></td>
                                        </tr>
                                        <tr>
                                            <td> FECHA:</td>
                                            <td> <?= $declaracion->fecha_correccion ?></td>
                                        </tr>
                                        <tr>
                                            <td> PERIODO GRAVABLE:</td>
                                            <td> <?= $declaracion->periodo_correccion ? mb_strtoupper(strftime('%B %Y', strtotime($declaracion->periodo_correccion)), 'UTF-8') : '' ?></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="20%" class="text-right"> VALOR RECAUDADO</td>
                                <td width="25%" class="borde-especifico"> <?= $formatear_valor($declaracion->recaudado) ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"> VALOR SANCIONES</td>
                                <td class="borde-especifico"> <?= $formatear_valor($declaracion->sanciones) ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"> VALOR INTERESES</td>
                                <td class="borde-especifico"> <?= $formatear_valor($declaracion->intereses) ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 class="subtitulo">SECCIÓN B. PERÍODO GRAVABLE</h4>

                    <table class="borde-externo mb-10">
                        <tr>
                            <td style="padding:20px 0">AÑO</td>
                            <td>
                                <table class="borde">
                                    <tr>
                                        <td class="text-center"> <?= date('Y', strtotime($declaracion->periodo)) ?></td>
                                        <?
                                            $mes_periodo = (int)date('m', strtotime($declaracion->periodo));
                                            foreach($meses AS $numero => $nombre)
                                            {
                                                echo '<td class="text-center"> '.($numero == $mes_periodo ? 'X' : mb_strtoupper($nombre, 'UTF-8')).'</td>';
                                            }
                                        ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <h4 class="subtitulo">SECCIÓN C. INFORMACIÓN GENERAL</h4>

                    <table class="borde mb-10" cellpadding="2">
                        <tr>
                            <td width="75%" colspan="2"> C.1. RAZÓN SOCIAL: </td>
                            <td width="25%" > C.2. NIT </td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2"> <?= mb_strtoupper($empresa->nombre, 'UTF-8') ?></td>
                            <td class="text-center"> <?= $empresa->nit ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"> C.3. CORREO ELECTRÓNICO:</td>
                        </tr>
                        <tr>
                            <td colspan="3"> <?= $empresa->email ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"> C.4. APELLIDOS Y NOMBRES DEL REPRESENTANTE LEGAL:</td>
                            <td> C.5. N° DE IDENTIFICACIÓN</td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2"> <?= mb_strtoupper($empresa->nombre_representante, 'UTF-8') ?></td>
                            <td class="text-center"> <?= number_format($empresa->identificador_representante, 0, '', '.') ?></td>
                        </tr>
                        <tr>
                            <td> C.6. DIRECCIÓN: </td>
                            <td> C.7. MUNICIPIO</td>
                            <td> C.8. TELÉFONO</td>
                        </tr>
                        <tr>
                            <td> <?= $empresa->direccion ?></td>
                            <td class="text-center"> <?= $empresa->municipio ?></td>
                            <td class="text-center"> <?= $empresa->telefono ?></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td width="50%">
                                <h4 class="subtitulo">SECCIÓN D. LIQUIDACIÓN</h4>
                            </td>
                            <td width="50%" class="bold text-right">
                                APROXIME AL MULTIPLO DE MIL MAS CERCANO
                            </td>
                        </tr>
                    </table>

                    <table class="borde mb-10" cellpadding="2">
                        <thead>
                            <tr>
                                <td class="text-center" rowspan="2" width="3%"> R</td>
                                <td class="text-center" rowspan="2" width="25%"> D.1. CLASE</td>
                                <td class="text-center" colspan="3"> D.2. VALOR</td>
                                <td class="text-center" rowspan="2"> D.3. TARIFA</td>
                                <td class="text-center" rowspan="2" width="20%"> D.4. VALOR RECAUDO <?= mb_strtoupper($declaracion->estampilla, 'UTF-8') ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="20%"> VALOR BASE</td>
                                <td class="text-center"> VIGENCIA ACTUAL</td>
                                <td class="text-center"> VIGENCIA ANTERIOR</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                                foreach($detalles AS $detalle)
                                {
                                    ?>
                                    <tr>
                                        <td class="text-center"> <?= $detalle->renglon ?></td>
                                        <td> <?= mb_strtoupper($clasificaciones[$detalle->renglon], 'UTF-8') ?></td>
                                        <td class="text-center"> <?= $formatear_valor($detalle->base) ?></td>
                                        <td class="text-center"> <?= $detalle->vigencia_actual ?></td>
                                        <td class="text-center"> <?= $detalle->vigencia_anterior ?></td>
                                        <td class="text-center"> <?= number_format($detalle->porcentaje, 2, ',', '.')  ?>%</td>
                                        <td class="text-right"> <?= $formatear_valor($detalle->valor_estampilla) ?></td>
                                    </tr>
                                    <?
                                }
                            ?>
                            <tr>
                                <td class="text-center"> 5</td>
                                <td> TOTAL A FAVOR DEL DEPARTAMETO</td>
                                <td class="text-center"> <?= $formatear_valor($declaracion->total_base) ?></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right"> <?= $formatear_valor($declaracion->total_estampillas) ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 class="subtitulo">SECCIÓN E. PAGOS</h4>

                    <table class="borde mb-10" cellpadding="2">
                        <tbody>
                            <tr>
                                <td width="3%" class="text-center"> 6</td>
                                <td> TOTAL A FAVOR DEL DEPARTAMETO</td>
                                <td width="20%" class="text-right"> <?= $formatear_valor($declaracion->total_estampillas) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center"> -</td>
                                <td> SALDO A FAVOR PERÍODO ANTERIOR</td>
                                <td class="text-right"> <?= $formatear_valor($declaracion->saldo_periodo_anterior) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center"> +</td>
                                <td> VALOR SANCIONES</td>
                                <td class="text-right"> <?= $formatear_valor($declaracion->sanciones_pago) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center"> +</td>
                                <td> INTERESES DE MORA</td>
                                <td class="text-right"> <?= $formatear_valor($declaracion->intereses_mora) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td> TOTAL A CARGO POR RECAUDO ESTAMPILLA, SANCIONES E INTERESES</td>
                                <td class="text-right"> <?= $formatear_valor($declaracion->total_cargo) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td> SALDO A FAVOR</td>
                                <td class="text-right"> <?= $formatear_valor($declaracion->saldo_favor) ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table>
                        <tr>
                            <td width="50%">
                                <h4 class="subtitulo">SECCIÓN F. FIRMAS</h4>
                            </td>
                            <td width="50%">
                                <h4 class="subtitulo">SECCIÓN G. RELACIÓN DE ESTAMPILLA FIJADA</h4>
                            </td>
                        </tr>
                    </table>

                    <table class="mb-10" cellpadding="2" border="1">
                        <tr>
                            <td width="50%">
                                <h4 class="subtitulo">
                                    <table>
                                        <tbody>
                                            <?
                                                $indice = 0;
                                                foreach($firmas AS $firma)
                                                {
                                                    ?>
                                                    <tr>
                                                        <!-- Si no es la primera aplique el espaciado -->
                                                        <td colspan="2"
                                                            class="bold"
                                                            <?= $indice != 0 ? 'style="padding-top:20px"' : '' ?>
                                                        > F.<?= $indice+1 ?>. <?= mb_strtoupper($firma['label'], 'UTF-8') ?></td>
                                                    </tr>
                                                    <?
                                                        # Si es la primera
                                                        if($indice == 0)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td colspan="2"> Declaro que la información aquí consignada es correcta y ajustada a las disposiciones legales.</td>
                                                            </tr>
                                                            <?
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td class="bold v-bot"> FIRMA</td>
                                                        <td>
                                                            <img src="<?= $this->config->item('application_root') .'/uploads/temporal/'.$firma['code'].'.png' ?>" width="240" height="40" align="absmiddle">
                                                            <br>
                                                            ______________________________________
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bold"> NOMBRE</td>
                                                        <td class="bold"> <?= mb_strtoupper($firma['first_name'] .' '. $firma['last_name'], 'UTF-8') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bold"> C.C.</td>
                                                        <td class="bold"> <?= number_format($firma['id_usuario'], 0, '', '.') ?></td>
                                                    </tr>
                                                    <?
                                                    $indice++;
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </h4>
                            </td>
                            <td width="50%" class="v-top">
                                <table style="height:100%">
                                    <tr>
                                        <td style="height:30px" class="text-center bold">
                                            CANTIDAD ANULADAS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="1" cellpadding="3">
                                                <tr>
                                                    <td class="text-center"> N° Inicial</td>
                                                    <td class="text-center"> Al N°.</td>
                                                    <td class="text-center"> N° Inicial</td>
                                                    <td class="text-center"> Al N°.</td>
                                                    <td class="text-center"> Total Fijadas</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">&nbsp;</td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 50px 0" class="text-center">ESPACIO RESERVADO PARA EL BANCO</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

        <pagebreak>

        <table class="mb-10">
            <tr>
                <td width="30%">
                    <img src="<?= $this->config->item('application_root') .'/'. $declaracion->imagen_estampilla ?>" style="width:150px">
                </td>
                <td class="bold">
                    <?= mb_strtoupper($empresa->nombre) ?><br>
                    NIT: <?= $empresa->nit ?><br>
                    DECLARACIÓN <?= mb_strtoupper($declaracion->estampilla) ?>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="20%" class="v-bot bold">PERIODO:</td>
                <td width="9%" class="v-bot bold"><?= mb_strtoupper(strftime('%B', strtotime($declaracion->periodo)), 'UTF-8') ?></td>
                <td width="9%"></td>
                <td width="13%" class="text-center bg-verde bold"> Contrato - Factura IVA Incluido</td>
                <td width="13%" class="text-center bg-verde bold"> Base liquidación estampilla SIN IVA</td>
                <td width="13%" class="text-center bg-verde bold"> Estampilla</td>
                <td width="23%" colspan="3"></td>
            </tr>
        </table>

        <table class="borde">
            <thead>
                <tr>
                    <td width="20%" class="bg-amarillo bold">Proveedor</td>
                    <td width="9%" class="bg-amarillo bold">Nº doc.</td>
                    <td width="9%" class="bg-amarillo bold">Fe.contab.</td>
                    <td width="13%" class="text-right bg-amarillo bold">Importe en MD</td>
                    <td width="13%" class="text-right bg-amarillo bold">Impte.base Qst en MI</td>
                    <td width="13%" class="text-right bg-amarillo bold">Importe qst en MI</td>
                    <td width="8%" class="bg-gris bold">Contrato</td>
                    <td width="7%" class="bg-gris bold">Pago</td>
                    <td width="8%" class="bg-gris bold">Estampilla</td>
                </tr>
            </thead>
            <tbody>
                <?
                    foreach($pagos AS $pago)
                    {
                        ?>
                        <tr>
                            <td><?= $pago->nombre_contratista ?></td>
                            <td><?= $pago->nit_contratista ?></td>
                            <td><?= $pago->fecha ?></td>
                            <td class="text-right"><?= $formatear_valor($pago->valor_contrato) ?></td>
                            <td class="text-right"><?= $formatear_valor($pago->base_pago) ?></td>
                            <td class="text-right"><?= $formatear_valor($pago->pagado) ?></td>
                            <td><?= $pago->contrato ?></td>
                            <td><?= $pago->pago ?></td>
                            <td><?= $pago->factura ?></td>
                        </tr>
                        <?
                    }
                ?>
            </tbody>
        </table>

    </body>
</html>
<?
// die();
