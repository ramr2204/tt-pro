<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<style>
    .letra-tama
    {
        font-size:10px;
        font-style: italic;
    }
    .encabezados
    {
        font-weight: bold;
    }
    .letra-tama
    {
        font-size:10px;
        font-style: italic;
    }
    .encabezados
    {
        font-weight: bold;
    }
    .border td {
      border: 1px solid black;
      padding: 2px;
    }
    .sin-borde {
      border: none !important;
    }
</style>

<table align="center" cellspacing="">
    <tbody>
    	<tr>
    		<td>
    			<img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion.jpg" width="110" height="70" align="center">
    		</td>
    		<td>
    			
		        <span style="font-size:9px">GOBERNACION DE BOYACÁ</span><br>
			    <span style="font-size:9px">Secretaría de hacienda departamental</span><br>
			    <span style="font-size:9px">Nit: 800094164-4</span><br>
			    <span style="font-size:9px">Liquidación de impuestos</span><br>
			    <span style="font-size:9px">Número liquidación: <?php echo $id ?></span><br>
			  
    		</td>
    		<td>
    			<img src="<?php echo $this->config->item('application_root'); ?>/images/logo_pdf.png" width="110" height="67" align="center" style="margin-top: 80px">
    		</td>
    	</tr>
	</tbody>
</table>
<table cellpadding="2" cellspacing="2">
    <tr>
        <td class="letra-tama encabezados">CÓDIGO</td>
        <td class="letra-tama"><?php echo $consultarParametros->numero_factura?></td>
        <td class="letra-tama encabezados">FECHA</td>
        <td class="letra-tama"><?php echo $consultarParametros->fecha_creacion?></td>
    </tr>
    <tr>
        <td class="letra-tama encabezados">DOCUMENTO</td>
        <td class="letra-tama"><?php echo $consultarParametros->nombre_documento?></td>
        <td class="letra-tama encabezados">NUMERO DOCUMENTO</td>
        <td class="letra-tama"><?php echo $consultarParametros->ndocumento?></td>
    </tr>
    <tr> 
        <td class="letra-tama encabezados">DIRECCION</td>
        <td class="letra-tama"><?php echo $consultarParametros->direccion?></td>
        <td class="letra-tama encabezados">TELEFONO</td>
        <td class="letra-tama"><?php echo $consultarParametros->telefono1?></td>
    </tr>
    <tr>
        <td class="letra-tama encabezados">TRÁMITE</td>
        <td class="letra-tama"><?php echo $consultarParametros->nombre_tramite?></td>
        <td class="letra-tama encabezados">NOMBRE</td>
        <td class="letra-tama"><?php echo $consultarParametros->primer_nombre?> <?php echo  $consultarParametros->segundo_nombre?> <?php echo  $consultarParametros->primer_apellido ?> <?php echo  $consultarParametros->segundo_apellido ?></td>
    </tr>
</table>
<br>
<br>
<table class="border" style="border-collapse: collapse;">
    <tr>
        <td class="letra-tama encabezados">CODIGO CONCEPTO</td>
        <td class="letra-tama encabezados">TIPO CONCEPTO</td>
        <td class="letra-tama encabezados">VALOR CONCEPTO</td>
    </tr>

    <?php foreach ($conceptos as $concepto) { ?>
    <tr>
        <td class="letra-tama"><?php echo $concepto->id ?></td>
        <td class="letra-tama"><?php echo $concepto->nombre_concepto ?></td>
        <td class="letra-tama"><?php echo $concepto->valor_concepto ?></td>
    </tr>
    <?php  } ?>
    <tr style="outline: thin solid">
	    <td style="border:none" class="letra-tama sin-borde"></td>
	    <td style="border:none" class="letra-tama sin-borde encabezados">TOTAL</td>
	    <td style="border:none" class="letra-tama sin-borde"><?php echo $sumConceptos ?></td>
	</tr>
</table>
<br>
<div style="font-size: 8px"><?php echo $numeroLetras ?> PESOS MDA, CTE.</div>
<br>
<table align="center" cellspacing="">
    <tr>
        <td>
        	<img src="<?php echo $this->config->item('application_root'); ?>application/libraries/barcodegen/<?php echo $codebar ?>.png" width="300" height="40" style="display:block;margin:auto;">
        	<p style="text-align: center;font-size: 8px"><?php echo $codebar?></p>
        </td>
    </tr>
