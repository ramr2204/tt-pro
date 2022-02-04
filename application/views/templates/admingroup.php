<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:           admin template
*   Ruta:             /application/views/templates/admingroup.php
*   Descripcion:      Plantilla exclusiva para usuarios registrados
*   Fecha Creacion:   12/may/2014
*   @author           Iván Viña <ivandariovinam@gmail.com>
*   @version          2014-07-16
*
*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<META NAME="DC.Language" SCHEME="RFC1766" CONTENT="Spanish">
<META NAME="AUTHOR" CONTENT="Iván Darío Viña Marulanda">
<META NAME="REPLY-TO" CONTENT="ivandariovinam@gmail.com">
<LINK REV="made" href="mailto:ivandariovinam@gmail.com">
<META NAME="Resource-type" CONTENT="Document">
<META NAME="DateCreated" CONTENT="Mon, 5 May 2014 08:00:00 GMT-5">

<title><?php echo ( isset( $title ) ) ? $title.'|| Sistema de estampillas pro ' : WEBSITE_NAME.' || Sistema de estampillas pro'; ?></title>
<?php
    // Add any keywords
    echo ( isset( $keywords ) ) ? meta('keywords', $keywords) : '';

    // Add a discription
    echo ( isset( $description ) ) ? meta('description', $description) : '';

    // Add a robots exclusion
    echo ( isset( $no_robots ) ) ? meta('robots', 'index,follow') : '';

    // Always add the main stylesheet
     echo link_tag( array( 'href' => 'css/favicon.ico', 'media' => 'screen', 'rel' => 'shortcut icon' ) ) . "\n";
     echo link_tag( array( 'href' => 'css/bootstrap.min.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
     echo link_tag( array( 'href' => 'css/sb-admin.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
     echo link_tag( array( 'href' => 'dist/ladda-themeless.min.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
     echo link_tag( array( 'href' => 'css/datepicker.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
     echo link_tag( array( 'href' => 'css/estampillas.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";

     //echo link_tag( array( 'href' => 'css/font-awesome.min.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n"; 
     ?> 
     <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">     
     <?php
     echo link_tag( array( 'href' => 'css/stylea.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
     echo link_tag( array( 'href' => 'css/yamm.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
     echo link_tag( array( 'href' => 'css/alertRotulosStyles.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";

    // Add any additional stylesheets
    if( isset( $style_sheets ) )
    {
        foreach( $style_sheets as $href => $media )
        {
            echo link_tag( array( 'href' => $href, 'media' => $media, 'rel' => 'stylesheet' ) ) . "\n";
        }
    }

    // jQuery  always loaded
    echo script_tag( 'js/jquery-1.11.0.min.js' ) . "\n";
    echo script_tag( 'js/bootstrap.min.js' ) . "\n";
    echo script_tag( 'dist/spin.min.js' ) . "\n";
    echo script_tag( 'dist/ladda.min.js' ) . "\n";
    echo script_tag( 'js/datepicker.js' ) . "\n";
    echo script_tag( 'js/applicationEvents.js' ) . "\n";

    
    // Add any additional javascript
    if( isset( $javascripts ) )
    {
        for( $x=0; $x<=count( $javascripts )-1; $x++ )
        {
            echo script_tag( $javascripts["$x"] ) . "\n";
        }
    }

    // Add anything else to the head
    echo ( isset( $extra_head ) ) ? $extra_head : '';
    
?>

</head>

<body> 
<input id="base" type="hidden" value="<?php echo base_url(); ?>">
<?php if (!$this->ion_auth->logged_in()) { ?>

<header class="cabecera">
      <div class="row">
        <div class="col-md-3 contLogo">
          <div align="center"> 
            <img src="<?php echo base_url(); ?>/images/logogober.png" alt="bitbahia.com" class="img-responsive logoLogin" style="max-width: 170px;">
          </div>
        </div>
        <div class="col-md-9" >
          <p style="font-size: 35px; padding-top: 2.5%;">
            Sistema De Información De Estampillas PRO
          </p>
        </div>
      </div>      
    </header>

<?php } ?>




<?php if ($this->ion_auth->logged_in()) { ?>
 <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top head" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
           <div class="logo"><a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/images/logogober.png" alt="bitbahia.com" class="img-responsive logoImagen"></a></div>

        </div>

        <div class="navbar-collapse collapse">
        <?php if ($get_menus==true) { ?>
           <ul class="nav navbar-nav">  
          



       <?php
        foreach($nav_procesos as $key_proceso => $value_proceso) :
          ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-folder-close"></span> <?php echo $value_proceso['proc_nombre']; ?><b class="caret"></b> </a>
              <ul class="dropdown-menu">
              
                <?php
                  foreach($nav_aplicaciones as $key_aplicacion => $value_aplicacion) :
                    if ($value_aplicacion['apli_procesoid']==$key_proceso) :
                      ?>
                        <li class="menu-item dropdown dropdown-submenu">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $value_aplicacion['apli_nombre']; ?></a>
                          <ul class="dropdown-menu">
                            <?php
                              foreach($nav_modulos as $key_modulo => $value_modulo) :
                                if ($value_modulo['modu_aplicacionid']==$key_aplicacion) :
                                  ?> 
                                    <li class="menu-item dropdown dropdown-submenu">
                                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $value_modulo['modu_nombre']; ?></a>
                                      <ul class="dropdown-menu">
                                          <?php
                                            foreach($nav_menus as $key_menu => $value_menu) :
                                              ?>
                                              <?php if ($value_menu['menu_moduloid']==$key_modulo) : ?>
                                                    <li class="menu-item menu-link">
                                                    <a href="<?php echo base_url().$value_menu['menu_ruta']; ?>"><?php echo $value_menu['menu_nombre']; ?></a>
                                                    </li>
                                              <?php endif; ?> 
                                          <?php endforeach; ?> 
                                      </ul>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?> 

                          </ul>

                        </li>
                  <?php endif; ?>
              <?php endforeach; ?>  
              
              </ul>
            </li>

       <?php endforeach; ?> 
      
      </ul>

        <?php } ?>
        

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-bell"></span>
                    </a>
                    <ul class="dropdown-menu notify-drop">
                        <div class="notify-drop-title">
                            <div class="row">
                                <div class="col-md-12"><b>Notificaciones</b></div>
                            </div>
                        </div>
                        <div class="drop-content">
                            <!-- <li class="text-center" style="font-size: 13px;">
                                Sin notificaciones
                            </li> -->
                            <!-- <li>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <div class="notify-img text-danger">
                                        <i class="glyphicon glyphicon-exclamation-sign"></i>
                                    </div>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                    <div class="notify-header">
                                        <a href="">Ahmet</a>
                                        <span class="text-muted">03-08 9:30</span>
                                    </div>
                                    <p>Lorem ipsum sit dolor amet consilium  weffewfew klqwe ioqwe c9cas odanwq wqnw qw weofenw wefiefw.</p>
                                </div>
                            </li>
                            <li>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <div class="notify-img text-success">
                                        <i class="glyphicon glyphicon-ok-sign"></i>
                                    </div>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                    <div class="notify-header">
                                        <a href="">Ahmet</a>
                                        <span class="text-muted">03-08 9:30</span>
                                    </div>
                                    <p>Lorem ipsum sit dolor amet consilium.</p>
                                </div>
                            </li> -->
                        </div>
                        <div class="notify-drop-footer text-center">
                            <div class="btn-group">
                                <a id="previo_n"
                                    class="btn btn-default btn-xs disabled"
                                    href="#"
                                    title="Página anterior"
                                >
                                    <i class="glyphicon glyphicon-backward"></i>
                                </a>
                                <a id="siguiente_n"
                                    class="btn btn-default btn-xs"
                                    href="#"
                                    title="Página siguiente"
                                >
                                    <i class="glyphicon glyphicon-forward"></i>
                                </a>
                            </div>
                        </div>
                    </ul>
                </li>
            </ul>

            <!-- 
            glyphicon glyphicon-backward
            glyphicon-forward
            glyphicon-exclamation-sign
            glyphicon-ok-sign
             -->


          <ul class="nav navbar-nav navbar-right">
            <?php if ($this->ion_auth->is_admin() || $this->ion_auth->user()->row()->perfilid == 10) { ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administración<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <?
                  if ($this->ion_auth->is_admin() || $this->ion_auth->user()->row()->perfilid == 10)
                  {
                    ?>
                      <li><a href="<?php echo base_url(); ?>index.php/users">Usuarios</a></li>
                    <?
                  }
                  if ($this->ion_auth->is_admin())
                  {
                    ?>
                    <li><a href="<?php echo base_url(); ?>index.php/parametros">Parámetros</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/perfiles">Perfiles</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/procesos">Procesos</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/aplicaciones">Aplicaciones</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/modulos">Módulos</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/menus">Menús</a></li>
                    <?
                  }
                ?>
              </ul>
            </li>
            <?php } ?>
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuario<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url(); ?>index.php/users/editme">Cambiar mis datos</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/users/logout">Desconectarse</a></li>
              </ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right">           
              <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manuales<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url(); ?>Manuales_Estampillas/estampillas_liquidador/site/index.html" target="_blank">Manual del Liquidador</a></li>
                <?php if ($this->ion_auth->is_admin()) { ?>
                <li><a href="<?php echo base_url(); ?>Manuales_Estampillas/estampillas_administrador/site/index.html" target="_blank">Manual del Administrador</a></li>     
                <?php } ?>           
              </ul>
            </li>
          </ul>

        </div><!--/.nav-collapse -->
      </div>
    </div>
<?php } //if login  && is_admin ?>

    <?php 
    /**
     * Notificacion para verificacion de anulacion de rotulos
     */
    if ($this->ion_auth->logged_in() && ($this->ion_auth->in_menu('impresiones/anulaciones') || $this->ion_auth->is_admin() ))
    {
        $objHelper = new HelperGeneral;
        $informacionAlertaRotulosAnuladosSinVerificar = $objHelper->solicitarInformacionAlertaRotulosAnuladosSinVerificar();

        if($informacionAlertaRotulosAnuladosSinVerificar['mostrarAlerta'])
        {
?>
            <div class="notification">
                <div class="content">
                <div class="text">Debe realizar la verificaci&oacute;n de rotulos anulados, hay <?php echo $informacionAlertaRotulosAnuladosSinVerificar['cantidadRotulosAnulados']; ?> rotulo(s) pendientes.</div>
                </div>
            </div>
            <div class="number"><p class="glyphicon glyphicon-exclamation-sign"></p></div>
<?php 
        } //if show alert rotulos
    } //if login ?>

    <?
      $objHelper = new HelperGeneral;
      $mensajeEmpresa = $objHelper->verificarVencimientoEmpresa();

      if($mensajeEmpresa)
      {
        ?>
        <div class="notification">
          <div class="content">
            <div class="text"><?= $mensajeEmpresa ?></div>
          </div>
        </div>
        <div class="number"><p class="glyphicon glyphicon-exclamation-sign"></p></div>
        <?
      }
    ?>

 <div class="container" id="cont_contenidogeneral">

      <div class="content">
        
      <br>
         <?php
        if (isset($errormessage)) {
              
          if ($errormessage)
            {
              echo '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. $errormessage.'</div>';
            }
          }
        if (isset($infomessage)) {
                  
          if ($infomessage)
            {
              echo '<div class="alert alert-dismissable alert-info"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. $infomessage.'</div>';
            }
          }
        if (isset($successmessage)) {
           
          if ($successmessage)
            {
              echo '<div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. $successmessage.'</div>';
            }
          }
        if (isset($warnigmessage)) {
          
          if ($warnigmessage)
            {
              echo '<div class="alert alert-dismissable alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. $warnigmessage.'</div>';
            }
         }  
       if (isset($primarymessage)) {
             
          if ($primarymessage)
            {
              echo '<div class="alert alert-dismissable alert-primary"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. $primarymessage.'</div>';
            }
          }
         ?>

        <?php echo $contents; ?>
      
      </div>


         
 </div> 
 <nav class="navbar navbar-default" role="navigation">
  <footer>
      <div >
        <div class="container">
          <center>
            <br/>
            <p style="font-size: 16px;">
                Todos los derechos reservados Turrisystem © <?php echo date('Y'); ?>
            </p>

          </center>
        </div>
      </div>
    </footer>
</nav>

    <script type="text/javascript" language="javascript" charset="utf-8">

        var notificaciones = {

            pagina: 1,
            por_pagina: 10,

            init: function(){
                notificaciones.eventos();
                notificaciones.consultar();
            },
            eventos: function(){
                $('#previo_n').click(notificaciones.cambiarPaginaPrevio)
                $('#siguiente_n').click(notificaciones.cambiarPaginaSiguiente)

                // Evitar que el menu se cierre
                $(document).on('click', '.dropdown-menu.notify-drop', function (e) {
                    e.stopPropagation();
                })
            },
            cambiarPaginaPrevio: function(){
                if(!$(this).hasClass('disabled')){
                    notificaciones.consultar(notificaciones.pagina - 1)
                }
            },
            cambiarPaginaSiguiente: function(){
                if(!$(this).hasClass('disabled')){
                    notificaciones.consultar(notificaciones.pagina + 1)
                }
            },
            consultar: function(pagina=1){

                notificaciones.iniciarCargado();

                // Se desactivan temporalmente los botones
                $('#previo_n').addClass('disabled');
                $('#siguiente_n').addClass('disabled');

                $.ajax({
                    url : `${base_url}/index.php/notificaciones/listado?pagina=${pagina}`,
                    type: 'GET',
                    success: function ( datos ){
                        if(datos.exito){

                            notificaciones.pagina = pagina;

                            var html = '';
                            var estilos = datos.estilos;

                            datos.notificaciones.forEach(function(notificacion){
                                html += `<li>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <div class="notify-img text-${estilos[notificacion.tipo].color}">
                                            <i class="glyphicon glyphicon-${estilos[notificacion.tipo].icono}"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                        <div class="notify-header">
                                            <a href="${base_url}/index.php/notificaciones/detalle/${notificacion.id}">${datos.descripciones[notificacion.tipo]}</a>
                                            <span class="text-muted">${notificacion.fecha}</span>
                                        </div>
                                        <p>${notificacion.texto}...</p>
                                    </div>
                                </li>`;
                            })
                            $('.drop-content').html(html);

                            notificaciones.actualizarPaginador(datos.notificaciones)

                            if(datos.notificaciones.length == 0){
                                notificaciones.notificacionSimple('Sin notificaciones');
                            }
                        } else {
                            notificaciones.notificacionSimple(datos.mensaje);
                        }
                    }
                })
            },
            actualizarPaginador: function(datos){

                if(notificaciones.pagina > 1){
                    $('#previo_n').removeClass('disabled');
                }else{
                    $('#previo_n').addClass('disabled');
                }

                if(datos.length == notificaciones.por_pagina){
                    $('#siguiente_n').removeClass('disabled');
                }else{
                    $('#siguiente_n').addClass('disabled');
                }
            },
            iniciarCargado : function() {
                notificaciones.notificacionSimple('Cargando <span class="fa fa-spinner spinning" style="font-size: 20px;"></span>');
            },
            notificacionSimple : function(contenido) {
                $('.drop-content').html(`<li>
                    <div class="col-xs-12">
                        <div class="notify-header">
                            <div class="w-100 text-center">
                                ${contenido}
                            </div>
                        </div>
                    </div>
                </li>`);
            }
        }

        $(function () {
            notificaciones.init();
        });
    </script>

</body>
</html>
<?php


