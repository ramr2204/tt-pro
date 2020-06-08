<?php

define ('K_PATH_IMAGES', '/images/');
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        $html = '<table align="center" cellspacing="">
                    <tr>
                        <td style="font-size:10px">GOBERNACION DEL PUTUMAYO</td>
                    </tr>
                    <tr>
                        <td style="font-size:10px">Secretaría de hacienda departamental</td>
                    </tr>
                    <tr>
                        <td style="font-size:10px">Nit: 800094164-4</td>
                    </tr>
                    <tr>
                        <td style="font-size:10px">Liquidación de impuestos</td>
                    </tr>
                    <tr>
                        <td style="font-size:10px">Número liquidación: '.$_GET['id'].'</td>
                    </tr>
                </table>';

          $this->writeHTMLCell($w = 0, $h = 50, $x = '', $y = '7', $html, $border = 0, $ln = 1, $fill = 0, $reseth = false, $align = 'C', $autopadding = true);
          $this->setPageMark();
        
        // Logo
        $image_escudo = K_PATH_IMAGES.'gobernacion_tolima1.jpg';
        $image_refran = K_PATH_IMAGES.'gobernacion_tolima2.png';

        $this->Image($image_refran, 160, 12, 36, 15, 'png', '', 'T', true, 600, '', false, false, 0, false, false, false);
        $this->Image($image_escudo, 13, 6, 35, 25, 'png', '', 'T', true, 100, '', false, false, 0, false, false, false);
    }

    // Page footer
    public function Footer() {
        
    }
}