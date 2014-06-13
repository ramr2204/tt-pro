
<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


    <div class="container" id="container">

 <div class="row"> 
 <div class="col-sm-12">    
<h1>Compra y vende de todo con Bitcoins</h1>
<br>
<h2>¿Qué es Bitbahia?</h2>
<p>
Bitbahia es el primer mercado online de habla hispana donde cualquier persona puede comprar y vender bienes y servicios tanto físicos como digitales utilizando la moneda virtual Bitcoin, estará disponible para todos los paises de Latinoamérica y España.

Bitbahia se encuentra en desarrollo, incluirá un procesador de pagos seguro y un sistema escrow que garantiza la seguridad de nuestros clientes, también dispondrá de un sistema de seguimiento de envíos con garantía del producto. Bitbahia contará con una interfaz sencilla, completa y fácil de usar para el usuario.
</p>
<h2>¿Cuándo estará disponible?</h2>
<p>
Todavía nos encontramos en estapa de desarrollo. Estamos trabajando al máximo para que Bitbahia funcione cuanto antes. Para estar más informado puedes suscribirte a nuestro Newsletter y te avisaremos hasta su lanzamiento.
</p>

      </div>   
      </div>
 <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-5"><?php echo $form_message; ?></div>
  <div class="col-sm-6"></div>
 </div> 
<?php echo form_open(current_url()); ?>
<div class="row formulario">
  <div class="col-sm-5">
    <div class="form-group">
  	<?php
    $data = array(
              'name'        => 'email',
              'id'          => 'email',
              'value'       => set_value('email'),
              'maxlength'   => '128',
              'required'    => 'required',
              'class'       => 'form-control',
              'placeholder' => 'Ingrese su email'
            );
//echo '<span class="glyphicon glyphicon-ok form-control-feedback"></span>';
    echo form_email($data);
    
    echo form_error('email','<div>','</div>');
    ?>
    </div>
  </div>
  <div class="col-sm-2">
  	<?php 
        $data = array(
               'name' => 'button',
               'id' => 'submit-button',
               'value' => 'Notificarme',
               'type' => 'submit',
               'content' => 'Notificarme',
               'class' => 'btn btn-info'
               );

        echo form_button($data);    
        ?>

  </div>
  <div class="col-sm-5"><img src="/images/welovebitcoin_presentacion.png" alt="bitbahia.com" class="img-responsive pull-right"></div>
</div>
<?php echo form_close(); ?>
    </div> <!-- /container -->


<div class="footer-color"> <div class="dialog suscribirse"> <div class="container"> <?php echo $form_message; ?> <?php echo form_open(current_url()); ?> <div class="row"> <div class="form-inline"> <div class="form-group" style="width: 500px;"> <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span> <input type="email" class="form-control input-lg" placeholder="Email" required="required" value="" name="email" id="email" /> </div> </div> <button class="btn btn-lg btn-primary"> Suscribirse </button> </div> <?php echo form_close(); ?> </div> </div> </div>


<div class="graybox">
<div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-7"><img src="/images/paises_presentacion.png" alt="bitbahia.com" class="img-responsive"></div>
  <div class="col-sm-3">
  <ul class="social">
  <li><?php echo anchor('http://www.facebook.com/bit.bahia', '<i class="fa fa-facebook fa-2x"></i>', array('title' => '','class' => '')); ?></li>
  <li><?php echo anchor('http://twitter.com/Bitcoinbahia', '<i class="fa fa-twitter fa-2x"></i>', array('title' => '','class' => '')); ?></li>
  <li><?php echo anchor('https://bitcointalk.org/index.php?topic=478202.0', '<i class="fa fa-comments fa-2x"></i>', array('title' => '','class' => '')); ?></li>
  </ul>
  </div>
  
  <div class="col-sm-1"></div>
</div>
</div>


