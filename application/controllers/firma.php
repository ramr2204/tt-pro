<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   @author            David Mahecha
*   @version           2021-10-20
*
*/

require_once APPPATH.'/libraries/barcodegen/class/BCGFontFile.php';
require_once APPPATH.'/libraries/barcodegen/class/BCGColor.php';
require_once APPPATH.'/libraries/barcodegen/class/BCGDrawing.php';
require_once APPPATH.'/libraries/barcodegen/class/BCGgs1128.barcode.php';

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

    /**
     * Muestra el cambio de clave o el formulario para firmar
     * 
     * @return null
     */
    public function renderSignDeclaracion()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/firmar'))
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
                }
                $this->load->view('firma/'.$vista, $datos); 
            } else {
                redirect(base_url().'index.php/error_404');
            }
        } else {
            redirect(base_url().'index.php/users/login');
        }
    }

    /**
     * Metodo para obtener información de un firma de usuario
     * 
     * @param int $id
     * @return array
     */
    private function getInfoSign($id)
    {
        $info = [];

        # Consultamos el registro de la firma
        $result_sign = $this->codegen_model->get(
            'usuarios_firma',
            'id, id_usuario, estado, tipo, created_at',
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

    /**
     * Metodo que recupera la informacion de la empresa
     * 
     * @param int $id_empresa
     * @return object|array
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

    /**
     * Metodo que verifica segun el numero de documento si el usuario puede firmar la declaracion
     * 
     * @param array $param
     * @return object
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

        $tipos_grupos = EquivalenciasFirmas::tiposGrupos();

        # Todos los tipos de usuarios seran necesarios
        $permisos = array_reduce($tipos_grupos, function($acumulador, $grupo){
            return array_merge($acumulador, $grupo);
        }, []);

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

    /**
     * Obtene la declaracion y todas las firmas de la misma
     * 
     * @param int $referencia
     * @param bool $validacion_estado
     * @return array
     */
    private function getElemento($referencia, $validacion_estado=true)
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
                    u.id_empresa, u.first_name, u.last_name,
                    firma.estado',
                'WHERE firma.id_declaracion = "'. $referencia .'"'
                    . ($validacion_estado ? ' AND firma.estado = 1' : ''),
                'INNER JOIN usuarios_firma u_firma ON u_firma.id = firma.id_usuario_firma
                    INNER JOIN users u ON u.id = u_firma.id_usuario',
                '',
                'ORDER BY tipo_usuario ASC'
            );

            $tipos_grupos = EquivalenciasFirmas::tiposGrupos();

            $firmas = array_map(function($grupo){
                return false;
            }, $tipos_grupos);

            # Recorremos las firmas y obtenemos información de los firmantes
            if (count($result_all) >  0)
            {
                foreach ($result_all as $item)
                {
                    $item = (array)$item;
                    $info = [ 'empresa' => $this->getInfoEmpresa($item['id_empresa']) ];

                    foreach($tipos_grupos AS $indice => $grupo) {
                        if(in_array($item['tipo_usuario'], $grupo)) {
                            $firmas[$indice] = true;
                            $item['grupo'] = $indice;
                        }
                    }

                    $item['label'] = $this->getLabelType($item['tipo_usuario']);
                    $content = array_merge($item, $info);
                    $response['info'][$item['id']] = $content;
                    $response['info'][$item['id']]['code'] = $this->genereteCodeBarSign($item['created_at'], $item['id_usuario']);
                }
                $response['firmas'] = $firmas;
            }
        }

        return $response;
    }

    /**
     * Retorna el nombre de un tipo de usuario de firma electronica
     * 
     * @param int $type
     * @return string
     */
    private function getLabelType($type)
    {
        $tipos_usuarios = EquivalenciasFirmas::tiposUsuarios();
        
        $label = $tipos_usuarios[$type];

        return $label ? $label : 'N/A';
    }

    /**
     * Genera el string usado para el codigo de barras
     * 
     * @param string $datetime
     * @param int $cod
     * @return string
     */
    private function genereteCodeBarSign($datetime, $cod)
    {
        $code = '';

        # Establecemos el nuevo formato del datetime
        $date = new DateTime($datetime);
        $code = $date->format('YmdHis');

        # Establecemos la longitud del codigo, se rellena para que quede dos digitos
        $long = str_pad(strlen($cod), 2, '0', STR_PAD_LEFT);

        $code = $long . $cod . $code;

        # Generamos dos numeros aleatorios al final
        $random = mt_rand(10, 99);
        $code = $code . $random;

        return $code;
    }

    /**
     * Procesa el envio del codigo de verificacion por email
     * 
     * @return null
     */
    public function sendMail()
    {
        $result = [
            'status' => 0,
            'message' => '',
        ];

        if ( $this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/firmar')) )
        {
            $this->load->helper('EnvioCorreoHelper');
            $mail = new EnvioCorreoHelper();

            $id = $this->input->post('id');
            $email_destino = $this->input->post('mail');
            $nombre_receptor = $this->input->post('destino');

            $code = $this->getCodeMail($id);

            $datos_vista = [
                'code' => $code ,
                'subject' => 'Código Verificación Firma',
                'alt' => 'Correo sin formato'
            ];
            $view = $this->load->view('firma/code', $datos_vista,true);

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

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Genera el codigo que se enviara por email
     * 
     * @param int $id
     * @return array
     */
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

    /**
     * Genera un numero aleatorio
     * 
     * @return int
     */
    private function getRamdonCode()
    {
        return rand(100000, 999999);
    }

    /**
     * Procesa la firma de un usuario
     * 
     * @param null
     */
    public function signProcess()
    {
        $response = (object)[
            'state' => false,
            'message' => 'No se puede firmar el elemento',
            'url' => null
        ];

        if ( $this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/firmar')) )
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
                    $accept = $this->input->post('accept');

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
                                if (!$this->getFullSignDeclaracion($elemento['id']))
                                {
                                    # Generamos y firmamos el archivo
                                    $url = $this->generadorPlantilla($elemento['id']);
                                    if (!empty($url)) {
                                        $response->state = true;
                                        $response->url = $url;
                                        $response->message = 'Se firmo y se genero correctamente el PDF';
                                    }
                                }
                            }
                            $response->message = $firma->message;
                        } else {
                            $response->message = 'Se presento un problema , intente mas tarde.';
                        }
                    } else {
                        $response->message = 'Debe aceptar los t&eacute;rminos y condiciones';
                    }
                } else {
                    $response->message = $band['message'];
                }
            } else {
                $response->message = 'Clave Incorrecta';
            }
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Metodo para obtener información de un firma de usuario
     * 
     * @param int $id
     * @return array
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

    /**
     * Funciones de verificación
     * 
     * @param string $key
     * @param string $hash
     * @return bool
     */
    private function verifyPassword($key, $hash)
    {
        if (sha1($key) == $hash) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funciones de verificación del codigo enviado por correo
     * 
     * @param int $codigo
     * @param int $id
     * @return array
     */
    private function verifyCodeMail($codigo, $id)
    {
        $response = ['state' => false, 'message' => 'Se presento un error'];

        # Verificamos si el codigo existe
        $result = $this->codegen_model->get(
            'codigo_firma',
            '*',
            'codigo = "'. $codigo . '"
                AND id_usuario_firma = "'. $id .'"',
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

    /**
     * Verifica que un elemento exista si no lo crea
     * 
     * @param int $referencia
     * @return array
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

    /**
     * Metodo que relaciona una firma a un elemento especifico
     * 
     * @param int $elemento
     * @param int $usuario
     * @param string $hash
     * @return object
     */
    private function setFirmaElemento($elemento, $usuario, $hash)
    {
        $response = (object)[
            'state' => false,
            'message' => 'No se ha podido firmar',
        ];

        # Verificamos que no exista una firma previa del usuario en el elemento especifico
        $result = $this->codegen_model->get(
            'elemento_firma',
            '*',
            'id_usuario_firma = "'. $usuario . '"
                AND id_declaracion = "'. $elemento .'"
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
                    'id_declaracion'    => $elemento,
                    'id_usuario_firma'  => $usuario,
                    'key_hash'          => $hash,
                    'fecha'             => date('Y-m-d H:i:s'),
                ]);

                if ($guardo->bandRegistroExitoso)
                {
                    $message = $this->getMessageFirma($elemento, $info['firma'], $info['usuario']);
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

    /**
     * Metodo que retornar los mensajes amigables al procesar una firma
     * 
     * @param int $id_declaracion
     * @param array $firma
     * @param array $usuario
     * @return string
     */
    private function getMessageFirma($id_declaracion, $firma, $usuario)
    {
        $message = 'Se ha generado la firma correctamente';

        $full = $this->getFullSignDeclaracion($id_declaracion);
        if (!$full) {
            $message = '<p>Las firmas de la declaraci&oacute;n se han completado y el archivo se ha generado.</p> La pagina se actualizar&aacute; en pocos segundos...';
        } else {
            $text = $this->getFaltantes($firma['tipo']);
            $message = "<p>La  declaraci&oacute;n ha sido firmada por " . $usuario['first_name'] . " " . $usuario['last_name'] . " como " . $firma['tipo_nombre'] . " de la empresa. <br>Para generar la declaraci&oacute;n electr&oacute;nica tambien debe firmar el " . $text . "<br><br>La pagina se actualizara en los pr&oacute;ximos segundos...</p>";
        }

        return $message;
    }

    /**
     * Metodo que permite verificar si el elemento contiene todas las firmas requeridas para generar el archivo
     * 
     * @param int $elemento
     * @return bool
     */
    private function getFullSignDeclaracion($elemento)
    {
        $band = true;

        $result = $this->codegen_model->get(
            'elemento_firma',
            'COUNT(*) AS total',
            'id_declaracion = "'. $elemento .'"
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

    /**
     * Identifica los usuarios que hacen falta para completar el firmado
     * 
     * @param int $tipo_usuario
     * @return string
     */
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

    /**
     * Metodo de generacion de archivos
     * 
     * @param int $elemento
     * @return string
     */
    private function generadorPlantilla($elemento)
    {
        # Agregamos información de firma
        $signData = [];

        /*Generamos el registro*/
        $fileN = $this->addFileSign(['elemento' => $elemento, 'ruta' => 'ruta']);
        $signData['FILE_ID'] = $this->getHashString($fileN['id']);

        // $qr_filename = $this->generateQR($signData['FILE_ID']);

        # Obtenemos firmas del elemento
        $info = $this->getElemento($elemento);
        $firmas = [];

        foreach ($info['info'] as $key => $item)
        {
            $signData['SIGN_' . $item['id'] . '_' . $elemento] = $item['key_hash'];

            $nombre = $item['first_name'] . ' ' . $item['last_name'];

            $firmas[] = [
                'nombre' => $nombre,
                'documento' => $item['id_usuario'],
                'hash' => $item['key_hash'],
                'code' => $item['code']
            ];

            $this->generateCodeBarSign($item['code'], '942', 'PNG');
        }

        # Generamos el archivo
        $this->load->library('../controllers/declaraciones');
        $url = $this->declaraciones->generarPdf($elemento, $signData, $info);

        # Actualizamos el registro del archivo
        $fileU = $this->addFileSign(['elemento' => $elemento, 'ruta' => $url]);

        $this->clearTempImg();

        if (isset($fileU['id']))
        {
            # Actualizamos el estado de la declaracion a firmada
            $this->codegen_model->edit(
                'declaraciones',
                [ 'estado' => EquivalenciasFirmas::declaracionFirmada() ],
                'id', $elemento
            );

            return $url;
        } else {
            return "";
        }
    }

    /**
     * Se recibe un vector como unico parametro el cual debe contener los siguientes campos
     *
     * elemento      =>   ID Elemento
     * ruta          =>   Ruta del archivo
     * checksum      =>   Hash para validacion de cambios Checksum
     * 
     * @param array $info
     * @return array
    */

    private function addFileSign($info)
    {
        $response = [];

        # Convertimos el vector es variables individuales
        foreach ($info as $key => $value) {
            $$key = $value;
        }
        $bandInsert = false;
        $bandUpdate = false;
        $hash = 'hash';
        $checksum = 'checksum';

        # Verificamos si ya existe el elemento en algun archivo
        $result = $this->codegen_model->get(
            'archivo_firma',
            '*',
            'id_declaracion = "'. $elemento .'"
                AND estado = '. Equivalencias::estadoActivo(),
            1,NULL,true
        );

        if (isset($result->id)) {
            $bandUpdate = true;
        } else {
            $bandInsert = true;
        }

        if ($bandInsert)
        {
            $guardo = $this->codegen_model->add('archivo_firma', [
                'id_declaracion'    => $elemento,
                'fecha'             => date('Y-m-d H:i:s'),
                'ruta_file'         => $ruta,
                'checksum'          => 'checksum',
                'hash_file'         => 'hash',
            ]);

            $response['id'] = $guardo->idInsercion;
            $response['elemento_id'] = $elemento;
            $response['ruta_file'] = $ruta;
        }

        if ($bandUpdate)
        {
            # Generamos el Hash del Id del archivo
            $id = $result->id;
            $hash = $this->getHashString($id);

            # Generamos el checksum de la fila
            $checksum = $this->getHashFile($ruta);

            # Actualizamos el registro
            $this->codegen_model->edit(
                'archivo_firma',
                [
                    'checksum' => $checksum,
                    'hash_file' => $hash,
                    'ruta_file' => $ruta,
                ],
                'id', $id
            );

            $response['id'] = $id;
            $response['elemento_id'] = $elemento;
            $response['ruta_file'] = $ruta;
            $response['hash_file'] = $hash;
            $response['checksum'] = $checksum;
        }
        return $response;
    }

    /**
     * Genera un Hash de un string
     * 
     * @param string $data
     * @return string
     */
    public function getHashString($data)
    {
        return hash("sha256", $data);
    }

    /**
     * Funcion que genera un hash de un archivo
     * 
     * @param string $url
     * @return string|null
     */
    private function getHashFile($url)
    {
        //Verificamos si el archivo existe
        if (file_exists($url)) {
            return hash_file("sha256", $url);
        }
        return null;
    }

    /**
     * Genera las imagenes de los codigos de barras
     * 
     * @param string $hash
     * @param string $ind
     * @param string $type
     * @param any $setStart
     * @return null
     */
    private function generateCodeBarSign($hash, $ind = '', $type, $setStart = null)
    {
        $colorFront = new BCGColor(0, 0, 0);
        $colorBack = new BCGColor(255, 255, 255);
        //Tipo Imagen
        switch ($type) {
            case 'PNG':
                $format = BCGDrawing::IMG_FORMAT_PNG;
                $ext = '.png';
                break;
            case 'JPEG':
                $format = BCGDrawing::IMG_FORMAT_JPEG;
                $ext = '.jpeg';
                break;
        }

        # Barcode Part
        $code = new BCGgs1128();
        if (!is_null($setStart)) {
            $code->setStart('C');
        }
        $code->setScale(2);
        $code->setThickness(30);
        $code->setForegroundColor($colorFront);
        $code->setBackgroundColor($colorBack);

        $code->setStrictMode(true);
        if (empty($ind)) {
            $code->parse($hash);
        } else {
            $code->parse($ind . $hash);
        }

        $dir = 'uploads/temporal';
        if (!file_exists($dir)) {
            mkdir($dir, 0755);
        }

        // Drawing Part
        $drawing = new BCGDrawing($dir . '/' . $hash . $ext, $colorBack);
        $drawing->setBarcode($code);
        $drawing->setDPI(72);
        $drawing->draw();
        //header('Content-Type: image/png');
        $drawing->finish($format);
    }

    /**
     * Limpia la carpeta temporal
     * 
     * @return null
     */
    private function clearTempImg()
    {
        $files = glob('uploads/temporal/*'); //obtenemos todos los nombres de los ficheros
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file); //elimino el fichero
        }
    }

    /**
     * Renderiza el listado de las firmas
     * 
     * @return null
     */
    public function obtenerFirmas()
    {
        if ( $this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/liberarFirmas')) )
        {
            $info = $this->getElemento($this->input->post('codigo'), false);
            $info['id_declaracion'] = $this->input->post('codigo');

            $this->load->view('firma/firmas', $info); 
        }
    }

    /**
     * Procesa la inactivacion de una firma y si es necesario anula el estado firmado
     * 
     * @return null
     */
    public function liberarFirma()
    {
        $message = '';

        if ( $this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/liberarFirmas')) )
        {
            $result = $this->codegen_model->get(
                'elemento_firma',
                '*',
                'id = '.$this->input->post('codigo'),
                1,NULL,true
            );

            if (isset($result->id_declaracion))
            {
                $elemento = $result->id_declaracion;

                # Actualizamos el estado
                $update = $this->codegen_model->edit(
                    'elemento_firma',
                    [ 'estado' => 0 ],
                    'id', $this->input->post('codigo'),
                    true
                );

                if ($update > 0)
                {
                    $message .= 'La firma fue liberado correctamente<br>';

                    # Verificamos si existe un archivo
                    $result_file = $this->codegen_model->get(
                        'archivo_firma',
                        'id',
                        'id_declaracion = "'. $elemento .'"
                            AND estado = 1',
                        1,NULL,true
                    );

                    if (isset($result_file->id))
                    {
                        # Inactivamos el archivo
                        $update = $this->codegen_model->edit(
                            'archivo_firma',
                            [ 'estado' => 0 ],
                            'id', $result_file->id,
                            true
                        );

                        # Se retorna la declaracion a inicializada
                        $this->codegen_model->edit(
                            'declaraciones',
                            [ 'estado' => EquivalenciasFirmas::declaracionIniciada() ],
                            'id', $elemento
                        );

                        if ($update > 0) {
                            $message .= 'El archivo fue invalidado.<br>';
                        }
                    }
                } else {
                    $message .= 'Se presento un problema, no se pudo liberar la firma.<br>';
                }
            }
        }

        echo $message;
    }

    /**
     * Vista para comprobar el codigo de barras de la firma
     * 
     * @return null
     */
    public function consultarFirma()
    {
        $this->template->load($this->config->item('admin_template'),'firma/comprobarFirma', $this->data);
    }

    /**
     * Procesa la consulta del codigo de barras de la firma
     * 
     * @return null
     */
    public function searchSign()
    {
        $info = [];
        $codigo = $this->input->post('value');

        if (!empty($codigo))
        {
            $data = $this->getDataFromCode($codigo);
            $id = isset($data['codigo']) ? $data['codigo'] : null;

            $result_user = $this->codegen_model->get(
                'usuarios_firma',
                '*',
                'id_usuario = '.$id,
                1,NULL,true
            );

            if (isset($result_user->id)) {
                $info = $this->getInfoSign($result_user->id);
            }
        }

        $this->load->view('firma/datosFirmante', ['info' => $info]); 
    }

    /**
     * Se obitiene la informacion del codigo de la firma
     * 
     * @param string $value
     * @return array
     */
    private function getDataFromCode($value)
    {
        $data = [];

        # Quitamos el primer digito de verificación
        $findme   = '942';
        $pos = strpos($value, $findme);

        if ($pos !== false)
        {
            # Eliminamos caracters anteriores al codigo de identificación
            $value = substr($value, $pos + strlen($findme));

            # Obtenemos longitud del codigo y le quitamos los ceros de la izquierda
            $long = ltrim(substr($value, 0, 2), '0');

            # Obtenemos el codigo de la firma
            $value = substr($value, 2);
            $codigo = substr($value, 0, $long);
            $data['codigo'] = $codigo;
            $value = substr($value, $long);

            # Obtenemos la fecha de creación longitud default de 14
            $fecha = substr($value, 0, 14);
            if ($this->validateStringDate($fecha))
            {
                $date = new DateTime($fecha);

                if ($date)
                {
                    $fecha = $date->format('Y-m-d H:i:s');
                    $data['fecha'] = $fecha;

                    # Obtenemos el valor aleatorio de control
                    $value = substr($value, 14);
                    $data['control'] = $value;
                }
            } else {
                $data = [];
            }
        }
        return $data;
    }

    /**
     * Valida la fecha convertida
     * 
     * @param string $str_date
     * @return bool
     */
    private function validateStringDate($str_date)
    {
        $band = true;

        if (strlen($str_date) == 14)
        {
            $formats = [
                [4, 2000, 2030],
                [2, 01, 12],
                [2, 01, 31],
                [2, 00, 24],
                [2, 00, 60],
                [2, 00, 60],
            ];

            foreach ($formats as $item)
            {
                $check = substr($str_date, 0, $item[0]);
                $str_date = substr($str_date, $item[0]);

                if (intval($check) < $item[1] || intval($check) > $item[2]) {
                    $band = false;
                }
            }
        }
        return $band;
    }
}
