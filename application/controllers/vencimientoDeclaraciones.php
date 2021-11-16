<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   @author            David Mahecha
*   @version           2021-10-20
*
*/

class VencimientoDeclaraciones extends MY_Controller
{
    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation','Pdf');
        $this->load->model('codegen_model','',TRUE);
        
        $this->load->helper(['form','url','codegen_helper', 'array']);
    }

    /**
     * Lista todas los vencimientos
     * 
     * @return null
     */
    public function index()
    {
    	if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('users/login', 'refresh');
		}
		elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_menu('vencimientoDeclaraciones/index'))) //remove this elseif if you want to enable this for non-admins
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
            $this->template->set('title', 'Administrar vencimientos');
            $this->data['style_sheets'] = [];
            $this->data['javascripts'] = [
                'js/sweetalert.min.js',
            ];

            $this->data['vencimientos'] = $this->codegen_model->getSelect(
                'vencimiento_declaraciones',
                'id, ultimo_digito, dia, modificado',
                '', '', '',
                'ORDER BY ultimo_digito'
            );

			$this->template->load($this->config->item('admin_template'),'declaraciones/vencimiento', $this->data);
		}
    }

    /**
     * Modifica los dias del vencimiento
     * 
     * @return null
     */
    public function editar()
    {
        $respuesta = [
            'exito' => false,
            'errores' => '',
            'modificado' => ''
        ];

        $this->form_validation->set_rules('id', 'Identificador','required|trim|xss_clean|is_exists[vencimiento_declaraciones.id]');
        $this->form_validation->set_rules('dia', 'Día del mes','required|trim|xss_clean|integer|greater_than[0]|less_than[16]');

        if ($this->form_validation->run() == false) {
            $respuesta['errores'] = (validation_errors() ? validation_errors() : false);
        } else {
            $fecha = date('Y-m-d H:i:s');

            $this->codegen_model->edit(
                'vencimiento_declaraciones',
                [
                    'dia' => $this->input->post('dia'),
                    'modificado'  => $fecha,
                ],
                'id', $this->input->post('id')
            );

            $respuesta['exito'] = true;
            $respuesta['modificado'] = $fecha;
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    }

}
