<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<!-- <tcpdf method="write1DBarcode" params="<?php echo $params; ?>" /> -->
<div style="width: 100%">
	<center>
		
	<img src="<?php echo $this->config->item('application_root'); ?>application/libraries/barcodegen/<?php echo $codebar ?>.png" width="300" height="40" align="center">
	</center>
</div>