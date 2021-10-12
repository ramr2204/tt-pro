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
    $('#btn-confirdata').on('click', function(event){
        var r = confirm("¿Estas seguro de estos datos?");

        if (r == true) {
            window.location.href ='<?php echo base_url()?>'+ 'index.php/liquidaciones/pagarContrato/'+$('#idvalue').val();
        }
    });

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
            { "sClass": "center","bVisible": false}, 
        ],   
        "fnRowCallback" : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if(aData[5] != null)
            {
                $("td:eq(4)", nRow).html('<div class="small">' + aData[5].substr( 0, 130 )+ '...</div>');
            }
            else
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
            if (aData[8]==0) {
                // Se comenta ya que el proceso de pago aun no ha sido terminado
                // $("td:eq(7)", nRow).append('<a href="#" class="btn btn-info btn-xs pagar-contrato" data-toggle="modal" data-target="#modalLiquidacion" title="pse" id="'+aData[0]+'"><i class="fa fa-shopping-cart"></i></a>');
            }

            // Si tiene mas de un numero de pagos es por retencion y se mostrara en todos los estados expecto sin liquidar
            if(aData[9] > 0 && aData[7] != null) {
                $("td:eq(7)", nRow).append('<a href="#" class="btn btn-warning btn-xs pagar-estampillas" title="Pagar estampillas por contención" id-contrato="'+aData[0]+'"><i class="fa fa-legal"></i></a>');
            }
        },
        "fnDrawCallback": function( oSettings ) {
            $(".pagar-contrato").on('click', function(event){
                var ID = $(this).attr("id");
                $('#idvalue').val(ID);
                $.ajax({
                    url: '<?php echo base_url(); ?>index.php/liquidaciones/consultarLiquidacion/'+ID,
                    success: function(data)
                    {
                        var data = JSON.parse(data);
                        $('#modal-pse-liquidar').html();
                        var modalbody = '';
                        var totalValor = 0;
                        data.facturas.forEach(function(factura, index){
                            modalbody += '<tr>'+
                                '<td width="25%">'+factura.fact_nombre+'</td>'+
                                '<td width="13%" class="text-right">$'+factura.fact_valor+'</td>'+
                                '<td width="12%" class="text-right">'+factura.fact_id+'</td>'+
                                '<td width="50%" class="text-center">'+
                                    '<img src="<?php echo $this->config->item('application_root'); ?>application/libraries/barcodegen/'+factura.codigo_barras+'.png width="300" height="40"><small>'+factura.codigo_barras+'</small>'+
                                '</td>'+
                            '</tr>';


                            $('#modal-pse-liquidar').append(modalbody);
                            totalValor = totalValor + parseInt(factura.fact_valor);
                        });

                        htmlValor = 
                            '<tr>'+
                                '<td>Total</td>'+
                                '<td class="text-right" colspan="3">'+totalValor+ '</td>'+
                            '</tr>';
                        $('#modal-pse-liquidar').append(htmlValor);
                    }
                })
            });
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
                            }
                            else
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

                  $('.confirmar_impresion').click(validarNumeroRotuloLiquidador);
                });
            });
        }     

       }).columnFilter({
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
        ]}
    );
        
    oTable.fnSearchHighlighting();  

});
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
    <div rol="usuario_rotulo" style="display:none;"><?php echo $this->ion_auth->user()->row()->id; ?></div>
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
    <input type="hidden" name="idvalue" id="idvalue">

    <!-- Modal -->
    <div class="modal fade" id="modalLiquidacion" tabindex="-1" role="dialog" aria-labelledby="modalLiquidacionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLiquidacionLabel">Datos Generales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered " id="tablaq" border="1" cellpadding="1">
                        <thead>
                            <tr>
                                <td width="25%" class="text-center"><strong>Estampilla</strong></td>
                                <td width="13%" class="text-center"><strong>Valor</strong></td>
                                <td width="12%" class="text-center"><strong>Factura</strong></td>
                                <td width="50%" class="text-center"><strong>Código de barras</strong></td>
                            </tr>
                        </thead>
                        <tbody id="modal-pse-liquidar"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-confirdata">Confirmar datos</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Cierra modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body paga"></div>
            <div class="modal-footer">
                <div class="text-center">
                <small> "Boyacá Avanza"<br>
                  Palacio de la Torre, Calle 20 No. 9 – 90 <br>
                  Teléfono PBX+(57)608742 0150<br>
                  contactenos@boyaca.gov.co </small> 
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


<?php
    if ($accion=='liquidado')
    {
        ?>
        <script type="text/javascript">
            var ID = <?php echo $idcontrato; ?>;
            
            $('.paga').load('<?php echo base_url(); ?>index.php/liquidaciones/verrecibos/'+ID,function(result){
                <?php
                    if (isset($errorModal)) 
                    {
                        if ($errorModal)
                        {
                            ?>
                            $('#errorModal').html( $('.alert')[0].outerHTML );
                            <?php
                        }
                    }
                ?>
            $('#myModal2').modal('show');
            
        });

        </script>
        <?php
    }
?>


<?php
    if ($accion=='legalizado')
    {
        ?>
        <script type="text/javascript">
        
            var ID = <?php echo $idcontrato; ?>;            
                    
            $('.termina').load('<?php echo base_url(); ?>index.php/liquidaciones/vercontratolegalizado/'+ID,function(result){

                $('#myModal3').modal('show');
            
                $('.confirmar_impresion').click(validarNumeroRotuloLiquidador);
            });
        </script>
        <?php
    }
?>

<!-- Modal de pago de estampillas por retencion -->
<div class="modal fade" id="pago_estampillas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body contenedor_pago_estampillas"></div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.pagar-estampillas', onClickPagarEstampillas);

    function onClickPagarEstampillas(event, ID=null, error_modal=false){
        if(event){
            event.preventDefault();
        }
        ID = ID ? ID : $(this).attr('id-contrato');

        $('.contenedor_pago_estampillas').load(
            '<?php echo base_url(); ?>index.php/liquidaciones/estampillasRetencion/'+ID,
            function(result){
                $('#pago_estampillas').modal('show');
                $('.id_contrato_cont').val(ID);

                if(error_modal){
                    $('#errorModal').html( $('.alert')[0].outerHTML );
                }
            }
        );
    }

    <?php
        if ($accion=='retencion')
        {
            ?>
            onClickPagarEstampillas(null, <?= $idcontrato ?>, <?= $errorModal ?>);
            <?php
        }
    ?>

    <?php
        if(isset($idPagoFactura) && $idPagoFactura)
        {
            ?>
            window.open($('#base').val() + 'generarpdf/certificadoPagoEstampilla?id=<?= urlencode($idPagoFactura) ?>', '_blank');
            <?php
        }
    ?>
</script>


