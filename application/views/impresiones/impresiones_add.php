<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/impresiones/impresiones_add.php
*   Descripcion:       permite crear una nueva aplicación
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <codigopapelndariovinam@gmail.com>
*   @version           2014-05-12
*
*/

?>
<div class="row clearfix">
            <div class="col-md-12 column">
                  <div class="row clearfix">
                        <div class="col-md-4 column">
                        </div>
                        <div class="col-md-4 column">
                           <div class="panel panel-default">
                            <div class="panel-heading"><h1>Crear un nuevo régimen</h1></div>
                             <div class="panel-body">
                              <?php echo form_open(current_url()); ?>
                                    <div class="form-group">
                                           <label for="codigopapel">Consecutivo del papel</label>
                                           <input class="form-control" id="codigopapel" type="number" name="codigopapel" value="<?php echo set_value('codigopapel'); ?>" step="" min="1" />
                                           <?php echo form_error('codigopapel','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="tipoanulacion">Causa de anulación</label>
                                           <input class="form-control typeahead" id="tipoanulacion" type="text" name="tipoanulacion" value="<?php echo set_value('tipoanulacion'); ?>" required="required" maxlength="200" />
                                           <?php echo form_error('tipoanulacion','<span class="text-danger">','</span>'); ?>
                                    </div>

                                    <div class="form-group">
                                           <label for="observaciones">Observaciones</label>
                                           <textarea class="form-control" id="observaciones" name="observaciones" maxlength="1000"><?php echo set_value('observaciones'); ?></textarea>
                                           <?php echo form_error('observaciones','<span class="text-danger">','</span>'); ?>
                                    </div>
                                    
                                    <div class="pull-right">
                                     <?php  echo anchor('impresiones', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                                    </div>
                                <?php echo form_close();?>
                              
                              </div>
                             </div>
       
                        </div>
                        <div class="col-md-4 column">
                        </div>
                  </div> 
            </div>
      </div>



<script type="text/javascript">
 $(document).ready(function() {

  var substringMatcher = function(strs) {
   return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        // the typeahead jQuery plugin expects suggestions to a
        // JavaScript object, refer to typeahead docs for more info
        matches.push({ value: str });
      }
    });

    cb(matches);
  };
};

var states = <?php echo $tiposanulaciones?>;

$('.typeahead').typeahead({
  hint: true,
  highlight: true,
  minLength: 0
},
{
  name: 'states',
  displayKey: 'value',
  source: substringMatcher(states)
});



});





</script>