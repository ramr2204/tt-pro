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
<?php 
    $a= "[";
    foreach ($vigencias as $key => $value) {
      $a.='"'.$value.'", ';
    }
    $a=substr($a, 0, -2);
    $a.= "]";
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

var oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/liquidaciones/liquidaciones_dataTable",
"sServerMethod": "POST",
"iDisplayLength": 5,
"aoColumns": [ 
                      { "sClass": "center","bVisible": false}, /*id 0*/
                      { "sClass": "center","sWidth": "6%" }, 
                      { "sClass": "center" }, 
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "item" },  
                      { "sClass": "money"},
                      { "sClass": "item"},
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
            ],   
"fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
  if(aData[5] != null)
  {
      $("td:eq(4)", nRow).html('<div class="small">' + aData[5].substr( 0, 130 )+ '...</div>');
  }else
      {
           $("td:eq(4)", nRow).html('<div class="small">NO REGISTRA...</div>');     
      }

  
   
  var number= accounting.formatMoney(aData[6], "$", 2, ".", ","); // €4.999,99
  $("td:eq(5)", nRow).html('<div class="">' + number + '</div>');
  if (aData[7]=='Legalizado') {
   $("td:eq(7)", nRow).html('<a href="#" class="btn btn-success btn-xs terminar" title="Cambiar estado" id="'+aData[0]+'"><i class="fa fa-tags"></i></a>');
  }
   if (aData[7]=='Liquidado') {
   $("td:eq(7)", nRow).html('<a href="#" class="btn btn-primary btn-xs pagar" title="Cambiar estado" id="'+aData[0]+'"><i class="fa fa-money"></i></a>');
  }
  if (aData[7]==null) { 
   $("td:eq(6)", nRow).html('<div>Sin Liquidar</div>'); 
   $("td:eq(7)", nRow).html('<a href="#" class="btn btn-danger btn-xs liquidar" title="Liquidar" id="'+aData[0]+'"><i class="fa fa-file-excel-o"></i></a>');
  }

 },
  "fnDrawCallback": function( oSettings ) {
      $(".liquidar").on('click', function(event) {
           event.preventDefault();
           var ID = $(this).attr("id");
            $("#idcontrato").val(ID);
             $('.liquida').load('<?php echo base_url(); ?>index.php/liquidaciones/liquidarcontrato/'+ID,function(result){
              $('#myModal').modal({show:true});

              //Eventos liquidar contratos-temporal
              $('.calcular').blur(actualizarTotal); 
              function actualizarTotal(e)
              { 
                  var total = 0;

                  $('.calcular').map(function(){      

                      if($(this).val()!='')
                      {
                          total += parseInt($(this).val());   
                      }else
                          {
                              total += 0;  
                          }                
                      
                  });

                  $('#valortotal').val(total);
                  
              }

             });
         });
       $(".pagar").on('click', function(event) {
           event.preventDefault();
           var ID = $(this).attr("id");
            $("#idcontrato").val(ID);
             $('.paga').load('<?php echo base_url(); ?>index.php/liquidaciones/verrecibos/'+ID,function(result){
              $('#myModal2').modal({show:true});
             });
         });
      $(".terminar").on('click', function(event) {
           event.preventDefault();
           var ID = $(this).attr("id");
            $("#idcontrato").val(ID);
             $('.termina').load('<?php echo base_url(); ?>index.php/liquidaciones/vercontratolegalizado/'+ID,function(result){
              $('#myModal3').modal({show:true});                                 

              //Solicita el ultimo rotulo al cargar la modal
              solicitarUltimoRotulo();    

              $('.confirmar_impresion').click(function(event) {
                  var objEvento = $(this);
                  event.preventDefault();

                  //Solicita el ultimo rotulo para la impresion
                  solicitarUltimoRotulo();
                  var bandEnviarImpresion = true;

                  var siguienteEstampilla = $('#siguienteEstampilla').val();
                  if(!confirm('.::SIGUIENTE ESTAMPIILLA A IMPRIMIRSE => No. '+siguienteEstampilla+'::.\n\n'
                        +'Esta seguro de generar la impresión?'
                        +' Recuerde que será modificado el consecutivo de la papeleria asignada a usted!'))
                  {
                      bandEnviarImpresion = false;
                  }

                  for(var i =1; i <= 3; i++)
                  {
                      var inputTeclado = prompt(".::POR FAVOR CONFIRME EL NÚMERO DE ROTULO FÍSICO A IMPRIMIR::.",Math.floor(Math.random()*10000));
                      if(inputTeclado != siguienteEstampilla)
                      {
                          alert('.::EL NÚMERO DE ROTULO FÍSICO ESPECIFICADO POR USTED NO CORRESPONDE CON EL ROTULO FÍSICO SIGUIENTE EN EL SISTEMA::.');
                          bandEnviarImpresion = false;
                          break;
                      }
                  }

                  if(bandEnviarImpresion)
                  {
                      objEvento.attr('disabled','disabled');
                      window.open(objEvento.attr('href'),'_blank');
                  }
              });

              /**
              * Funcion de Apoyo que solicita el ultimo rotulo impreso
              * del usuario liquidador
              */
              function solicitarUltimoRotulo()
              {
                  var usuario = <?php echo $this->ion_auth->user()->row()->id ?>;

                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      data: {usuario : usuario},
                      url: base_url+"index.php/liquidaciones/solicitarUltimoRotuloImpreso",
                      success: function(data) {              
                          $('#siguienteEstampilla').val(data.rotulo);                          
                      }
                  });  
              }
             });
         });
    }     




   }).columnFilter(

{
                     aoColumns: [
                                    
                                    {
                                         type: "number",
                                         sSelector: "#buscarnumero"
                                    },
                                    
                                    {
                                         type: "number",
                                         sSelector: "#buscarnit"
                                    },
                                    {
                                         type: "text",
                                         sSelector: "#buscarcontratista",
                                         bSmart: false

                                    },
                                    {    
                                         sSelector: "#buscarano", 
                                         type:"select" ,
                                         values : <?php echo $a; ?>
                                         
                                    },
                                    null,
                                    null,
                                    null,
                                    null,

                                    
                                 ]
               }


);
    
    oTable.fnSearchHighlighting();
    

} );




