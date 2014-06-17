<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->IDTASA) ?>
<h2>Editar Tasa de Acuerdo</h2>
<p>
<?php
 echo form_label('Concepto<span class="required">*</span>', 'concepto');                               
      foreach($conceptos as $row) {
          $optionc[$row->IDCONCEPTO] = $row->NOMBRECONCEPTO;
       }
  echo form_dropdown('concepto', $optionc,$result->IDCONCEPTO,'id="concepto" class="chosen" disabled="disabled"');
 
   echo form_error('concepto','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Valor tasa<span class="required">*</span>', 'valortasa');
   $data = array(
              'name'        => 'valortasa',
              'id'          => 'valortasa',
              'value'       => $result->VALORTASA,
              'maxlength'   => '10',
              'class'       =>  'input-mini'
            );

   echo form_input($data);
   echo '<span class="add-on">%</span>';
   echo form_error('valortasa','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Estado<span class="required">*</span>', 'estado_id');                               
      foreach($estados as $row) {
          $options[$row->IDESTADO] = $row->NOMBREESTADO;
       }
  echo form_dropdown('estado_id', $options,$result->IDESTADO,'id="estado" class="chosen"');
 
   echo form_error('estado_id','<div>','</div>');
?>
</p>
<p>     
        
        <?php  echo anchor('tasaspago', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
        <?php 
        $data = array(
               'name' => 'button',
               'id' => 'submit-button',
               'value' => 'Guardar',
               'type' => 'submit',
               'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
               'class' => 'btn btn-success'
               );

        echo form_button($data);    
        ?>
        <?php  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasaspago/delete'))
               {echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"');
               } ?>
        
</p>

<?php echo form_close(); ?>

  <script type="text/javascript">
  //style selects
    var config = {
      '.chosen'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script>

<script>
    $(function() {
        // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $("#borrar").click(function(evento){    
        evento.preventDefault();
         var link = $(this).attr('href');
        $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:180,
            modal: true,
            buttons: {
                "Confirmar": function() {
                    location.href='<?php echo base_url()."index.php/tasaspago/delete/".$result->IDTASA; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar la tasa de pago?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar la tasa de pago "<?php echo $result->VALORTASA; ?>"?</p>
</div>