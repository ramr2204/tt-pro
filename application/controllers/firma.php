<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   @author            David Mahecha
*   @version           2021-10-20
*
*/

class Firma extends MY_Controller
{
    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation','Pdf');
        $this->load->model('codegen_model','',TRUE);
        
        $this->load->helper(['form','url','codegen_helper', 'array']);
        $this->load->helper(['Equivalencias', 'EquivalenciasFirmas']);
        $this->load->helper('HelperGeneral');
    }

    public function renderSignDeclaracion()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index'))
            {
                $datos = $this->input->post();

                # Si es igual a uno requiere asignación de segunda clave
                if( $this->input->post('st') == '1' ){
                    $vista = 'password';
                }else{

                    $datos['info'] = $this->getInfoSign($this->input->post('user'));

                    $datos['v'] = $this->getAvaibleSign([
                        'ref'   =>  $this->input->post('ref'),
                        'tp'    =>  $datos['info']['firma']['tipo']
                    ]);

                    $vista = 'sign';

                    // echo '$datos<pre>';var_dump( $datos );echo '</pre>';die();
                }
                $this->load->view('firma/'.$vista, $datos); 
            } else {
                redirect(base_url().'index.php/error_404');
            }
        } else {
            redirect(base_url().'index.php/users/login');
        }
    }

    /*
    * Metodo para obtener información de un firma de usuario
    */
    private function getInfoSign($id)
    {
        $info = [];

        # Consultamos el registro de la firma
        $result_sign = $this->codegen_model->get(
            'usuarios_firma',
            'id, id_usuario, estado, tipo',
            'id = '.$id,
            1,NULL,true
        );
        $result_sign->tipo_nombre = $this->getLabelType($result_sign->tipo);

        # Obtenemos la informacion del usuario
        $result_user = $this->codegen_model->get(
            'users',
            'id, email, first_name, last_name, phone, id_empresa',
            'id = '.$result_sign->id_usuario,
            1,NULL,true
        );

        $info['firma'] = (array)$result_sign;
        $info['usuario'] = (array)$result_user;
        $info['adicional'] = [
            'empresa' => (array)$this->getInfoEmpresa($result_user->id_empresa)
        ];
        return $info;
    }

    /*
    * Metodo que recupera la informacion de la empresa segun el tipo y el nit
    */
    private function getInfoEmpresa($id_empresa)
    {
        $empresa = $this->codegen_model->get(
            'empresas',
            'id, nombre, nit, email',
            'id = '.$id_empresa,
            1,NULL,true
        );

        return $empresa ? $empresa : [];
    }

    /*
    * Metodo que verifica segun el numero de documento si el usuario puede firmar la declaracion
    */
    private function getAvaibleSign($param)
    {
        $response = (object)[
            'state' => false,
            'message' => 'Usted ya ha firmado esta declaración y se está a la espera que sea firmada por los demás responsables para ser aprobada',
            'firmas' => []
        ];

        # Obtenemos el elemento
        $elemento = $this->getElemento($param['ref']);

        // echo '$elemento<pre>';var_dump( $elemento );echo '</pre>';die();

        $tipos_grupos = EquivalenciasFirmas::tiposGrupos();

        # Todos los tipos de usuarios seran necesarios
        $permisos = array_reduce($tipos_grupos, function($acumulador, $grupo){
            return array_merge($acumulador, $grupo);
        }, []);

        // echo '$permisos<pre>';var_dump( $permisos );echo '</pre>';die();

        # Verificamos si tiene permisos para firmar
        if (in_array($param['tp'], $permisos))
        {
            # Verificamos si el elemento esta creado , si no es la primera firma
            if (isset($elemento['elemento']))
            {
                if ($elemento['elemento']->estado != EquivalenciasFirmas::declaracionIniciada())
                {
                    $response->message = 'Ya declaración ya ha sido firmada por todos.';
                    return $response;
                }
                # Verificamos si ya existe una firma del mismo tipo del usuario que va firmar
                if (isset($elemento['info']) && count($elemento['info']) > 0)
                {
                    $nombres_grupos = EquivalenciasFirmas::tiposGruposNombres();

                    foreach($tipos_grupos AS $indicador_grupo => $grupo)
                    {
                        if(in_array($param['tp'], $grupo))
                        {
                            if (!$elemento['firmas'][$indicador_grupo]) {
                                $response->state = true;
                            } else {
                                $response->message = 'Esta declaración ya ha sido firmada por el ' . $nombres_grupos[$indicador_grupo];
                            }
                        }
                    }
                } else {
                    $response->state = true;
                }
            } else {
                $response->state = true;
            }
            $response->firmas = isset($elemento['info']) ? $elemento['info'] : array();
        } else {
            $response->message = 'No tiene permisos para firmar esta declaraci&oacute;n';
        }
        return $response;
    }

    private function getElemento($referencia)
    {
        $response = [];

        $result_elemento = $this->codegen_model->get(
            'declaraciones',
            'estado',
            'id = '.$referencia,
            1,NULL,true
        );

        if (empty($result_elemento) == false)
        {
            $response['elemento'] = $result_elemento;

            # Obtenemos las firmas activas en el elementos
            $result_all = $this->codegen_model->getSelect(
                'elemento_firma AS firma',
                'firma.id AS id, firma.fecha AS fecha_firma, u_firma.id_usuario ,
                    u_firma.key_hash AS key_hash, u_firma.created_at AS created_at, u_firma.tipo AS tipo_usuario,
                    u.id_empresa, u.first_name, u.last_name',
                'WHERE firma.estado = 1
                    AND firma.id_declaracion = "'. $referencia .'"',
                'INNER JOIN usuarios_firma u_firma ON u_firma.id = firma.id_usuario_firma
                    INNER JOIN users u ON u.id = u_firma.id_usuario',
                '',
                'ORDER BY fecha_firma DESC'
            );

            $tipos_grupos = EquivalenciasFirmas::tiposGrupos();

            $firmas = array_map(function($grupo){
                return false;
            }, $tipos_grupos);

            # Recorremos las firmas y obtenemos información de los firmantes
            if (count($result_all) >  0)
            {
                foreach ($result_all as $item) {
                    $info = [ 'empresa' => $this->getInfoEmpresa($item->id_empresa) ];

                    foreach($tipos_grupos AS $indice => $grupo) {
                        if(in_array($item->tipo_usuario, $grupo)) {
                            $firmas[$indice] = true;
                        }
                    }

                    $item = (array)$item;
                    $item['label'] = $this->getLabelType($info['type']);
                    $content = array_merge($item, $info);
                    $response['info'][$item['id']] = $content;
                    $response['info'][$item['id']]['code'] = $this->genereteCodeBarSign($item['created_at'], $item['id_usuario']);
                }
                $response['firmas'] = $firmas;
            }
        }

        return $response;
    }

    private function getLabelType($type)
    {
        $tipos_usuarios = EquivalenciasFirmas::tiposUsuarios();
        
        $label = $tipos_usuarios[$type];

        return $label ? $label : 'N/A';
    }

    private function genereteCodeBarSign($datetime, $cod)
    {
        $code = '';

        # Establecemos el nuevo formato del datetime
        $date = new DateTime($datetime);
        $code = $date->format('YmdHis');

        # Establecemos la longitud del codigo
        $long = strlen($cod);
        $code = $long . $cod . $code;

        # Generamos dos numeros aleatorios al final
        $random = mt_rand(10, 99);
        $code = $code . $random;

        return $code;
    }

    public function sendMail()
    {
        $result = [
            'status' => 0,
            'message' => '',
        ];

        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index'))
            {
                $this->load->helper('EnvioCorreoHelper');
                $mail = new EnvioCorreoHelper();

                $id = $this->input->post('id');
                $email_destino = $this->input->post('mail');
                $nombre_receptor = $this->input->post('destino');

                $code = $this->getCodeMail($id);
                // echo '$code<pre>';var_dump( $code );echo '</pre>';die();

                $datos_vista = [
                    'code' => $code ,
                    'subject' => 'Código Verificación Firma',
                    'alt' => 'Correo sin formato'
                ];
                $view = $this->load->view('firma/code', $datos_vista,true);

                // $mail->setTo(array( 'to' => array($email_destino,$nombre_receptor) ) );
                // $mail->setSubject("Código Verificación Firma");
                // $mail->setImage(
                //     array(
                //         array(
                //             'banner' => 'images/index_r1_c1.png'
                //         )
                //     )
                // );
                // $mail->setBody($view);
                // $mail->setAlt("El codigo de verificacion es: ".$code['code']);

                $envio = $mail->enviar([
                    'to'          => $email_destino,
                    'sender_name' => 'Estampillas Pro Boyacá',
                    'subject'     => 'Código Verificación Firma',
                    'body'        => $view,
                    'alt'         => 'El codigo de verificacion es: '.$code['code']
                ]);

                if($envio === true) {
                    $result['message'] = 'El correo electrónico se envió correctamente, por favor verificar el código enviado.';
                    $result['status'] = 1;
                } else {
                    $result['message'] = 'Se presento un problema al enviar el correo.';
                }
            }
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    private function getCodeMail($id)
    {
        $code = [];

        $codigo = $this->getRamdonCode();

        date_default_timezone_set('America/Bogota');
        $today = date('Y-m-d H:i:s');
        $expira = strtotime('+1 hour', strtotime($today));
        $expira = date('Y-m-d H:i:s', $expira);

        $result = $this->codegen_model->get(
            'codigo_firma',
            '*',
            'id_usuario_firma = '.$id,
            1,NULL,true
        );

        if (isset($result->id))
        {
            $idCode = $result->id;

            $this->codegen_model->edit(
                'codigo_firma',
                [
                    'codigo' => $codigo,
                    'generado' => $today,
                    'expira' => $expira,
                ],
                'id', $idCode
            );

            $code = ['code' => $codigo, 'id' => $idCode];
        } else {
            $guardo = $this->codegen_model->add('codigo_firma', [
                'id_usuario_firma'  => $id,
                'codigo'            => $codigo,
                'generado'          => $today,
                'expira'            => $expira,
            ]);

            $code = ['code' => $codigo, 'id' => $guardo->idInsercion];
        }
        return $code;
    }

    private function getRamdonCode()
    {
        return rand(100000, 999999);
    }

    private function signProcess()
    {
        $response = (object)[
            'state' => false,
            'message' => 'No se puede firmar el elemento',
            'url' => null
        ];

        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index'))
            {
                # Obtenemos información de la firma
                $info = $this->getInfoSignUser($this->input->post('firma_id'));

                # Verificamos la clave
                if ($this->verifyPassword($this->input->post('clave_firma'), $info['password']))
                {
                    # Verificamos que el codigo este activo y sea correcto
                    $band = $this->verifyCodeMail($this->input->post('codigo_v'), $this->input->post('firma_id'));

                    if ($band['state'])
                    {
                        # Verificamos que se aceptaron los terminos
                        if (isset($accept))
                        {
                            # Verificamos si la referencia ya esta creada
                            $elemento = $this->setElemento($this->input->post('referencia'));
                            if (isset($elemento['id']))
                            {
                                # Relacionamos el elemento a la firma
                                $firma = $this->setFirmaElemento($elemento['id'], $info['id'], $info['key_hash']);
                                if ($firma->state)
                                {
                                    $response->state = true;

                                    # Verificamos si ya tiene todas las firmas activas necesarias
                                    if (!$this->getFullSignDeclaracion($elemento['id'], 'declaracion')) {
                                        # Generamos y firmamos el archivo
                                        $url = $this->generadorPlantilla($elemento['id']);
                                        if (!empty($url)) {
                                            $response->state = true;
                                            $response->url = $url;
                                            $response->message = "Se firmo y se genero correctamente el PDF";
                                        }
                                    }
                                }
                                $response->message = $firma->message;
                            } else {
                                $response->message = "Se presento un problema , intente mas tarde.";
                            }
                        } else {
                            $response->message = "Debe aceptar los t&eacute;rminos y condiciones";
                        }
                    } else {
                        $response->message = $band['message'];
                    }
                } else {
                    $response->message = "Clave Incorrecta";
                }
            }
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    /*
    * Metodo para obtener información de un firma de usuario
    */
    private function getInfoSignUser($id)
    {
        $info = [];

        $result = $this->codegen_model->get(
            'usuarios_firma',
            '*',
            'id = "'. $id . '"',
            1,NULL,true
        );
        if (isset($result->id)) {
            $info = (array)$result;
        }
        return $info;
    }

    /* Funciones de verificación */
    private function verifyPassword($key, $hash)
    {
        if (sha1($key) == $hash) {
            return true;
        } else {
            return false;
        }
    }

    private function verifyCodeMail($codigo, $id)
    {
        $response = ['state' => false, 'message' => 'Se presento un error'];

        # Verificamos si el codigo existe
        $result = $this->codegen_model->get(
            'codigo_firma',
            '*',
            'codigo = "'. $codigo . '"
                AND usuario_firma_id = "'. $id .'"',
            1,NULL,true
        );

        if (isset($result->id))
        {
            # Verificamos que el timestamps sea mayor al actual
            date_default_timezone_set('America/Bogota');
            $today = date('Y-m-d H:i:s');
            if ($result->expira > $today) {
                $response['state'] = true;
            } else {
                $response['message'] = 'El Codigo ha expirado';
            }
        } else {
            $response['message'] = 'El codigo no es valido';
        }
        return $response;
    }

    /*
    * Verifica que un elemento exista si no lo crea
    */
    private function setElemento($referencia)
    {
        $info = [];

        # Obtenemos el id del tipo
        $result = $this->codegen_model->get(
            'declaraciones',
            'id',
            'id = "'. $referencia . '"
                AND estado = '. EquivalenciasFirmas::declaracionIniciada(),
            1,NULL,true
        );
        
        if (isset($result->id)) {
            $info = (array)$result;
        }
        return $info;
    }

    /*
    * Metodo que relaciona una firma a un elemento especifico
    */
    private function setFirmaElemento($elemento, $usuario, $hash)
    {
        $response = (object)[
            'state' => false,
            'message' => 'No se ha podido firmar'
        ];

        # Verificamos que no exista una firma previa del usuario en el elemento especifico
        $result = $this->codegen_model->get(
            'elemento_firma',
            '*',
            'id_usuario_firma = "'. $usuario . '"
                AND elemento_id = "'. $elemento .'"
                AND estado = '. Equivalencias::estadoActivo(),
            1,NULL,true
        );

        $info = $this->getInfoSign($usuario);

        $permisos = array_reduce(EquivalenciasFirmas::tiposGrupos(), function($acumulador, $grupo){
            return array_merge($acumulador, $grupo);
        }, []);

        if (in_array($info['firma']['tipo'], $permisos))
        {
            if (!isset($result->id))
            {
                $guardo = $this->codegen_model->add('elemento_firma', [
                    'elemento_id'       => $elemento,
                    'usuario_firma_id'  => $usuario,
                    'key_hash'          => $hash,
                    'fecha'             => date('Y-m-d H:i:s'),
                ]);

                if ($guardo->bandRegistroExitoso)
                {
                    $message = $this->getMessageFirma($guardo->idInsercion, $info['firma'], $info['usuario']);
                    $response->state = true;
                    $response->message = $message;
                } else {
                    $response->message = 'Se Presento un problema al procesar la firma, intente m&aacute;s tarde.';
                }
            } else {
                $response->message = 'La persona ya ha realizado la firma anteriormente en el elemento.';
            }
        } else {
            $response->message = 'Usted no tiene permisos para firmar esta declaracion';
        }

        return $response;
    }

    /*
    * Metodo que retornar los mensajes amigables al procesar una firma
    */
    private function getMessageFirma($id_elemento_firma, $firma, $usuario)
    {
        $message = 'Se ha generado la firma correctamente';

        $full = $this->getFullSignDeclaracion($id_elemento_firma);
        if (!$full) {
            $message = '<p>Las firmas de la declaraci&oacute;n se han completado y el archivo se ha generado.</p> La pagina se actualizar&aacute; en pocos segundos...';
        } else {
            $text = $this->getFaltantes($firma['tipo']);
            $message = "<p>La  declaraci&oacute;n ha sido firmada por " . $usuario['first_name'] . " " . $usuario['last_name'] . " como " . $firma['tipo_nombre'] . " de la empresa. <br>Para generar la declaraci&oacute;n electr&oacute;nica tambien debe firmar el " . $text . "<br><br>La pagina se actualizara en los pr&oacute;ximos segundos...</p>";
        }

        return $message;
    }

    /*
    * Metodo que permite verificar si el elemento contiene todas las firmas requeridas para generar el archivo
    */
    private function getFullSignDeclaracion($elemento)
    {
        $band = true;

        $result = $this->codegen_model->get(
            'elemento_firma',
            'COUNT(*) AS total',
            'elemento_id = "'. $elemento .'"
                AND estado = '. Equivalencias::estadoActivo(),
            1,NULL,true
        );

        if (isset($result->total)) {
            if (intval($result->total) >= count(EquivalenciasFirmas::tiposGrupos())) {
                $band = false;
            }
        }

        return $band;
    }

    private function getFaltantes($tipo_usuario)
    {
        $text = '';
        $nombres = EquivalenciasFirmas::tiposGruposNombres();

        foreach(EquivalenciasFirmas::tiposGrupos() AS $indicador => $grupo){
            if(!in_array($tipo_usuario, $grupo)) {
                $text = $nombres[$indicador];
                break;
            }
        }

        return $text;
    }
}
