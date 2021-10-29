<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * @author      Iván viña
 *
 **/
?><!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo ( isset( $title ) ) ? $title : WEBSITE_NAME.' || Compra y vende de todo con Bitcoins'; ?></title>
<?php
    // Add any keywords
    echo ( isset( $keywords ) ) ? meta('keywords', $keywords) : '';

    // Add a discription
    echo ( isset( $description ) ) ? meta('description', $description) : '';

    // Add a robots exclusion
    echo ( isset( $no_robots ) ) ? meta('robots', 'index,follow') : '';
?>
<?php 
    // Always add the main stylesheet
   echo link_tag( array( 'href' => 'css/favicon.ico', 'media' => 'screen', 'rel' => 'shortcut icon' ) ) . "\n";
    echo link_tag( array( 'href' => 'css/bootstrap.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
    echo link_tag( array( 'href' => 'css/font-awesome.min.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
     echo link_tag( array( 'href' => 'css/style.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
      echo link_tag( array( 'href' => 'http://fonts.googleapis.com/css?family=Ubuntu', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
    // Add any additional stylesheets
    if( isset( $style_sheets ) )
    {
        foreach( $style_sheets as $href => $media )
        {
            echo link_tag( array( 'href' => $href, 'media' => $media, 'rel' => 'stylesheet' ) ) . "\n";
        }
    }

    // jQuery  always loaded
    echo script_tag( 'js/jquery-1.9.1.js' ) . "\n";
    echo script_tag( 'js/bootstrap.min.js' ) . "\n";
    //echo script_tag( 'js/chosen.jquery.min.js' ) . "\n";

    
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
     <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-17182911-23', 'bitbahia.com');
  ga('send', 'pageview');

</script>
</head>
<body>    

<div class="navbar navbar-default  head" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <div class="container">

          <div class="row">
  
            <div class="col-sm-12 logo"><a class="" href="/"><img src="<?php echo base_url(); ?>/images/logogober.png" style="max-width: 120px" alt="bitbahia.com" class="img-responsive logoImagen"></a></div>
  
          </div>
          
          </div>
        </div>
      
      </div>
    </div>
 
         <?php echo $contents ?>
         
 
</body>
</html>
<?php

/* End of file main.php */
/* Location: /application/views/templates/main.php */
