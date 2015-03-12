<?php

class Perfiles extends MY_Controller {
    
    function __construct() {
        parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);

	}	
	
	function index(){
		$this->manage();
	}

	function manage(){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('perfiles/manage'))
               {
                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage']=$this->session->flashdata('errormessage');
            //template data
                $this->template->set('title', 'Administrar perfiles');
                $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
                $this->template->load($this->config->item('admin_template'),'perfiles/perfiles_list', $this->data);
               } else {
                          redirect(base_url().'index.php/error_404');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/users/login');
            }
    }
	function permisos(){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin())
               {
              
                //template data
                
                $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
                $idperfil = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
                $this->data['result'] = $this->codegen_model->get('adm_perfiles','perf_id,perf_nombre,perf_descripcion','perf_id = '.$idperfil,1,NULL,true);
                foreach ($this->data['result'] as $key => $value) {
                             $perfil[$key]=$value;
                    }
                $this->template->set('title', $perfil['perf_nombre'].' - Editar permisos predeterminados del perfil');    
                $this->template->load($this->config->item('admin_template'),'perfiles/perfiles_permisos', $this->data);
               } else {
                          redirect(base_url().'index.php/error_404');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/users/login');
            }
    }
    function add(){        
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin())
               {
                 $this->data['successmessage']=$this->session->flashdata('message');  
        		     $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]|is_unique[adm_perfiles.perf_nombre]');   
                 $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|max_length[500]'); 
                 if ($this->form_validation->run() == false)
                 {
                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
                 } else
                 {    
                   $data = array(
                        
                                 'perf_nombre' => $this->input->post('nombre'),
                                 'perf_descripcion' => $this->input->post('descripcion')
                   );
                 
    			       if ($this->codegen_model->add('adm_perfiles',$data) == TRUE)
    			       {
                   $this->session->set_flashdata('message', 'El perfil se ha creado con éxito');
                   redirect(base_url().'index.php/perfiles/add');
    			       }
    			       else
    			       {
    				       $this->data['errormessage'] = 'No se pudo registrar el perfil';

    			       }
    		       }
                
                $this->template->set('title', 'Nuevo Perfil');
                $this->template->load($this->config->item('admin_template'),'perfiles/perfiles_add', $this->data);
             
             } else 
             {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url().'index.php/perfiles');
             }
           } else
           {
             redirect(base_url().'index.php/users/login');
           }
    }	


	function edit(){    
        if ($this->ion_auth->logged_in())
           {
                if (($this->ion_auth->is_admin() || $this->ion_auth->in_menu('perfiles/edit')))
                {  
                    $idperfil = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
                    $resultado = $this->codegen_model->get('adm_perfiles','perf_nombre','perf_id = '.$idperfil,1,NULL,true);
                    foreach ($resultado as $key => $value) {
                             $perfil[$key]=$value;
                    }
                    if ($perfil['perf_nombre']==$this->input->post('nombre')) 
                    {
                      $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
                    } else 
                    {
                      $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]|is_unique[adm_perfiles.perf_nombre]');
                    }

                    $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|max_length[500]');  

                    if ($this->form_validation->run() == false)
                    {
                        $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
                    } else
                    {                            
                        $data = array(
                                'perf_nombre' => $this->input->post('nombre'),
                                'perf_descripcion' => $this->input->post('descripcion'),
                         );
                           
                			  if ($this->codegen_model->edit('adm_perfiles',$data,'perf_id',$idperfil) == TRUE)
                			  {
                				  //$this->data['successmessage'] = 'El perfil se ha editado con éxito';
                           $this->session->set_flashdata('successmessage', 'El perfil se ha editado con éxito');
                           redirect(base_url().'index.php/perfiles/edit/'.$idperfil);
                			  }
                			  else
                			  {
                				  $this->data['errormessage'] = 'No se pudo registrar el perfil';

                			  }
                		}    
                     $this->data['successmessage']=$this->session->flashdata('successmessage');
                     $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	   $this->data['result'] = $this->codegen_model->get('adm_perfiles','perf_id,perf_nombre,perf_descripcion','perf_id = '.$idperfil,1,NULL,true);
                     $this->template->set('title', 'Editar perfil');
                     $this->template->load($this->config->item('admin_template'),'perfiles/perfiles_edit', $this->data);
                        
              }else {
                          redirect(base_url().'index.php/error_404');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/users/login');
            }
        
    }
	
    function delete(){
         if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin())
               {  
                  if (!$this->codegen_model->depend('users','perfilid',$this->input->post('idperfil'))) 
                  {
                     $this->codegen_model->delete('adm_perfiles','perf_id',$this->input->post('idperfil'));
                     $this->codegen_model->delete('adm_perfiles_menus','peme_perfilid',$this->input->post('idperfil'));
                     $this->session->set_flashdata('successmessage', 'El perfil se ha eliminado con éxito');
                     redirect(base_url().'index.php/perfiles');  
                  } else
                  {
                     $this->session->set_flashdata('errormessage', 'El perfil se encuentra en uso, no es posible eliminarlo.');
                     redirect(base_url().'index.php/perfiles/edit/'.$this->input->post('idperfil'));
                  }
                         
               } else 
               {
                   redirect(base_url().'index.php/error_404');
                   
               } 
            } else
            {
              redirect(base_url().'index.php/users/login');
            }
    }


