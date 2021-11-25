
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Código</title>
        <style media="all">
            body{
                background-color: #F7F7F7;
                font-family: "Source Sans Pro", "Arial", sans-serif;
                color: #0b1409 !important;
            }
            .contenedor{
                margin: 0 auto;
                padding: 2px;
                width: 80%;
            }
            #header{
                background-color: #ffff;
                height: auto;
                margin: 0 auto;
                width: 100% !important;
            }
            #banner{
                height: auto;
                width: 100%;
            }
            #content{
                background-color: #F7F7F7;
                /*border-bottom: 2px solid black;*/
                height: auto;
                margin: 0 auto;
                padding: 3px;
                width: 99.5%;
                text-align: center;
            }
            .message-code{
                font-size: 14px;
                padding-left: 10px;
                padding-top: 6px;
            }
            .message-bottom{
                text-align: center;
                font-weight: bold;
            }
            .copy{
                font-size: 12px;
            }
            #codeSign{
                background-color: white;
                border: 2px solid black;
                color: black;
                font-size: 25px;
                margin: 0 auto;
                margin-bottom: 5px;
                padding: 5px;
                text-align: center;
                width: 20%;
                text-decoration: none;
            }
            #footer{
                background-color: #00632d;
                color: white;
                font-style: italic;
                font-size: 10px;
                height: auto;
                margin: 0 auto;
                margin-top: 0;
                width: 100% !important;
                /*text-align: right !important;*/
            }
            table, td, th {  
                border: 1px solid black;
                text-align: center;
            }
            table:first-child{
                background: #00632d;
            }
            table {
                border-collapse: collapse;
                margin: 0 auto;
                width: 100%;
            }
            th, td {
                border: 0; border-bottom:1px solid #000
            }
            .prop{
                background: #13A615;
                color: white;
                font-weight: bold;
            }
            .bannerHead{
                    background: #13A615;
                    border: 2px solid black;
                    color: white;
                    font-weight: bold;
                    text-align: center;
            }
            .enlace-btn{
                background: #00940D;
                border-radius: 12px;
                border: 3px solid black;
                color: white;
                font-weight: bolder;
                font-size: 26px;
                margin: 0 auto;
                padding: 5px;
                width: 80%;
            }
            .enlace-btn > a{
                color: white;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="contenedor">
            <div id="header">
                <img id="banner" src="<?= site_url('images/bannerlogo.png') ?>" style="height: auto;width: 578px;    display: block;margin: auto;" alt="Banner Cordoba">
            </div>
            <div id="content">
                <p class="message-code">En el siguiente enlace podrá consultar las facturas generadas.</p>

                <a id="codeSign" href="<?= $ruta ?>" target="_blank">Facturas</a>
                <p>Si el anterior boton no funciona, por favor copie y pegue el siguiente enlace en su navegador web</p>
                <a href="<?= $ruta ?>" target="_blank"><?= $ruta ?></a>
            </div>
            <div id="footer">
                <table>
                    <tr>
                        <td>
                            <img src="<?= site_url('images/desarrolladoturri.png') ?>" width="150px" height="40px">
                        </td>
                        <td>
                            <p style="font-size: 13px">Este correo fue generando automáticamente, por favor no responder.</p>
                            <div class="copy">Todos los derechos reservados Turrisystem ® <?php echo date('Y') ?></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>