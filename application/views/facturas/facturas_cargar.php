<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
        <?php     
echo $message;
       echo form_open_multipart(current_url()); 
       ?>
        <?php //echo $custom_error; ?>
        <h2>Cargar archivo</h2>
   
        <p>
        <?php
         echo form_label('Cargar archivo (Archivo TXT) <span class="required">*</span>', 'archivo');
        ?>
         <input type="file" name="archivo" id="archivo" size="20" />
        </p>

     

        <p>
                <?php  echo anchor('cargarpagosplantillaunica', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Cargar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Cargar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
            <?php echo form_error('archivo','<div>','</div>'); ?>    
        </p>

        <?php echo form_close(); ?>
</div>