function predeterminar(){        
        if ($this->ion_auth->is_admin())
           {
            $this->load->library('form_validation');  
            $id_menu=$this->input->post('id_menu');
            $id_perfil=$this->input->post('id_perfil'); 
            $this->form_validation->set_rules('id_menu', 'Menú', 'required|numeric|greater_than[0]');   
            $this->form_validation->set_rules('id_perfil','Perfil', 'required|numeric|greater_than[0]');  
            
            if ($this->form_validation->run() == false)
            {
                echo $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
            } 
            else
            { 
                
                $data = array(
                        'peme_menuid' => $id_menu,
                        'peme_perfilid' => $id_perfil
                );
                 
                if ($this->codegen_model->add('adm_perfiles_menus',$data) == TRUE)
                {
                     echo $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Agregado correctamente</div>';
                }
                else
                {
                    echo $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrió un error.</div>';

                }
             
            }   
            }
            else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }   

    function despredeterminar(){        
      
        if ($this->ion_auth->is_admin())
           {
              
            $this->load->library('form_validation');  
            $id_permiso=$this->input->post('id_permiso');
            $this->form_validation->set_rules('id_permiso', 'Permiso', 'required|numeric|greater_than[0]');  
              if ($this->form_validation->run() == false)
              {
                echo $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
              } 
              else
              { 
                $this->codegen_model->delete('adm_perfiles_menus','peme_id',$id_permiso); 
                echo $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Eliminado correctamente</div>';
              }   
            }
        else
            {
              redirect(base_url().'index.php/auth/login');
            }
    } 



    function permisos_datatable (){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin())
               { 
                //$user = $this->ion_auth->user()->row();
                $perfilid=$this->uri->segment(3);
                $perfilid = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0 ;
                $this->load->library('datatables');
                $this->datatables->select('m.menu_id,m.menu_nombre,m.menu_ruta,pm.peme_id');
                $this->datatables->from('adm_menus m');
                $this->datatables->join('adm_perfiles_menus pm','pm.peme_menuid=m.menu_id and pm.peme_perfilid='.$perfilid,'left');
                //$this->datatables->join('users u','');
                
                echo $this->datatables->generate();
                // echo $this->db->last_query();
                } else 
                {
                  $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                  redirect(base_url().'index.php/perfiles');
                }
               
            } else{
              redirect(base_url().'index.php/users/login');
            }           
    }
 
     function datatable (){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin())
               { 
                
                $this->load->library('datatables');
                $this->datatables->select('p.perf_id,p.perf_nombre,p.perf_descripcion');
                $this->datatables->from('adm_perfiles p');
                $this->datatables->add_column('edit', '<div class="btn-toolbar" role="toolbar">
                                                       <div class="btn-group">
                                                        <a href="'.base_url().'perfiles/edit/$1" class="btn btn-default btn-xs" title="Editar datos del perfil"><i class="fa fa-pencil-square-o"></i></a>
                                                        <a href="'.base_url().'perfiles/permisos/$1" class="btn btn-default btn-xs" title="Editar permisos predeterminados del perfil"><i class="fa fa-eye"></i></a>
                                                       </div>
                                                   </div>', 'p.perf_id');
                echo $this->datatables->generate();
                } else 
                {
                  $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                  redirect(base_url().'index.php/error_404');
                }
               
            } else{
              redirect(base_url().'index.php/users/login');
            }           
    }
}

/* End of file perfiles.php */
/* Location: ./system/application/controllers/perfiles.php */