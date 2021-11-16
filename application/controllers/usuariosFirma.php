<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            contratos
*   Ruta:              /application/controllers/generarpdf.php
*   Descripcion:       controlador de contratos
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class usuariosFirma extends MY_Controller
{
    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation','Pdf');    
        $this->load->model('liquidaciones_model','',TRUE);
        $this->load->model('codegen_model','',TRUE);
        
        $this->load->helper(['form','url','codegen_helper']);
        $this->load->helper(['Equivalencias', 'EquivalenciasFirmas']);
    }

    function index()
    {
    	if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('users/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
		{
			//redirect them to the home page because they must be an administrator to view this
			redirect('error_404', 'refresh');
			
		}
		else
		{
			$this->data['successmessage']	= $this->session->flashdata('successmessage');
			$this->data['errormessage']		= $this->session->flashdata('errormessage');
			$this->data['infomessage']		= $this->session->flashdata('infomessage');

			//template data
            $this->template->set('title', 'Administrar Usuarios Firma');
            $this->data['style_sheets'] = [
                'css/chosen.css' => 'screen',
                'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
            ];
            $this->data['javascripts'] = [
                'js/chosen.jquery.min.js',
                'js/jquery.dataTables.min.js',
                'js/plugins/dataTables/dataTables.bootstrap.js',
                'js/jquery.dataTables.defaults.js',
                'js/axios.min.js',
                'js/sweetalert.min.js',
            ];

            $this->data['tipos_usuarios'] = EquivalenciasFirmas::tiposUsuarios();
            $this->data['estado_activo'] = Equivalencias::estadoActivo();

			$this->template->load($this->config->item('admin_template'),'usuarios_firma/index', $this->data);
		}
    }

    public function dataTable()
    {
        if ($this->ion_auth->is_admin())
        {
            $this->load->library('datatables');
            $this->datatables->select('
                f.id,
                u.id AS documento,
                CONCAT(u.first_name, \' \', u.last_name) AS nombre_completo,
                e.nombre AS empresa,
                u.email,
                f.tipo,
                f.created_at,
                f.estado
            ', false);
            $this->datatables->from('usuarios_firma AS f');
            $this->datatables->join('users u','u.id = f.id_usuario','inner');
            $this->datatables->join('con_contratantes e','e.id = u.id_empresa','inner');

            echo $this->datatables->generate();
        }
        else
        {
          redirect(base_url().'index.php/users/login');
        }
    }

    public function create()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar'))
            {
                $_POST = array_merge($_POST, ($this->session->flashdata('campos') ? $this->session->flashdata('campos') : []) );

                $this->data['successmessage'] = $this->session->flashdata('successmessage');
                $this->data['errormessage']   = $this->session->flashdata('errormessage');
                $this->data['infomessage']    = $this->session->flashdata('infomessage');

                $this->template->set('title', 'Crear usuario firma');

                $this->data['style_sheets'] = [
                    'css/chosen.css' => 'screen',
                    'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen'
                ];
                $this->data['javascripts'] = [
                    'js/chosen.jquery.min.js'
                ];

                $this->store();

                $this->data['empresas'] = $this->codegen_model->getSelect(
                    'con_contratantes',
                    'id, nombre',
                    '', '',
                    'ORDER BY nombre'
                );

                $this->data['tipos_usuarios'] = EquivalenciasFirmas::tiposUsuarios();

                $this->template->load($this->config->item('admin_template'),'usuarios_firma/create', $this->data);
            } else {
                redirect(base_url().'index.php/error_404');
            }
        } else {
            redirect(base_url().'index.php/users/login');
        }
    }

    private function store()
    {
        $this->form_validation->set_rules('empresa', 'Empresa','required|trim|xss_clean|is_exists[con_contratantes.id]');
        $this->form_validation->set_rules('usuario', 'Usuario',[
            'required',
            'trim',
            'xss_clean',
            'is_exists[users.id]',
            'is_unique[usuarios_firma.id_usuario]'
        ]);
        $this->form_validation->set_rules('tipo_usuario', 'Tipo de usuario',
            [
                'required',
                'trim',
                'xss_clean',
                'in_list['. implode(',', array_keys(EquivalenciasFirmas::tiposUsuarios()) ) .']'
            ]
        );

        if ($this->form_validation->run() == false) {
            $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
        } else {
            $guardo = $this->codegen_model->add('usuarios_firma', [
                'id_usuario'    => $this->input->post('usuario'),
                'tipo'          => $this->input->post('tipo_usuario'),
                'password'      => 'password',
                'key_hash'      => $this->input->post('usuario'),
                'created_at'    => date('Y-m-d H:i:s'),
                'creado_por'    => $this->session->userdata('user_id'),
            ]);

            if($guardo->bandRegistroExitoso)
            {
                $this->load->library('../controllers/firma');
                $key_hash = $this->firma->getHashString($guardo->idInsercion . "," . trim($this->input->post('usuario')));

                $edito = $this->codegen_model->edit(
                    'usuarios_firma',
                    ['key_hash' => $key_hash],
                    'id', $guardo->idInsercion
                );

                if ($edito == true)
                {
                    $this->inactivarFirmantesAnteriores($this->input->post('empresa'), $guardo->idInsercion);

                    $this->session->set_flashdata('successmessage', 'El usuario de firma electrónica se ha registrado correctamente');
                    redirect(base_url().'index.php/usuariosFirma/');
                } else {
                    $this->codegen_model->delete(
                        'usuarios_firma',
                        'id', $guardo->idInsercion
                    );
                    $this->data['errormessage'] = 'No se pudo registrar firma';
                }
            } else {
                $this->data['errormessage'] = 'No se pudo registrar el usuario';
            }
        }
    }

    public function buscarUsuariosEmpresa()
    {
        $respuesta = [];

        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar'))
            {
                $id_empresa = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

                $respuesta = $this->codegen_model->getSelect(
                    'users',
                    'id, email AS nombre',
                    'WHERE id_empresa = "'. $id_empresa .'"
                        AND perfilid = "'. Equivalencias::perfilFirmante() .'"
                        AND active = '.Equivalencias::estadoActivo(),
                    '',
                    'ORDER BY nombre'
                );

            }
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($respuesta);
    }

    public function estadoFirma()
    {
        $response = [];

        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar'))
            {
                if ($this->input->post('st') == 1) {
                    $estado = 0;
                } else {
                    $estado = 1;
                }

                $edito = $this->codegen_model->edit(
                    'usuarios_firma',
                    [
                        'estado' => $estado,
                        'update_at' => date('Y-m-d H:i:s')
                    ],
                    'id', $this->input->post('id')
                );

                if ($edito)
                {
                    if($estado == 1)
                    {
                        $usuario = $this->codegen_model->get(
                            'usuarios_firma AS firma',
                            'users.id_empresa',
                            'firma.id = '.$this->input->post('id'),
                            1,NULL,true, '',
                            'users', 'users.id = firma.id_usuario'
                        );
                        $this->inactivarFirmantesAnteriores($usuario->id_empresa, $this->input->post('id'));
                    }

                    $response['id'] = $this->input->post('id');
                    $response['estado'] = $estado;
                }
            }
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function requestChange()
    {
        $response = [];

        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar'))
            {
                if( $this->input->post('id') > 0 )
                {
                    $edito = $this->codegen_model->edit(
                        'usuarios_firma',
                        [ 'change_password' => 1 ],
                        'id', $this->input->post('id')
                    );

                    if ($edito) {
                        $response['id'] = $this->input->post('id');
                        $response['estado'] = '1';
                    }
                }
            }
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    private function inactivarFirmantesAnteriores($id_empresa, $exepcion)
    {
        # Inactiva todos los demas representantes legales o contador/revisor fiscal anteriores
        $tipos_inactivar = '';
        $tipos_usuarios = array_keys(EquivalenciasFirmas::tiposUsuarios());

        if($this->input->post('tipo_usuario') == EquivalenciasFirmas::usuarioRepresentante()) {
            $tipos_inactivar = $this->input->post('tipo_usuario');
        } else {
            # Selecciona todos los usuarios que no son representantes
            $tipos_inactivar = implode(',', array_slice($tipos_usuarios, 1));
        }

        $edito = $this->codegen_model->editWhere(
            'usuarios_firma',
            [
                'estado' => 0,
                'update_at' => date('Y-m-d H:i:s')
            ],
            'tipo IN ('. $tipos_inactivar .')
                AND id_usuario IN (
                    SELECT id FROM users
                    WHERE perfilid = "'. Equivalencias::perfilFirmante() .'"
                        AND id_empresa = "'. $id_empresa .'"
                )
                AND id != "'. $exepcion .'"'
        );
    }

    public function asignarClave()
    {
        $respuesta = [
            'state' => false,
            'message' => ''
        ];

        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index'))
            {
                $this->form_validation->set_rules('clave', 'Clave',[
                    'required',
                    'trim',
                    'xss_clean',
                    'min_length[8]',
                    'length_number[2]',
                    'length_lower[1]',
                    'length_upper[1]',
                ]);
                $this->form_validation->set_rules('confirm', 'Confirmar clave', 'required|trim|xss_clean|matches[clave]');

                if ($this->form_validation->run() == false) {
                    $respuesta['message'] = (validation_errors() ? validation_errors() : false);
                } else {

                    $pass = $this->getHashUserPassword($this->input->post('clave'));

                    //Actualizamos el Hash del Password del usuario
                    $edito = $this->codegen_model->edit(
                        'usuarios_firma',
                        [
                            'password' => $pass,
                            'update_at' => date('Y-m-d H:i:s'),
                            'change_password' => 0
                        ],
                        'id', $this->input->post('codigo')
                    );

                    if($edito){
                        $respuesta['state'] = true;
                        $respuesta['message'] = 'Se actualizo correctamente la contraseña de la firma<br>
                        Inicie Sesión y podrá utilizar su firma electrónica';
                    }else{
                        $respuesta['message'] = 'No se pudo actualizar la contraseña de la firma';
                    }
                }
            }
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($respuesta);
    }

    /**
    * Función que genera el hash de la segunda clave del usuario (Clave para firmar)
    */
    public function getHashUserPassword($key)
    {
        $hash = sha1($key);

        return $hash;
    }
}
