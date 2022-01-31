<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            contratantes
*   Ruta:              /application/controllers/contratantes.php
*   Descripcion:       controlador de contratantes
*   Fecha Creacion:    18/dic/2018
*   @author            Michael Angelo Ortiz Trivinio <engineermikeortiz@gmail.com>
*   @version           2018-12-18
*
*/

class Contratantes extends MY_Controller {
    
    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('HelperGeneral');
        $this->load->helper(array('form','url','codegen_helper'));
        $this->load->model('codegen_model','',TRUE);
    }	
	
    function index()
    {
        $this->manage();
    }

    function manage()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/manage'))
            {
                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage']=$this->session->flashdata('errormessage');
                $this->data['infomessage']=$this->session->flashdata('infomessage');

                //template data
                $this->template->set('title', 'Administrar contratantes');
                $this->data['style_sheets']= array(
                'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                );

                $this->data['javascripts']= array(
                'js/jquery.dataTables.min.js',
                'js/plugins/dataTables/dataTables.bootstrap.js',
                'js/jquery.dataTables.defaults.js'
                );

                $this->template->load($this->config->item('admin_template'),'contratantes/contratantes_list', $this->data);    
            }else
            {
                redirect(base_url().'index.php/error_404');
            }
        }else
        {
            redirect(base_url().'index.php/users/login');
        }    
    }
 
	function add()
    {
        if ($this->ion_auth->logged_in()) 
        { 
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/add')) {
                $this->data['successmessage']=$this->session->flashdata('message');  
                $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
                $this->form_validation->set_rules('nit', 'NIT', 'required|numeric|trim|xss_clean|max_length[100]|is_unique[con_contratantes.nit]');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			    $this->form_validation->set_rules('direccion', 'Direccion', 'required');
			    $this->form_validation->set_rules('telefono', 'Telefono', 'required|numeric');
                $this->form_validation->set_rules('municipioid', 'Municipio',  'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('tipocontratistaid', 'Tipo tributario',  'required|numeric|greater_than[0]');

                if ($this->form_validation->run() == false) 
                {
                    $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
                }else
                {
                    $data = [
                        'nombre'            => $this->input->post('nombre'),
                        'tipocontratistaid' => $this->input->post('tipocontratistaid'),
                        'nit'               => $this->input->post('nit'),
                        'municipioid'       => $this->input->post('municipioid'),
                        'fecha'             => date('Y-m-d'),
                        'email'             => $this->input->post('email'),
						'direccion'         => $this->input->post('direccion'),
						'telefono'          => $this->input->post('telefono'),
                    ];

                    $respuestaProceso = $this->codegen_model->add('con_contratantes',$data);
                    if($respuestaProceso->bandRegistroExitoso)
                    {
                        $this->session->set_flashdata('message', 'El contratante se ha creado con éxito');
                        redirect(base_url().'index.php/contratantes/add');
                    }else
                    {
                        $this->data['errormessage'] = 'No se pudo registrar el contratante';
                    }    
                }

                $this->template->set('title', 'Nuevo Contratante');
                $this->data['style_sheets']= [
                    'css/chosen.css' => 'screen'
                ];

                $this->data['javascripts']= [
                    'js/chosen.jquery.min.js'
                ];

                $this->data['municipios']  = $this->codegen_model->getMunicipios();
                $this->data['tiposcontratistas']  = $this->codegen_model->getSelect('con_tiposcontratistas','tpco_id,tpco_nombre');
                $this->data['regimenes']  = $this->codegen_model->getSelect('con_regimenes','regi_id,regi_nombre');
                $this->template->load($this->config->item('admin_template'),'contratantes/contratantes_add', $this->data);
            }else
            {
                redirect(base_url().'index.php/error_404');
            }    
        }else
        {
            redirect(base_url().'index.php/users/login');
        }    
    }	


    function edit()
    {    
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/edit'))
            {  
                $idContratante = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;

                if ($idContratante=='')
                {
                    $this->session->set_flashdata('infomessage', 'Debe elegir un contratante para editar');
                    redirect(base_url().'index.php/contratantes');
                }

                $resultado = $this->codegen_model->get('con_contratantes','nit','id = '.$idContratante,1,NULL,true);
                foreach ($resultado as $key => $value) 
                {
                    $aplilo[$key]=$value;
                }

                if ($aplilo['nit']==$this->input->post('nit')) 
                {
                    $this->form_validation->set_rules('nit', 'NIT', 'required|trim|xss_clean|max_length[100]');              
                }else
                {
                    $this->form_validation->set_rules('nit', 'NIT', 'required|trim|xss_clean|max_length[100]|is_unique[con_contratantes.nit]');
                }

                $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
                $this->form_validation->set_rules('municipioid', 'Municipio',  'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('tipocontratistaid', 'Tipo tributario',  'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			    $this->form_validation->set_rules('direccion', 'Direccion', 'required');
			    $this->form_validation->set_rules('telefono', 'Telefono', 'required|numeric');

                if ($this->form_validation->run() == false) 
                {
                    $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                }else
                {
                    $data = [
                        'nombre'            => $this->input->post('nombre'),
                        'nit'               => $this->input->post('nit'),
                        'municipioid'       => $this->input->post('municipioid'),
                        'tipocontratistaid' => $this->input->post('tipocontratistaid'),
                        'email'             => $this->input->post('email'),
						'direccion'         => $this->input->post('direccion'),
						'telefono'          => $this->input->post('telefono'),
                    ];

                    if ($this->codegen_model->edit('con_contratantes',$data,'id',$idContratante) == TRUE) 
                    {
                        $this->session->set_flashdata('successmessage', 'El contratante se ha editado con éxito');
                        redirect(base_url().'index.php/contratantes/edit/'.$idContratante);
                    }else
                    {
                        $this->data['errormessage'] = 'No se pudo modificar el contratante';
                    }
                }

                $this->data['style_sheets'] = [
                    'css/chosen.css' => 'screen'
                ];

                $this->data['javascripts'] = [
                    'js/chosen.jquery.min.js'
                ];

                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                $this->data['result'] = $this->codegen_model->get(
                    'con_contratantes',
                    'id,nombre,nit,municipioid,tipocontratistaid,,email,direccion,telefono',
                    'id = '.$idContratante,
                    1,NULL,true
                );
                $this->data['municipios']  = $this->codegen_model->getMunicipios();
                $this->data['regimenes']  = $this->codegen_model->getSelect('con_regimenes','regi_id,regi_nombre');
                $this->data['tiposcontratistas']  = $this->codegen_model->getSelect('con_tiposcontratistas','tpco_id,tpco_nombre');
                $this->template->set('title', 'Editar contratante');
                $this->template->load($this->config->item('admin_template'),'contratantes/contratantes_edit', $this->data);
            }else
            {
                redirect(base_url().'index.php/error_404');
            }
        }else
        {
            redirect(base_url().'index.php/users/login');
        }        
    }
	
  function delete()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contratante para eliminar');
                  redirect(base_url().'index.php/contratantes');
              }
              if (!$this->codegen_model->depend('con_contratos','cntr_contratanteid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_contratantes','id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El contratante se ha eliminado con éxito');
                  redirect(base_url().'index.php/contratantes');

              } else {

                  $this->session->set_flashdata('errormessage', 'El contratante se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/contratantes/edit/'.$this->input->post('id'));

              }
                         
          } else {
              redirect(base_url().'index.php/error_404');       
          } 
      } else {
          redirect(base_url().'index.php/users/login');
      }
  }
    
 
    function datatable()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/manage') ) 
            {
                /*
                * Se Valida si el usuario tiene la opcion de editar contratante
                * para renderizar el boton de editar
                */
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratantes/edit')) 
                {
                    $this->load->library('datatables');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">'
                    .'<div class="btn-group">'
                    .'<a href="'.base_url().'index.php/contratantes/edit/$1" '
                    .' class="btn btn-default btn-xs" title="Editar contratante">'
                    .'<i class="fa fa-pencil-square-o"></i></a>'
                    .'</div>'
                    .'</div>', 'c.id');
                }else 
                {
                    $this->load->library('datatables');     
                    $this->datatables->add_column('edit', '', 'c.id');
                }

                $this->datatables->select('c.id,c.nit,c.nombre,t.tpco_nombre,m.muni_nombre,d.depa_nombre');
                $this->datatables->from('con_contratantes c');
                $this->datatables->join('par_municipios m', 'm.muni_id = c.municipioid', 'left');
                $this->datatables->join('par_departamentos d', 'd.depa_id = m.muni_departamentoid', 'left');
                $this->datatables->join('con_tiposcontratistas t', 't.tpco_id = c.tipocontratistaid', 'left');                      
                echo $this->datatables->generate();        
            }else
            {
                redirect(base_url().'index.php/error_404');
            }                       
        }else
        {
            redirect(base_url().'index.php/users/login');
        }           
  }
}
