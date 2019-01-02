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
     echo link_tag( array( 'href' => 'css/applicationStyles.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";

    // Add any additional stylesheets
    if( isset( $style_sheets ) )
    {
        foreach( $style_sheets as $href => $media )
        {
            echo link_tag( array( 'href' => $href, 'media' => $media, 'rel' => 'stylesheet' ) ) . "\n";
        }
    }

    // jQuery  always loaded
    echo script_tag( 'js/jquery-1.10.2.js' ) . "\n";
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
            <img src="<?php echo base_url(); ?>/images/logo_presentacion.png" alt="bitbahia.com" class="img-responsive logoLogin">
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
           <div class="logo"><a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/images/logo_presentacion.png" alt="bitbahia.com" class="img-responsive logoImagen"></a></div>

        </div>

        <div class="navbar-collapse collapse">
        <?php if ($get_menus==true) { ?>
           <ul class="nav navbar-nav">  
          



       <?php  foreach($nav_procesos as $key_proceso => $value_proceso) : ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-folder-close"></span> <?php echo $value_proceso['proc_nombre']; ?><b class="caret"></b> </a>
            <ul class="dropdown-menu">
            
             <?php foreach($nav_aplicaciones as $key_aplicacion => $value_aplicacion) : ?>
                 <?php if ($value_aplicacion['apli_procesoid']==$key_proceso) : ?> 
                    <li class="menu-item dropdown dropdown-submenu">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $value_aplicacion['apli_nombre']; ?></a>
                      <ul class="dropdown-menu">
                       <?php foreach($nav_modulos as $key_modulo => $value_modulo) : ?>
                           <?php if ($value_modulo['modu_aplicacionid']==$key_aplicacion) : ?> 
                             <li class="menu-item dropdown dropdown-submenu">
                               <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $value_modulo['modu_nombre']; ?></a>
                               <ul class="dropdown-menu">
                                   <?php foreach($nav_menus as $key_menu => $value_menu) : ?>
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
            <?php if ($this->ion_auth->is_admin()) { ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administración<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url(); ?>index.php/users">Usuarios</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/parametros">Parámetros</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/perfiles">Perfiles</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/procesos">Procesos</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/aplicaciones">Aplicaciones</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/modulos">Módulos</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/menus">Menús</a></li>
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
     * Notificacion para rotulos restantes
     */
    if ($this->ion_auth->logged_in()) 
    {
        $objHelper = new HelperGeneral;
        $informacionAlertaRotulosUsuario = $objHelper->solicitarInformacionAlertaRotulosMinimosUsuarioAutenticado();

        if($informacionAlertaRotulosUsuario['mostrarAlerta'])
        {
?>
            <div class="notification">
                <div class="content">
                <div class="text">Debe solicitar rotulos para impresi&oacute;n, actualmente tiene disponibles <?php echo $informacionAlertaRotulosUsuario['cantidadRotulosDisponiblesUsuario']; ?> rotulos.</div>
                </div>
            </div>
            <div class="number"><p class="glyphicon glyphicon-exclamation-sign"></p></div>
<?php 
        } //if show alert rotulos
    } //if login ?>

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
            <p style="font-size: 20px;">
                Thomas Greg & Sons de Colombia S.A. © <?php echo date('Y'); ?>
            </p>

          </center>
        </div>
      </div>
    </footer>
</nav>
</body>
</html>
<?php


