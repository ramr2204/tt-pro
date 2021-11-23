<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   @author            David Mahecha
*   @version           2021-10-20
*
*/

class Notificaciones extends MY_Controller
{
    function __construct() 
    {
        parent::__construct();
        $this->load->model('codegen_model','',TRUE);
        
        $this->load->helper(['url','codegen_helper']);
        $this->load->helper(['EquivalenciasNotificaciones']);
    }

    /**
     * Crea la solicitud de una correccion de declaracion
     * 
     * @return null
     */
    public function listado()
    {
        header('Content-type: application/json; charset=utf-8');

        $respuesta = [
            'exito' => false,
            'mensaje' => ''
        ];

        $porPagina = 10;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

        //redirect them to the login page
        if (!$this->ion_auth->logged_in()) {
			$respuesta['mensaje'] = 'No se encuentra registrado en el aplicativo';
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
		}

        $usuarioLogueado = $this->ion_auth->user()->row();
        $permisos = EquivalenciasNotificaciones::permisos();
        $notificacionesSinEmpresa = EquivalenciasNotificaciones::notificacionesSinEmpresa();

        $tiposNotificaciones = $permisos[$usuarioLogueado->perfilid] ? $permisos[$usuarioLogueado->perfilid] : [0];

        $respuesta['notificaciones'] = $this->codegen_model->getSelect(
            'notificaciones AS n',
            'n.id, n.tipo, SUBSTRING(n.texto, 1, 70) AS texto,
                DATE_FORMAT(n.fecha_creacion, "%m-%d %k:%i") AS fecha',
            'WHERE n.tipo IN ('. implode(',', $tiposNotificaciones) .')
                AND n.id_empresa = (CASE
                    WHEN n.tipo IN ('. implode(',', $notificacionesSinEmpresa) .')
                    THEN n.id_empresa
                    ELSE '. ($usuarioLogueado->id_empresa ? $usuarioLogueado->id_empresa : 0) .'
                END)',
            '', '',
            'ORDER BY n.fecha_creacion DESC',
            'LIMIT '.(($pagina - 1) * $porPagina).','.$porPagina
        );

        $respuesta['descripciones'] = EquivalenciasNotificaciones::descripciones();
        $respuesta['estilos'] = EquivalenciasNotificaciones::estilos();
        $respuesta['exito'] = true;

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Crea la solicitud de una correccion de declaracion
     * 
     * @return null
     */
    public function detalle()
    {
        if ($this->ion_auth->logged_in()) {
            if ($this->uri->segment(3)==''){
                redirect(base_url().'index.php/error_404');
            }

            $this->data['successmessage'] = $this->session->flashdata('message');
            $this->data['infomessage'] = $this->session->flashdata('infomessage');
            $this->data['errormessage']=$this->session->flashdata('errormessage');

            $this->data['style_sheets'] = [ ];
            $this->data['javascripts'] = [
                'js/applicationEvents.js',
            ];

            $usuarioLogueado = $this->ion_auth->user()->row();
            $permisos = EquivalenciasNotificaciones::permisos();
            $notificacionesSinEmpresa = EquivalenciasNotificaciones::notificacionesSinEmpresa();

            $tiposNotificaciones = $permisos[$usuarioLogueado->perfilid] ? $permisos[$usuarioLogueado->perfilid] : [0];

            $this->data['notificacion'] = $this->codegen_model->getSelect(
                'notificaciones AS n',
                'n.id, n.tipo, n.texto,
                    n.fecha_creacion AS fecha, n.adicional',
                'WHERE id = "'. $this->uri->segment(3) .'"
                    AND n.tipo IN ('. implode(',', $tiposNotificaciones) .')
                    AND n.id_empresa = (CASE
                        WHEN n.tipo IN ('. implode(',', $notificacionesSinEmpresa) .')
                        THEN n.id_empresa
                        ELSE '. ($usuarioLogueado->id_empresa ? $usuarioLogueado->id_empresa : 0) .'
                    END)'
            );
            $this->data['notificacion'] = $this->data['notificacion'][0];

            $this->data['descripciones'] = EquivalenciasNotificaciones::descripciones();
            $this->data['estilos'] = EquivalenciasNotificaciones::estilos();

            $this->template->set('title', 'Detalles de la notificación');
            $this->template->load($this->config->item('admin_template'),'notificacion/detalle', $this->data);

        } else {
            redirect(base_url().'index.php/users/login');
        }
    }
}
