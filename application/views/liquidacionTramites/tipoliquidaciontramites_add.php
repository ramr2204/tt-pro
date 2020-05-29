<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

/**
*   Nombre:            admin template
*   Ruta:              /application/views/contratistas/contratistas_add.php
*   Descripcion:       permite crear un nuevo contratista
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
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
                    <div class="panel-heading"><h1>Crear un nuevo tipo trámite</h1></div>
                    <div class="panel-body">
                        <?php echo form_open(current_url()); ?>

                        <div class="form-group">
                            <label for="valor">Vigencia</label>
                            <input class="form-control" id="" type="" name=""  value="<?php echo date('Y');?>" disabled />
                        </div>

                        <div class="form-group">
                            <label for="tramite_existe">Trámite Existente</label>
                            <select class="form-control" id="tramite_existe" name="tramite_existe" required="required" >
                                <option value="0">Seleccione Nombre</option>
                                <?php  foreach($result['tramite_existe'] as $row) { ?>
                                    <option value="<?php echo $row->id; ?>"><?php echo  $row->nombre; ?></option>
                                <?php   } ?>
                            </select>
                            <?php echo form_error('tramite_existe','<span class="text-danger">','</span>'); ?>
                        </div>


                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input class="form-control" id="nombre_tramite" type="nombre" name="nombre" required="required" maxlength="128" />
                            <?php echo form_error('nombre','<span class="text-danger">','</span>'); ?>
                        </div>


                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input class="form-control" id="valor" type="valor" name="valor"  maxlength="15" />
                            <?php echo form_error('valor','<span class="text-danger">','</span>'); ?>
                        </div>
                        
                        <div class="pull-right">
                            <?php  echo anchor('tipoLiquidacionTramite/manage', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
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
    //style selects
    var config = {
        '#tipocontratistaid'  : {disable_search_threshold: 10},
        '#municipioid'  : {disable_search_threshold: 10},
        '#regimenid'  : {disable_search_threshold: 10},
        '#tramite_existe'  : {disable_search_threshold: 10}
    }

    for (var selector in config) 
    {
        $(selector).chosen(config[selector]);
    }

</script>
