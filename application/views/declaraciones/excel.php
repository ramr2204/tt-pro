<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   @author           David Mahecha
*   @version          2015-11-17
*
*/

?>
<p><h1 style="text-align: center;">Sistema Estampillas-Pro</h1></p>
<br>
<table border="1" style="text-align:center;">
    <thead>
        <tr>
            <th style="text-align:center; width:26mm;"><strong>Proveedor</strong></th>
            <th style="text-align:center; width:20mm;"><strong>Nº doc.</strong></th>
            <th style="text-align:center; width:20mm;"><strong>Fe.contab.</strong></th>
            <th style="text-align:center; width:40mm;"><strong>Importe en MD</strong></th>
            <th style="text-align:center; width:40mm;"><strong>Impte.base Qst en MI</strong></th>
            <th style="text-align:center; width:40mm;"><strong>Importe qst en MI</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Contrato</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Pago</strong></th>
            <th style="text-align:center; width:30mm;"><strong>Estampilla</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($detalles as $detalle) 
            {
                ?>
                <tr>
                    <td style="text-align:center; width:26mm;"><?= $detalle->nombre_contratista ?></td>
                    <td style="text-align:center; width:20mm;"><?= $detalle->nit_contratista ?></td>
                    <td style="text-align:center; width:20mm;"><?= $detalle->fecha ?></td>
                    <td style="text-align:center; width:40mm;"><?= $formatear_valor($detalle->valor_contrato) ?></td>
                    <td style="text-align:center; width:40mm;"><?= $formatear_valor($detalle->base_pago) ?></td>
                    <td style="text-align:center; width:40mm;"><?= $formatear_valor($detalle->pagado) ?></td>
                    <td style="text-align:center; width:30mm;"><?= $detalle->contrato ?></td>
                    <td style="text-align:center; width:30mm;"><?= $detalle->pago ?></td>
                    <td style="text-align:center; width:30mm;"><?= $detalle->factura ?></td>
                </tr>
                <?
            }
        ?>
    </tbody>
</table>