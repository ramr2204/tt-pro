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
 .center-vertical {
  padding-top: 30px;
 }

 .tablaestampillas td,
 .tablaestampillas tr {
  border-style: solid black 1px;
}

</style>

            <table class="tablaestampillas">
 
                <tr>
                    <td class="col-1 row-1">
                        <img id="logo_gobernacion" src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg">
                    </td>
                    <td class="text-center row-1" id="leyenda_encabezado" colspan="3" >
                        GOBERNACIÓN DEL TOLIMA <br> 
                        Departamento Administrativo de Asuntos<br> 
                        Jurídicos <br> 
                        Dirección de Contratación
                    </td>
                    <td class="col-5 row-1">
                        <img id="logo_gobernador" src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" >
                    </td>
                </tr>
 
                <tr>
                    <td class="row-2 text-center" colspan="2">Número Estampilla</td>
                    <td class="row-2 text-center" colspan="3"><?php echo $estampilla->cntr_numero; ?></td>
                </tr>

                <tr>
                    <td class="col-1 row-3">Nombre Contratista</td>
                    <td class="row-3" colspan="4"></td>
                </tr>

                <tr>
                    <td class="col-1 row-4">C.C. ó Nit.</td>
                    <td class="row-4" colspan="4"><?php echo $estampilla->cont_nit; ?></td>
                </tr>

                <tr>
                    <td class="col-1 row-5" rowspan="4">
                        <?php if ($estampilla->fact_rutaimagen) { ?>
                            <img src="<?php echo base_url().$estampilla->fact_rutaimagen; ?>" >
                        <?php } ?>
                    </td>
                    <td class="col-2 row-5" style="font-size:7;" >Número Contrato</td>
                    <td class="col-3 row-5 text-center" >Numero X</td>
                    <td class="col-4 row-5" style="font-size:7;">Vigencia</td>
                    <td class="col-5 row-5 text-center" ><?php echo $estampilla->cntr_vigencia; ?></td>
                </tr>

                <tr>
                    <td class="col-2 row-6" style="font-size:7;">Valor contrato Sin IVA</td>
                    <td class="row-6" colspan="3" >$</td>
                </tr>

                <tr>
                    <td class="col-2 row-7" style="font-size:7;">Nombre Estampilla PRO</td>
                    <td class="col-3 row-7" style="font-size:7;"><?php echo $estampilla->cont_nombre; ?></td>
                    <td class="col-4 row-7" style="font-size:7;">Valor</td>
                    <td class="col-5 row-7" style="font-size:7;"><?php echo '$'.number_format($estampilla->fact_valor, 2, ',', '.'); ?></td>
                </tr>

                <tr>
                    <td class="row-8" colspan="4"><tcpdf method="write1DBarcode" params="<?php echo $params; ?>"/></td>
                </tr>

                <tr>
                    <td class="col-2 row-9 text-center" colspan="3" >
                        <small> "Unidos por la grandeza del Tolima"<br>
                        Edificio de la Gobernación del Tolima, Carrera 3 Calles 10 y 11, Piso 9<br>
                        Teléfonos (8) 2611111 Exts. 209 - 305<br>
                        dcontratos@outlook.com</small>
                    </td>
                    <td class="row-9 text-center" colspan="2">Numero X</td>
                </tr>   
            </table>
 