</script>


<style type="text/css">
  .dataTables_filter, .dataTables_length, .dataTables_footer {
    display: none;
  }
.item2{
    width: 160px;
    height: 15px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}
</style>
<div class="row"> 
 <div class="col-xs-12">
     <?php
        if($band_saldoestampillas) 
        {
            echo '<div class="alert alert-dismissable alert-danger">'. $notif_saldoestampillas.'</div>';
        }
     ?>
 </div>
 <div class="col-sm-12">
 <h1>Contratos</h1>

   <br><br>
 </div>
</div> 

<div class="row"> 
<div class="col-sm-1"></div>
 <div class="col-sm-2">Número:<div align="center" id="buscarnumero"></div></div>
 <div class="col-sm-2">NIT:<div align="center" id="buscarnit"></div></div>
 <div class="col-sm-4">Contratista:<div align="center" id="buscarcontratista"></div></div>
 <div class="col-sm-2">Vigencia:<div align="center" id="buscarano"></div></div>
 <div class="col-sm-1"></div>
</div>


          
    







<div class="row"> 
 <div class="col-sm-12">    
   
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Número</th>
     <th>NIT</th>
     <th>Contratista</th>
     <th>Fecha</th>
     <th>Objeto</th>
     <th>Valor</th>       
     <th>Estado</th>
     <th></th>
   </tr>
 </thead>
 <tbody></tbody>     
 <tfoot>
   <tr class="dataTables_footer">
     <th></th>
     <th></th>
     <th></th>
     <th></th>
     <th></th>
     <th></th>
     <th></th>       
     <th></th>
     <th></th>
   </tr>
 </tfoot>
</table>
</div>
</div>   
      </div>

<?php echo form_open("liquidaciones/procesarliquidacion",'role="form"');?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <input class="form-control" id="idcontrato" type="hidden" name="idcontrato" value=""/>
      <div class="modal-body liquida">
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Liquidar</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>



<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     
      <div class="modal-body paga">
         
      </div>
      <div class="modal-footer">
      <div class="text-center">
        <small> "Soluciones que Transforman"<br>
      Edificio de la Gobernación del Tolima, carrera 3 calle 10 y 11, 9 piso <br>
      Teléfonos 2610758 - 2611111 -Ext. 209 - 305<br>
      dcontratos@outlook.com </small>
      </div>
        
      </div>
    </div>
  </div>
</div>




<?php echo form_open("liquidaciones/procesarterminado",'role="form"');?>
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <input class="form-control" id="idcontrato" type="hidden" name="idcontrato" value=""/>
      <div class="modal-body termina">
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>





<?php if ($accion=='liquidado') { ?>
<script type="text/javascript">
  
            var ID = <?php echo $idcontrato; ?>;
            
            $('.paga').load('<?php echo base_url(); ?>index.php/liquidaciones/verrecibos/'+ID,function(result){
            <?php
            if (isset($errorModal)) 
            {              
                if ($errorModal)
                { ?>
                    var mensajeError = '<div class="alert alert-dismissable alert-danger">'+$('.alert').html();+'</div>'                    
                    $('#errorModal').html(mensajeError);
                  <?php
                }
            }
            ?>
            $('#myModal2').modal('show');
            
        });
        
 
</script>
<?php   } ?>




<?php if ($accion=='legalizado') { ?>
<script type="text/javascript">
  
            var ID = <?php echo $idcontrato; ?>;            
            
           $('.termina').load('<?php echo base_url(); ?>index.php/liquidaciones/vercontratolegalizado/'+ID,function(result){
            
            $('#myModal3').modal('show');

              //Solicita el ultimo rotulo al cargar la modal
              solicitarUltimoRotulo();    

              $('.confirmar_impresion').click(function(event) {
                  //Solicita el ultimo rotulo para la impresion
                  solicitarUltimoRotulo();                   

                  var siguienteEstampilla = $('#siguienteEstampilla').val();
                  if(!confirm('SIGUIENTE ESTAMPIILLA A IMPRIMIRSE => No. '+siguienteEstampilla+'\n\n'
                        +'Esta seguro de generar la impresión?'
                        +' Recuerde que será modificado el consecutivo de la papeleria asignada a usted!'))
                  {
                    event.preventDefault();
                  }

              });

              /**
              * Funcion de Apoyo que solicita el ultimo rotulo impreso
              * del usuario liquidador
              */
              function solicitarUltimoRotulo()
              {
                  var usuario = <?php echo $this->ion_auth->user()->row()->id ?>;

                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      data: {usuario : usuario},
                      url: base_url+"index.php/liquidaciones/solicitarUltimoRotuloImpreso",
                      success: function(data) {              
                          $('#siguienteEstampilla').val(data.rotulo);                          
                      }
                  });  
              }  
          
        });
        
 
</script>
<?php   } ?>


