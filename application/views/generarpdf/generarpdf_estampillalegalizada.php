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
 
</style>

            <table class="tablaestampillas">
 
                <tr>
                    <td class="col-1 row-1" style="height: 19mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        <img id="logo_gobernacion" src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" style="height: 18mm; width: 25mm;">
                    </td>

                    <td class="text-center row-1" id="leyenda_encabezado" colspan="3" style="height: 19mm; width: 68mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        GOBERNACIÓN DEL TOLIMA <br> 
                        Departamento Administrativo de Asuntos<br> 
                        Jurídicos <br> 
                        Dirección de Contratación
                    </td>

                    <td class="col-5 row-1" style="height: 19mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        <img id="logo_gobernador" src="<?php echo base_url() ?>images/gobernacion_tolima2.jpg" style="height: 13mm; width: 25mm;" >
                    </td>
                </tr>
 
                <tr>
                    <td class="row-2 text-center" colspan="2" style="height: 5mm; width: 57.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        Número Estampilla
                    </td>

                    <td class="row-2 text-center" colspan="3" style="height: 5mm; width: 62.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        <?php echo $estampilla->cntr_numero; ?>
                    </td>
                </tr>

                <tr>
                    <td class="col-1 row-3" style="height: 9mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                    Nombre Contratista
                    </td>

                    <td class="row-3" colspan="4" style="height: 9mm; width: 94mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">
                        

                    </td>
                </tr>

                <tr>
                    <td class="col-1 row-4" style="height: 5mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        C.C. ó Nit.
                    </td>

                    <td class="row-4" colspan="4" style="height: 5mm; width: 94mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        <?php echo $estampilla->cont_nit; ?>
                    </td>
                </tr>

                <tr>
                    <td class="col-1 row-5" rowspan="4" style="height: 31mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        <?php if ($estampilla->fact_rutaimagen) { ?>
                            <img src="<?php echo base_url().$estampilla->fact_rutaimagen; ?>" >
                        <?php } ?>
                    </td>

                    <td class="col-2 row-5" style="height: 5mm; width: 31.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;">

                        Número Contrato
                    </td>

                    <td class="col-3 row-5 text-center" style="height: 5mm; width: 24mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                    Numero X
                    </td>

                    <td class="col-4 row-5" style="height: 5mm; width: 13mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;">

                        Vigencia
                    </td>

                    <td class="col-5 row-5 text-center" style="height: 5mm; width: 25.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        <?php echo $estampilla->cntr_vigencia; ?>
                    </td>
                </tr>

                <tr>
                    <td class="col-2 row-6" style="height: 5mm; width: 31.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;">

                        Valor contrato Sin IVA
                    </td>

                    <td class="row-6" colspan="3" style="height: 5mm; width: 62.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">

                        $
                    </td>
                </tr>

                <tr>
                    <td class="col-2 row-7" style="height: 5mm; width: 31.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;">

                        Nombre Estampilla PRO
                    </td>

                    <td class="col-3 row-7" style="height: 5mm; width: 24mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;">

                         <?php echo $estampilla->cont_nombre; ?>
                    </td>

                    <td class="col-4 row-7" style="height: 5mm; width: 13mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;">

                        Valor
                    </td>

                    <td class="col-5 row-7" style="height: 5mm; width: 25.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;">

                        <?php echo '$'.number_format($estampilla->fact_valor, 2, ',', '.'); ?>
                    </td>
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
        
 
