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

<style type="text/css">
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
        padding-right: 1px;
    }
    #tablaq {
        margin-top: 3px;

    }

</style>

<br><br>

<?php
    for($x=0; $x<=1; $x++)
    {
        ?>
        <table class="table table-striped table-bordered " id="tablaq" border="1" cellpadding="1">
            <thead>
                <tr>
                    <th colspan="1" class="text-center small">
                        <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima1.jpg" height="60" width="70" >
                    </th>
                    <th colspan="2" class="text-center small">Gobernación del Putumayo <br> Secretaría de Hacienda Departamental <br> Oficina Rentas</th>
                    <th colspan="1" class="text-center small">
                        <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima2.png" height="50" width="90" >
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td colspan="1"><strong>Nombre del contratista</strong></td>
                    <td width="33%" colspan="1"><?php echo $result->liqu_nombrecontratista; ?></td>
                    <td width="17%" colspan="1"><strong>C.C. o NIT</strong></td>
                    <td colspan="1"><?php echo $result->liqu_nit; ?></td>
                </tr>

                <tr>
                    <td colspan="1"><strong>Dirección</strong></td>
                    <td colspan="1"><?php echo $contratista->cont_direccion; ?></td>
                    <td colspan="1"><strong>Telefono</strong></td>
                    <td colspan="1"><?php echo $contratista->cont_telefono; ?></td>
                </tr>

                <tr>
                    <td colspan="1"><strong>Correo electrónico</strong></td>
                    <td colspan="1"><?php echo $contratista->cont_email; ?></td>
                    <td colspan="1"><strong>Tipo de contratista</strong></td>
                    <td colspan="1"><?php echo $result->liqu_tipocontratista; ?></td>
                </tr>

                <tr>
                    <td colspan="1"><strong>Número de contrato</strong></td>
                    <td colspan="1"><?php echo $result->liqu_numero; ?> </td>
                    <td colspan="1"><strong>Vigencia</strong></td>
                    <td colspan="1"><?php echo $result->liqu_vigencia; ?></td>
                </tr>
                <tr>
                    <td colspan="1"><strong>Valor del contrato</strong></td>
                    <td colspan="1"><?php echo '$'.number_format($result->liqu_valorconiva, 2, ',', '.'); ?>
                    </td>
                    <td colspan="1"><strong>Valor sin IVA</strong></td>
                    <td colspan="1"><?php echo '$'.number_format($result->liqu_valorsiniva, 2, ',', '.'); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="1"><strong>Tipo de contrato</strong></td>
                    <td colspan="1"><?php echo $result->liqu_tipocontrato; ?></td>
                    <td colspan="1"><strong>Fecha generación</strong></td>
                    <td colspan="1"><?php echo $contrato->fecha_insercion ?></td>
                </tr>
                <tr>
                    <td colspan="1"><strong>Objeto</strong></td>
                    <td colspan="3"><?php echo $contrato->cntr_objeto; ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-striped table-bordered " id="tablaq" border="1" cellpadding="1">
            <thead>
                <tr>
                    <td width="25%" class="text-center"><strong>Estampilla</strong></td>
                    <td width="13%" class="text-center"><strong>Valor</strong></td>
                    <td width="12%" class="text-center"><strong>Factura</strong></td>
                    <td width="50%" class="text-center"><strong>Código de barras</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $totalValor = 0;

                    foreach($facturas as $factura)
                    {
                        ?>
                        <tr>
                            <td width="25%"><?php echo $factura->fact_nombre; ?></td>
                            <td width="13%" class="text-right"><?php echo '$'.number_format($factura->fact_valor, 2, ',', '.'); ?></td>
                            <td width="12%" class="text-right"><?php echo $factura->fact_id; ?></td>
                            <td width="50%" class="text-center">
                                <img src="<?php echo $this->config->item('application_root'); ?>application/libraries/barcodegen/<?php echo $factura->codigo_barras ?>.png" width="300" height="40">
                                <small><?php echo $factura->codigo_barras; ?></small>
                            </td>
                        </tr>
                        <?php

                        $totalValor += $factura->fact_valor;
                    }
                ?>
                <tr>
                    <td>Total</td>
                    <td class="text-right"><?php echo '$'.number_format($totalValor, 2, ',', '.'); ?></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <?php
            # No aparezca en el ultimo recorrido
            if($x != 1)
            {
                ?>
                <br>
                <img src="<?php echo $this->config->item('application_root'); ?>images/tijeras.png" width="25" height="25" style="vertical-align: sub;"><span>--------------------------------------------------------------------------------------------------------------------------------------------------</span>
                <br><br>
                <?php
            }
        ?>
        <?php
    }
?>