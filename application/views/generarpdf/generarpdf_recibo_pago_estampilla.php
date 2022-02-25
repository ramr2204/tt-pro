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

<table class="table table-striped table-bordered " id="tablaq" border="1" cellpadding="1">
    <thead>
        <tr>
            <th colspan="1" class="text-center small">
                <img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion.jpg" height="60" width="70" >
            </th>
            <th colspan="2" class="text-center small">Gobernación de Boyacá <br> Secretaría de Hacienda Departamental <br> Oficina Rentas</th>
            <th colspan="1" class="text-center small">
                <img src="<?php echo $this->config->item('application_root'); ?>/images/logo.png" height="50" width="90" >
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
            <td colspan="1"><strong>Tipo de contrato</strong></td>
            <td colspan="1"><?php echo $result->liqu_tipocontrato; ?></td>
            <td colspan="1"><strong>Fecha</strong></td>
            <td colspan="1"><?php echo $pagos->fecha ?></td>
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
            <td width="13%" class="text-center"><strong>Porcentaje</strong></td>
            <td width="12%" class="text-center"><strong>Valor de cuota</strong></td>
            <td width="50%" class="text-center"><strong>Código QR</strong></td>
        </tr>
    </thead>
    <tbody>
        <?
            foreach($pagos AS $pago)
            {
                $estilos_qr[0] = base_url().'generarpdf/generar_estampilla_retencion?id='.urlencode($this->encrypt->encode($pago->id, Equivalencias::generadorHash()));
                $params = TCPDF_STATIC::serializeTCPDFtagParameters($estilos_qr);

                ?>
                <tr>
                    <td width="25%" class="text-center"><?= $pago->fact_nombre ?></td>
                    <td width="13%" class="text-center"><?= number_format($pago->porcentaje, 2, ',', '.') ?>%</td>
                    <td width="12%" class="text-center">$<?= number_format($pago->valor_cuota, 2, ',', '.') ?></td>
                    <td width="50%" class="text-center">
                        <tcpdf method="write2DBarcode" params="<?= $params; ?>" />
                    </td>
                </tr>
                <?
            }
        ?>
    </tbody>
</table>