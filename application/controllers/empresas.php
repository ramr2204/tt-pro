<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   VNombre:            cuantias
*   Ruta:              /application/controllers/cuantias.php
*   Descripcion:       controlador de cuantias
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
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

    function dataTable()
    {
        if ($this->ion_auth->is_admin())
        {
            $this->load->library('datatables');
            $this->datatables->select('id,nit,nombre,email,direccion,telefono,id_municipio,nombre_representante,identificador_representante');
            $this->datatables->from('empresas');
            //$this->datatables->join('adm_perfiles p','p.perf_id = u.perfilid','left');
            $this->datatables->add_column('edit', '<div class="btn-toolbar" role="toolbar">
	                                           <div class="btn-group">
	                                            <a href="'.base_url().'empresas/edit/$1" class="btn btn-default btn-xs" title="Editar datos de usuario"><i class="fa fa-pencil-square-o"></i></a>
	                                            <a href="'.base_url().'users/permisos/$1" class="btn btn-default btn-xs" title="Editar permisos predeterminados"><i class="fa fa-eye"></i></a>
	                                           </div>
	                                       </div>', 'id');
         echo $this->datatables->generate();
        }
        else
        {
          redirect(base_url().'index.php/users/login');
        }           
    	
    }

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

		    if ($this->ion_auth->is_admin())
			{

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
              
			    //fin validacionn
			 		/*var_dump('aaa');exit();*/

			  	if ($this->form_validation->run() == false)
			 	{
			 		$errormessage = (validation_errors() ? validation_errors(): false);
              		$this->data['errormessage'] = $errormessage;
					$this->session->set_flashdata('errormessage', $errormessage);
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
					$this->session->set_flashdata('successmessage', 'La empresa se ha creado con éxito');
					/*var_dump($this->session);exit();*/
			  	}

				$this->load->model('codegen_model','',TRUE); 
				/*$this->data['perfiles']  = $this->codegen_model->getSelect('adm_perfiles','perf_id,perf_nombre');*/
		      	$this->data['successmessage']=$this->session->flashdata('successmessage');
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

          		//var_dump($idempresa);exit();
              	if ($idempresa=='')
              	{
                  	$this->session->set_flashdata('infomessage', 'Debe elegir un tipo de régimen para editar');
                  	redirect(base_url().'index.php/empresas');
              	}

              	$this->form_validation->set_rules('nit', 'Nit' ,'required|is_unique[empresas.nit]');
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
                  	//var_dump($this->data['errormessage']);exit();
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

                    //var_dump($data);exit();
	                           
	                if ($this->codegen_model->edit('empresas',$data,'id',$idempresa) == TRUE) 
	                {
                        /*
                        * Actualiza el nombre del regimen en las posibles liquidaciones de los contratos
                        */
                        $where = 'id = '.$idempresa;                              

                        $this->codegen_model->editWhere('empresas',$datos,$where);

                        $this->session->set_flashdata('successmessage', 'La empresa se ha editado con éxito');
                        redirect(base_url().'index.php/empresas/edit/'.$idempresa);
	                } 
	                else 
	                {
	                	$this->data['errormessage'] = 'No se pudo registrar el aplilo';
	                } 
	            } 

              	$this->data['successmessage']=$this->session->flashdata('successmessage');
              	$this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
            	$this->data['result'] = $this->codegen_model->get('empresas','id,nit,nombre,email,direccion,telefono,id_municipio,nombre_representante,identificador_representante','id = '.$idempresa,1,NULL,true);
              	$this->template->set('title', 'Editar régimen');
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

  	function delete()
	{
	    if ($this->ion_auth->logged_in()) 
	    {
	        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regimenes/delete')) 
	        { 
	            if ($this->input->post('id')==''){
	                $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de régimen para eliminar');
	                redirect(base_url().'index.php/empresas');
	            }
	            if (!$this->codegen_model->depend('con_contratistas','cont_regimenid',$this->input->post('id'))) 
	            {
	                $this->codegen_model->delete('empresas','regi_id',$this->input->post('id'));
	                $this->session->set_flashdata('successmessage', 'La empresa se ha eliminado con éxito');
	                redirect(base_url().'index.php/empresas');  

	            } 
	            else 
	            {
	                $this->session->set_flashdata('errormessage', 'El régimen se encuentra en uso, no es posible eliminarlo.');
	                redirect(base_url().'index.php/empresas/edit/'.$this->input->post('id'));

	            }                 
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