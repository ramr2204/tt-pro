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
                    <td class="text-center" style="height: 19mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><img id="logo_gobernacion" src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima1.jpg" style="height: 15mm; width: 20mm;"></td>

                    <td class="text-center" id="leyenda_encabezado" colspan="3" style="height: 19mm; width: 68mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:9;"><br><br>GOBERNACIÓN DEL TOLIMA <br>Estampilla Departamental</td>

                    <td style="height: 19mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><br><br><img id="logo_gobernador" src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion_tolima2.jpg" style="height: 13mm; width: 25mm;" ></td>
                </tr>
 
                <tr>
                    <td class="text-center" colspan="2" style="height: 5mm; width: 57.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;                        
                        border-bottom: 0.5px solid black;">Número Estampilla</td>

                    <td class="text-center" colspan="3" style="height: 5mm; width: 62.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><?php echo $estampilla->impr_estampillaid; ?></td>
                </tr>

                <tr>
                    <td class="text-center" style="height: 9mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        vertical-align: text-middle;">Nombre Contratista y/o Empresa</td>

                    <td colspan="4" style="height: 9mm; width: 94mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><br><br>  <?php echo $estampilla->cont_nombre; ?></td>
                </tr>

                <tr>
                    <td class="text-center" style="height: 5mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">C.C. ó Nit.</td>

                    <td colspan="4" style="height: 5mm; width: 94mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">  <?php echo $estampilla->cont_nit; ?></td>
                </tr>

                <tr>
                    <td rowspan="5" class="text-center" style="height: 31mm; width: 26mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><br><br><?php if ($estampilla->fact_rutaimagen) { ?><img src="<?php echo $this->config->item('application_root').$estampilla->fact_rutaimagen; ?>" style="height: 26mm; width: 24mm;"><?php } ?></td>

                    <td style="height: 5mm; width: 31.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;"> Número Acto</td>

                    <td class="text-center" style="height: 5mm; width: 24mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><?= $estampilla->cntr_numero; ?></td>

                    <td style="height: 5mm; width: 13mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;
                        text-align: center;">Fecha</td>

                    <td class="text-center" style="height: 5mm; width: 25.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;"><?php echo $estampilla->cntr_vigencia; ?></td>
                </tr>

                <tr>
                    <td style="height: 5mm; width: 31.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;"> Valor Gravado</td>

                    <td colspan="2" style="height: 5mm; width: 24mm; border-top: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;">  $</td>

                    <td colspan="2" style="height: 5mm; width: 38.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        text-align: right;"><?= number_format($estampilla->liqu_valorsiniva,2, ',', '.'); ?></td>
                </tr>

                <tr>
                    <td style="height: 5mm; width: 31.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;"> Nombre Estampilla PRO</td>

                    <td style="height: 5mm; width: 24mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:6;
                        text-align: center;"><?php echo $estampilla->fact_nombre; ?></td>

                    <td style="height: 5mm; width: 13mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:6;
                        text-align: center;">Valor Estampilla</td>

                    <td style="height: 5mm; width: 25.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        text-align: right;">$  <?php echo number_format($estampilla->fact_valor, 2, ',', '.'); ?></td>
                </tr>

                <tr>
                    <td style="height: 8mm; width: 31.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;"><br><br> Fecha Pago</td>

                    <td style="height: 8mm; width: 24mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:6;
                        text-align: center;"><?php echo $estampilla->pago_fecha; ?></td>

                    <td rowspan="2" colspan="2" style="height: 16mm; width: 38.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        text-align: center;"><br><br>QR</td>
                </tr>

                <tr>
                    <td style="height: 8mm; width: 31.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:7;"><br><br> Fecha Impresión</td>

                    <td style="height: 8mm; width: 24mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:6;
                        text-align: center;"><?php echo date('Y-m-d',strtotime($estampilla->impr_fecha)); ?></td>                    
                </tr>


                <tr>
                    <td class="text-center" colspan="3" style="height: 15mm; width: 81.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        font-size:10;"><small> "Unidos por la grandeza del Tolima"<br>Edificio de la Gobernación del Tolima<br>Carrera 3 Calles 10 y 11, Piso 9<br>Código Postal 730006</small></td>

                    <td class="text-center" colspan="2" style="height: 15mm; width: 38.5mm; border-top: 0.5px solid black;
                        border-right: 0.5px solid black;
                        border-left: 0.5px solid black;
                        border-bottom: 0.5px solid black;
                        color: red;"><br><br>0000001</td>
                </tr>   
            </table>
        
 
