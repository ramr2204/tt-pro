<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            contratistas
*   Ruta:              /application/controllers/contratistas.php
*   Descripcion:       controlador de contratistas
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Contratistas extends MY_Controller {
    
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
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar contratistas');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'contratistas/contratistas_list', $this->data);

          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }

    /**
     * Procesa el renderizado de la vista de creacion
     * y el registro del mismo
     * 
     * @return null
     */
    public function add()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/add'))
            {
                $this->data['successmessage']=$this->session->flashdata('message');

                $respuestaRegistro = $this->registrarContratista();

                if($respuestaRegistro['exito'])
                {
                    $this->session->set_flashdata('message', 'El contratista se ha creado con éxito');
                    redirect(base_url().'index.php/contratistas/add');
                } else {
                    $this->data['errormessage'] = $respuestaRegistro['error'];
                }

                $this->template->set('title', 'Nueva aplicación');
                $this->data['style_sheets']= array(
                            'css/chosen.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                            'js/chosen.jquery.min.js'
                        );  
                $this->template->set('title', 'Nuevo contratista');
                $this->data['municipios']  = $this->codegen_model->getMunicipios();
                $this->data['tiposcontratistas']  = $this->codegen_model->getSelect('con_tiposcontratistas','tpco_id,tpco_nombre');
                $this->data['regimenes']  = $this->codegen_model->getSelect('con_regimenes','regi_id,regi_nombre');
                $this->template->load($this->config->item('admin_template'),'contratistas/contratistas_add', $this->data);
            } else {
                redirect(base_url().'index.php/error_404');
            }
        } else {
          redirect(base_url().'index.php/users/login');
        }
    }

    /**
     * Procesa el registro del contratista
     * (sin redirecciones o validaciones de usuario)
     * 
     * @return array
     */
    public function registrarContratista()
    {
        $resuesta = [
            'exito' => false,
            'error' => '',
            'id' => null
        ];

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
        $this->form_validation->set_rules('nit', 'NIT', 'required|numeric|trim|xss_clean|max_length[100]|is_unique[con_contratistas.cont_nit]');   
        $this->form_validation->set_rules('direccion', 'Dirección', 'required|trim|xss_clean|max_length[256]');
        $this->form_validation->set_rules('telefono', 'Telefono', 'required|numeric|trim|xss_clean|max_length[15]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[con_contratistas.cont_email]');
        $this->form_validation->set_rules('municipioid', 'Municipio',  'required|numeric|is_exists[par_municipios.muni_id]');
        $this->form_validation->set_rules('tipocontratistaid', 'Tipo tributario',  'required|numeric|is_exists[con_tiposcontratistas.tpco_id]');

        if ($this->form_validation->run() == false) {
            $resuesta['error'] = (validation_errors() ? validation_errors(): false);
        }
        else
        {
            $data = [
                'cont_nombre'               => $this->input->post('nombre'),
                'cont_nit'                  => $this->input->post('nit'),
                'cont_direccion'            => $this->input->post('direccion'),
                'cont_telefono'             => $this->input->post('telefono'),
                'cont_email'                => $this->input->post('email'),
                'cont_municipioid'          => $this->input->post('municipioid'),
                'cont_tipocontratistaid'    => $this->input->post('tipocontratistaid'),
                'cont_fecha'                => date('Y-m-d')
            ];

            $respuestaProceso = $this->codegen_model->add('con_contratistas',$data);

            if ($respuestaProceso->bandRegistroExitoso) {
                $resuesta['exito'] = true;
                $resuesta['id'] = $respuestaProceso->idInsercion;
            } else {
                $resuesta['error'] = 'No se pudo registrar el contratista';
            }
        }

        $this->form_validation->reset_validation();
        return $resuesta;
    }

	function edit()
    {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/edit')) {

              $idContratista = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idContratista==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contratista para editar');
                  redirect(base_url().'index.php/contratistas');
              }
              $resultado = $this->codegen_model->get('con_contratistas','cont_nit,cont_email','cont_id = '.$idContratista,1,NULL,true);
       
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
             
              if ($aplilo['cont_nit']==$this->input->post('nit')) {
                  
                  $this->form_validation->set_rules('nit', 'NIT', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nit', 'NIT', 'required|trim|xss_clean|max_length[100]|is_unique[con_contratistas.cont_nit]');
              
              }

                if ($aplilo['cont_email']==$this->input->post('email')) {
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                } else {
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[con_contratistas.cont_email]');
                }

              $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');   
              $this->form_validation->set_rules('direccion', 'Dirección', 'trim|xss_clean|max_length[256]');
              $this->form_validation->set_rules('telefono', 'Telefono', 'numeric|trim|xss_clean|max_length[15]');
              $this->form_validation->set_rules('municipioid', 'Municipio',  'required|numeric|greater_than[0]');
              $this->form_validation->set_rules('tipocontratistaid', 'Tipo tributario',  'required|numeric|greater_than[0]');   

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);

              } else {
                  
                  $data = [
                        'cont_nombre' => $this->input->post('nombre'),
                        'cont_nit' => $this->input->post('nit'),
                        'cont_direccion' => $this->input->post('direccion'),
                        'cont_municipioid' => $this->input->post('municipioid'),
                        'cont_telefono' => $this->input->post('telefono'),
                        'cont_email' => $this->input->post('email'),
                        'cont_tipocontratistaid' => $this->input->post('tipocontratistaid')
                    ];

                	if ($this->codegen_model->edit('con_contratistas',$data,'cont_id',$idContratista) == TRUE) {

                    /*
                    * Extrae los id de los contratos que existan para el contratista
                    */
                    $where = 'WHERE cntr_contratistaid = '.$idContratista;
                    $idContratos = $this->codegen_model->getSelect('con_contratos','cntr_id',$where);
                    $vecIds = HelperGeneral::lists($idContratos,'cntr_id');

                    # Se deja al menos un campo como vacio por si no trae nada el IN no retorne error en mysql
                    $vecIds[] = 'NULL';
                    
                    /*
                    * Actualiza los datos del contratista en las posibles liquidaciones de los contratos
                    */
                    $where = 'liqu_contratoid IN ('. implode(',', $vecIds) .')';
                    
                    /*
                    * Se Agregan los datos del pago manual
                    */                                
                    $datos['liqu_nombrecontratista'] = $this->input->post('nombre');
                    $datos['liqu_nit'] = $this->input->post('nit');

                    /*
                    * Se Actualiza el registro del pago
                    */
                    $this->codegen_model->editWhere('est_liquidaciones',$datos,$where);


                    $this->session->set_flashdata('successmessage', 'El contratista se ha editado con éxito');
                    redirect(base_url().'index.php/contratistas/edit/'.$idContratista);
                    
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }   
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                        );
                  $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                        );    
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('con_contratistas','cont_id,cont_nombre,cont_nit,cont_direccion,cont_telefono,cont_municipioid,cont_tributarioid,cont_tipocontratistaid,cont_email','cont_id = '.$idContratista,1,NULL,true);
                  $this->data['municipios']  = $this->codegen_model->getMunicipios();
                  $this->data['regimenes']  = $this->codegen_model->getSelect('con_regimenes','regi_id,regi_nombre');
                  $this->data['tiposcontratistas']  = $this->codegen_model->getSelect('con_tiposcontratistas','tpco_id,tpco_nombre');
                  $this->template->set('title', 'Editar contratista');
                  $this->template->load($this->config->item('admin_template'),'contratistas/contratistas_edit', $this->data);
                        
          }else {
              redirect(base_url().'index.php/error_404');
          }
      } else {
          redirect(base_url().'index.php/users/login');
      }
        
  }
	
  function delete()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contratista para eliminar');
                  redirect(base_url().'index.php/contratistas');
              }
              if (!$this->codegen_model->depend('con_contratos','cntr_contratistaid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_contratistas','cont_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El contratista se ha eliminado con éxito');
                  redirect(base_url().'index.php/contratistas');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El contratista se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/contratistas/edit/'.$this->input->post('id'));

              }
                         
          } else {
              redirect(base_url().'index.php/error_404');       
          } 
      } else {
          redirect(base_url().'index.php/users/login');
      }
  }
    
 
  function datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/manage') ) { 
                
                /*
                * Se Valida si el usuario tiene la opcion de editar contratista
                * para renderizar el boton de editar
                */            
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/edit')) 
                {
                    $this->load->library('datatables');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">'
                        .'<div class="btn-group">'
                        .'<a href="'.base_url().'index.php/contratistas/edit/$1" class="btn btn-default btn-xs" title="Editar contratista"><i class="fa fa-pencil-square-o"></i></a>'
                        .'</div>'
                        .'</div>', 'c.cont_id');
                }else 
                    {             
                        $this->load->library('datatables');     
                        $this->datatables->add_column('edit', '', 'c.cont_id');
                    }
              
              $this->datatables->select('c.cont_id,c.cont_nit,c.cont_nombre,t.tpco_nombre,m.muni_nombre,d.depa_nombre,c.cont_direccion');
              $this->datatables->from('con_contratistas c');
              $this->datatables->join('par_municipios m', 'm.muni_id = c.cont_municipioid', 'left');
              $this->datatables->join('par_departamentos d', 'd.depa_id = m.muni_departamentoid', 'left');
              $this->datatables->join('con_tiposcontratistas t', 't.tpco_id = c.cont_tipocontratistaid', 'left');

              
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }
}
