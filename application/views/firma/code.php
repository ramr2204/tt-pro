
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
            }
            .message-code{
                font-size: 14px;
                padding-left: 10px;
                padding-top: 6px;
                text-align: center;
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
                <img id="banner" src="../images/bannerlogo.png" style="height: auto;width: 578px;    display: block;margin: auto;" alt="Banner Cordoba">
            </div>
            <div id="content">
                <p class="message-code">A continuación el código de verificación generado en TTI Córdoba para que pueda realizar la respectiva firma de la declaración.</p>
                <div id="codeSign">
                    <?php echo $code['code']; ?>
                </div>
                <p class="message-bottom">El código de firma tiene vigencia de 60 minutos, pasado este tiempo ya no sera valido y deberá solicitar un código nuevo.</p>
            </div>
            <div id="footer">
                <table>
                    <tr>
                        <td>
                            <img src="../images/desarrolladoturri.png" width="150px" height="40px">
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