</table>
<br>
<div style="font-size:9px">--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</div>
<br>
<table align="center" cellspacing="">
    <tbody>
    	<tr>
    		<td>
    			<img src="<?php echo $this->config->item('application_root'); ?>/images/gobernacion.jpg" width="110" height="70" align="center">
    		</td>
    		<td>
    			
		        <span style="font-size:9px">GOBERNACION DE BOYACÁ</span><br>
			    <span style="font-size:9px">Secretaría de hacienda departamental</span><br>
			    <span style="font-size:9px">Nit: 800094164-4</span><br>
			    <span style="font-size:9px">Liquidación de impuestos</span><br>
			    <span style="font-size:9px">Número liquidación: <?php echo $id ?></span><br>
			  
    		</td>
    		<td>
    			<img src="<?php echo $this->config->item('application_root'); ?>/images/logo_pdf.png" width="110" height="67" align="center" style="margin-top: 80px">
    		</td>
    	</tr>
	</tbody>
</table>
<table cellpadding="2" cellspacing="2">
    <tr>
        <td class="letra-tama encabezados">CÓDIGO</td>
        <td class="letra-tama"><?php echo $consultarParametros->numero_factura?></td>
        <td class="letra-tama encabezados">FECHA</td>
        <td class="letra-tama"><?php echo $consultarParametros->fecha_creacion?></td>
    </tr>
    <tr>
        <td class="letra-tama encabezados">DOCUMENTO</td>
        <td class="letra-tama"><?php echo $consultarParametros->nombre_documento?></td>
        <td class="letra-tama encabezados">NUMERO DOCUMENTO</td>
        <td class="letra-tama"><?php echo $consultarParametros->ndocumento?></td>
    </tr>
    <tr> 
        <td class="letra-tama encabezados">DIRECCION</td>
        <td class="letra-tama"><?php echo $consultarParametros->direccion?></td>
        <td class="letra-tama encabezados">TELEFONO</td>
        <td class="letra-tama"><?php echo $consultarParametros->telefono1?></td>
    </tr>
    <tr>
        <td class="letra-tama encabezados">TRÁMITE</td>
        <td class="letra-tama"><?php echo $consultarParametros->nombre_tramite?></td>
        <td class="letra-tama encabezados">NOMBRE</td>
        <td class="letra-tama"><?php echo $consultarParametros->primer_nombre?> <?php echo  $consultarParametros->segundo_nombre?> <?php echo  $consultarParametros->primer_apellido ?> <?php echo  $consultarParametros->segundo_apellido ?></td>
    </tr>
</table>
<br>
<br>
<table class="border" style="border-collapse: collapse;">
    <tr>
        <td class="letra-tama encabezados">CODIGO CONCEPTO</td>
        <td class="letra-tama encabezados">TIPO CONCEPTO</td>
        <td class="letra-tama encabezados">VALOR CONCEPTO</td>
    </tr>

    <?php foreach ($conceptos as $concepto) { ?>
    <tr>
        <td class="letra-tama"><?php echo $concepto->id ?></td>
        <td class="letra-tama"><?php echo $concepto->nombre_concepto ?></td>
        <td class="letra-tama"><?php echo $concepto->valor_concepto ?></td>
    </tr>
    <?php  } ?>
    <tr style="outline: thin solid">
	    <td style="border:none" class="letra-tama sin-borde"></td>
	    <td style="border:none" class="letra-tama sin-borde encabezados">TOTAL</td>
	    <td style="border:none" class="letra-tama sin-borde"><?php echo $sumConceptos ?></td>
	</tr>
</table>
<br>
<div style="font-size: 8px"><?php echo $numeroLetras ?> PESOS MDA, CTE.</div>
<br>
<table align="center" cellspacing="">
    <tr>
        <td>
        	<img src="<?php echo $this->config->item('application_root'); ?>application/libraries/barcodegen/<?php echo $codebar ?>.png" width="300" height="40" style="display:block;margin:auto;">
        	<p style="text-align: center;font-size: 8px"><?php echo $codebar?></p>
        </td>
    </tr>
</table>