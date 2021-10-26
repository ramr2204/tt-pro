<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   @author            Monica Guitierrez
*   @version           2021-10-13
*
*/

class Empresas extends MY_Controller 
{
	function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('HelperGeneral');
        //$this->load->helper('MYPDF');
        $this->load->helper(array('form','url','codegen_helper'));
        $this->load->model('codegen_model','',TRUE);
    }

	/**
	 * Muestra el listado de empresas
	 * 
	 * @return null
	 */
    function index()
    {
    	if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('users/login', 'refresh');
		}
		elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_menu('empresas/index'))) //remove this elseif if you want to enable this for non-admins
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
            $this->template->set('title', 'Usuarios');
            $this->data['style_sheets']= array(
                    'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                   );
            $this->data['javascripts']= array(
                    'js/jquery.dataTables.min.js',
                    'js/plugins/dataTables/dataTables.bootstrap.js',
                    'js/jquery.dataTables.defaults.js'
                   );
            $this->data['successmessage']=$this->session->flashdata('successmessage');
            $this->data['message'] = $this->session->flashdata('message');
			$this->template->load($this->config->item('admin_template'),'empresas/index', $this->data);
		}
    }

	/**
	 * Retorna datos para el procesamiento del dataTable
	 * 
	 * @return null
	 */
    function dataTable()
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('empresas/index'))
        {
            $this->load->library('datatables');
            $this->datatables->select('id,nit,nombre,email,direccion,telefono,id_municipio,nombre_representante,identificador_representante,estado');
            $this->datatables->from('empresas');
            $this->datatables->add_column('edit', '<div class="btn-toolbar" role="toolbar">
					<div class="btn-group">
						<a href="'.base_url().'empresas/edit/$1" class="btn btn-default btn-xs" title="Editar datos de usuario"><i class="fa fa-pencil-square-o"></i></a>
					</div>
				</div>', 'id');
         	echo $this->datatables->generate();
        }
        else
        {
          	redirect(base_url().'index.php/users/login');
        }           
    	
    }

	/**
	 * Renderiza la vista de crear y tambien guarda una empresa
	 * 
	 * @return null
	 */
    function create()
    {
    	if ($this->ion_auth->logged_in())
		{
			$this->data['style_sheets']= array(
            	'css/chosen.css' => 'screen'
            );
            $this->data['javascripts']= array(
            	'js/chosen.jquery.min.js'
            );    

		    if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('empresas/index'))
			{
				$this->data['successmessage'] = $this->session->flashdata('message');

            	$this->data['municipios']  = $this->codegen_model->getMunicipios();

				$this->data['title'] = "Crear Empresa";	
			    //$this->form_validation->set_rules('id', $this->lang->line('create_user_validation_id_label'), 'required|xss_clean|numeric|greater_than[0]|is_unique[users.id]');

			    //validamos los datos del formularioo
			    $this->form_validation->set_rules('nit', 'Nit' ,'required|is_unique[empresas.nit]');
			    $this->form_validation->set_rules('nombre', 'Nombre', 'required');
			    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			    $this->form_validation->set_rules('direccion', 'Direccion', 'required');
			    $this->form_validation->set_rules('telefono', 'Telefono', 'required|numeric');
			    $this->form_validation->set_rules('id_municipio', 'Municipio', 'required'); 
			    $this->form_validation->set_rules('nombre_representante', 'Nombre Representante', 'required'); 
			    $this->form_validation->set_rules('identificador_representante', 'Identificador Representante', 'required|numeric'); 
              
			    //fin validacion

			  	if ($this->form_validation->run() == false)
			 	{
			 		$errormessage = (validation_errors() ? validation_errors(): false);
              		$this->data['errormessage'] = $errormessage;
			 	} 
			 	else 
			 	{
			 		//creamos la empresa
			 		$dataEmpresa = array(
			 			'nit'                  => $this->input->post('nit'),
						'nombre'               => $this->input->post('nombre'),
						'email'                => $this->input->post('email'),
						'direccion'            => $this->input->post('direccion'),
						'telefono'             => $this->input->post('telefono'),
						'id_municipio'         => $this->input->post('id_municipio'),
						'nombre_representante' => $this->input->post('nombre_representante'),
						'identificador_representante' => $this->input->post('identificador_representante'),
						'fecha_creacion'       => date('Y-m-d H:i:s')
			 		);

	                $respuestaProceso = $this->codegen_model->add('empresas',$dataEmpresa);

					$this->session->set_flashdata('message', 'La empresa se ha creado con éxito');
					redirect(base_url().'index.php/empresas/create');
			  	}

				$this->template->load($this->config->item('admin_template'),'empresas/create', $this->data);
			}
			else 
			{
				redirect(base_url().'error_404');
			}
		} 
		else
		{
			redirect(base_url().'empresas/create');
		}
    }

	/**
	 * Renderiza la vista de editar y tambien actualiza una empresa
	 * 
	 * @return null
	 */
	function edit()
	{
      	if ($this->ion_auth->logged_in()) 
      	{
      		$this->data['style_sheets']= array(
            	'css/chosen.css' => 'screen'
            );
            $this->data['javascripts']= array(
            	'js/chosen.jquery.min.js'
            ); 

            $this->data['municipios']  = $this->codegen_model->getMunicipios();

          	if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('empresas/edit')) 
          	{
              	$idempresa = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;

              	if ($idempresa=='')
              	{
                  	$this->session->set_flashdata('infomessage', 'Debe elegir una empresa para editar');
                  	redirect(base_url().'index.php/empresas');
              	}

              	$this->form_validation->set_rules('nit', 'Nit' ,'required|is_unique[empresas.nit.id.'. $idempresa .']');
			    $this->form_validation->set_rules('nombre', 'Nombre', 'required');
			    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			    $this->form_validation->set_rules('direccion', 'Direccion', 'required');
			    $this->form_validation->set_rules('telefono', 'Telefono', 'required|numeric');
			    $this->form_validation->set_rules('id_municipio', 'Municipio', 'required'); 
			    $this->form_validation->set_rules('nombre_representante', 'Nombre Representante', 'required'); 
			    $this->form_validation->set_rules('identificador_representante', 'Identificador Representante', 'required|numeric'); 

              	if ($this->form_validation->run() == false) 
              	{
                  	$this->data['errormessage'] = (validation_errors() ? validation_errors() : false);  
	            }
	            else
	            {
					$data = array(
                        'nit'                  => $this->input->post('nit'),
						'nombre'               => $this->input->post('nombre'),
						'email'                => $this->input->post('email'),
						'direccion'            => $this->input->post('direccion'),
						'telefono'             => $this->input->post('telefono'),
						'id_municipio'         => $this->input->post('id_municipio'),
						'nombre_representante' => $this->input->post('nombre_representante'),
						'identificador_representante' => $this->input->post('identificador_representante'),
						'fecha_creacion'       => date('Y-m-d H:i:s')
                    );

	                if ($this->codegen_model->edit('empresas',$data,'id',$idempresa) == TRUE) 
	                {
                        $this->session->set_flashdata('successmessage', 'La empresa se ha editado con éxito');
                        redirect(base_url().'index.php/empresas/edit/'.$idempresa);
	                } 
	                else 
	                {
	                	$this->data['errormessage'] = 'No se pudo editar la empresa';
	                } 
	            } 

              	$this->data['successmessage']=$this->session->flashdata('successmessage');
              	$this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
            	$this->data['result'] = $this->codegen_model->get('empresas','id,nit,nombre,email,direccion,telefono,id_municipio,nombre_representante,identificador_representante','id = '.$idempresa,1,NULL,true);
              	$this->template->set('title', 'Editar empresa');
              	$this->template->load($this->config->item('admin_template'),'empresas/edit', $this->data);
          	}
          	else 
          	{
              	redirect(base_url().'index.php/error_404');
          	}
      }
      else
      {
          redirect(base_url().'index.php/users/login');
      }
        
  	}

	/**
	 * Cambia de estado las empresas
	 * 
	 * @return null
	 */
  	function delete()
	{
	    if ($this->ion_auth->logged_in()) 
	    {
	        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regimenes/delete')) 
	        {
				$id = $this->uri->segment(3);
				$estado = $this->uri->segment(4);

	            if (!$id || !in_array($estado, [0,1])){
	                $this->session->set_flashdata('infomessage', 'La informacion suministrada es invalida');
	            } else {
					if ($this->codegen_model->edit('empresas',['estado' => $estado], 'id',$id) == true) 
					{
						$this->session->set_flashdata('successmessage', 'La empresa se ha '. ($estado ? 'activado' : 'desactivado') .' con éxito');
					} 
					else
					{
						$this->session->set_flashdata('errormessage', 'No se pudo cambiar de estado la empresa');
					}
				}

				redirect(base_url().'index.php/empresas');
	        } 
	        else 
	        {
	            redirect(base_url().'index.php/error_404');       
	        } 
	    } 
	    else 
	    {
	        redirect(base_url().'index.php/empresas/login');
	    }
	}
}