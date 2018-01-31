<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->model('codegen_model','',TRUE); 
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required
		$this->config->item('use_mongodb', 'ion_auth') ?
		$this->load->library('mongo_db') :

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->helper('language');
	}

	//redirect if needed, otherwise display the user list
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
               $this->data['message']=$this->session->flashdata('message');
			$this->template->load($this->config->item('admin_template'),'users/index', $this->data);
		}
	}
    function permisos(){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin())
               {
                //template data
                $this->load->model('codegen_model','',TRUE); 
                $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
                $idusuario = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
                $this->data['result'] = $this->codegen_model->get('users','id, email, perfilid','id = '.$idusuario,1,NULL,true);
                foreach ($this->data['result'] as $key => $value) {
                             $usuario[$key]=$value;
                    }
                $this->template->set('title', $usuario['email'].' - Editar permisos del usuario');    
                $this->template->load($this->config->item('admin_template'),'users/usuarios_permisos', $this->data);
               } else {
                          redirect(base_url().'index.php/error_404');
                   }
               } 
           else
            {
              redirect(base_url().'index.php/users/login');
            }
    } 
	//log the user in
	function login()
	{
		$this->data['title'] = "Login";

		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
			//check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{

				//llama al metodo de registro de log
				//en el modelo				
				$this->codegen_model->registerAccesos('log_in');

				//if the login is successful
				//redirect them back to the home page				
				$this->session->set_flashdata('successmessage', $this->ion_auth->messages());
				redirect(base_url().'liquidaciones/liquidar', 'refresh');
			}
			else
			{
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('errormessage', $this->ion_auth->errors());
				redirect('users/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['errormessage'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errormessage');
            $this->data['successmessage'] = $this->session->flashdata('successmessage');
            $this->template->load($this->config->item('admin_template'),'users/login', $this->data);
			
		}
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//llama al metodo de registro de log
		//en el modelo				
		$this->codegen_model->registerAccesos('log_out');


		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them to the login page
		$this->session->set_flashdata('successmessage', $this->ion_auth->messages());
		redirect('users/login', 'refresh');
	}

	//change password
	function editme()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('users/login', 'refresh');
		}

        $user = $this->ion_auth->user()->row();
	    $this->data['result']=$user;   
        //validate form input
        if ($user->email != $this->input->post('email'))  {
           $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
       
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');

        }

		
		
		if ($this->input->post('password')){
			 $this->form_validation->set_rules('oldpassword', $this->lang->line('change_password_validation_old_password_label'), 'required');
		     $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		     $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

		     $data['password'] = $this->input->post('password');
	    }		

		//valida la información personal
		$this->form_validation->set_rules('telefono', $this->lang->line('create_user_validation_telefono_label'), 'required|numeric');
		$this->form_validation->set_rules('apellidos', $this->lang->line('create_user_validation_apellidos_label'), 'required|min_length[4]|max_length[40]');
        $this->form_validation->set_rules('nombres', $this->lang->line('create_user_validation_nombres_label'), 'required|min_length[4]|max_length[40]');

		if ($this->form_validation->run() == false) {
			//display the form
			//set the flash data error message if there is one
			$this->data['errormessage'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errormessage');
			$this->data['successmessage']=$this->session->flashdata('successmessage');
			$this->template->load($this->config->item('admin_template'),'users/change_password', $this->data);

		}
		else {
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
            
            $data = array(
				    'email' => $this->input->post('email'),
				    'first_name' => $this->input->post('nombres'),
				    'last_name' => $this->input->post('apellidos'),
				    'phone' => $this->input->post('telefono')
			      );
				  $this->ion_auth->update($user->id, $data); 
				  $this->session->set_flashdata('successmessage', 'Ha editado sus datos con éxito');
				  

            if ($this->input->post('password')){
            	$change = $this->ion_auth->change_password($identity, $this->input->post('oldpassword'), $this->input->post('password'));
			   
			    if ($change) {
				    //if the password was successfully changed
				    $this->session->set_flashdata('successmessage', $this->ion_auth->messages());
				    $this->logout();
			    }
			    else {
				    $this->session->set_flashdata('errormessage', $this->ion_auth->errors());
				    redirect('users/change_password', 'refresh');
			    }
            }
            redirect("users/editme", 'refresh');
			
		}
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required');
		if ($this->form_validation->run() == false)
		{

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$this->data['errormessage'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errormessage');
			$this->data['successmessage'] = $this->session->flashdata('successmessage');
			$this->template->load($this->config->item('admin_template'),'users/forgot_password', $this->data);

		}
		else
		{
			// get identity for that email
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            if(empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('errormessage', $this->ion_auth->messages());
                redirect("users/forgot_password", 'refresh');
            }
            
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('successmessage', $this->ion_auth->messages());
				redirect("users/login", 'refresh'); //we should display a confirmation page here instead of the login page

			}
			else
			{
				$this->session->set_flashdata('errormessage', $this->ion_auth->errors());
				redirect("users/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			redirect(base_url().'index.php/error_404');
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new_password', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');
          
			if ($this->form_validation->run() == false)
			{
				//display the form
                  
				//set the flash data error message if there is one
				$this->data['errormessage'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errormessage');
                $this->data['successmessage'] =  $this->session->flashdata('successmessage');
				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;
				$this->template->load($this->config->item('admin_template'),'users/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password

					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new_password'));
                       
					if ($change)
					{
						//if the password was successfully changed
						$this->session->set_flashdata('successmessage', $this->ion_auth->messages());
						$this->logout();
					}
					else
					{
						$this->session->set_flashdata('errormessage', $this->ion_auth->errors());
						redirect('users/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('errormessage', $this->ion_auth->errors());
			redirect("users/forgot_password", 'refresh');
		}
	}


	//activate the user
	function activate($id, $code=false)
	{
      if ($this->ion_auth->logged_in())
		 {
		  if ($this->ion_auth->is_admin())
             {
             	$activate=$this->ion_auth->activate($id);
               if($activate==1)
               	  {
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario con id: '.$id.' se ha activado con éxito.</div>');
			        redirect("users", 'refresh');
               	  } else
               	  {
               	  	//redirect them to the forgot password page
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect("users", 'refresh');
               	  }
             }else 
			 {
			  
			  redirect(base_url().'error_404');
			 }
		 }else
		 {
			redirect(base_url().'users/login');
		 }


		/*if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}*/
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
	  if ($this->ion_auth->logged_in())
		 {
		  if ($this->ion_auth->is_admin())
			 {
			  $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;
			  // insert csrf check
			  $this->data['csrf'] = $this->_get_csrf_nonce();
			  $this->data['user'] = $this->ion_auth->user($id)->row();
			  $this->ion_auth->deactivate($id);	
			  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario con id: '.$id.' se ha desactivado con éxito.</div>');
			  redirect('users', 'refresh');
             }else 
			 {
			  $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
			  redirect(base_url().'error_404');
			 }
		 } else
		 {
			 redirect(base_url().'users/login');
		 }
	}



	//create a new user
	function create_user()
	{
		
      if ($this->ion_auth->logged_in())
		 {
		  if ($this->ion_auth->is_admin())
			 {
			  $this->data['title'] = "Crear usuario";	
		      $this->form_validation->set_rules('id', $this->lang->line('create_user_validation_id_label'), 'required|xss_clean|numeric|greater_than[0]|is_unique[users.id]');
		      $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
		      $this->form_validation->set_rules('telefono', $this->lang->line('create_user_validation_telefono_label'), 'required|numeric');
		      $this->form_validation->set_rules('apellidos', $this->lang->line('create_user_validation_apellidos_label'), 'required|min_length[4]|max_length[40]');
		      $this->form_validation->set_rules('nombres', $this->lang->line('create_user_validation_nombres_label'), 'required|min_length[4]|max_length[40]');
		      $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		      $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
              $this->form_validation->set_rules('perfilid', 'Perfil',  'required|numeric|greater_than[0]'); 
			  if ($this->form_validation->run() == false)
				 {
				  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage'));
                  $this->data['successmessage'] = $this->session->flashdata('successmessage');
				 } else
				 {
				  $username = $this->input->post('id'); //este id será el identificador de la tabla usuarios, no se usa nombre de usuario
			      $email    = strtolower($this->input->post('email'));
			      $password = $this->input->post('password');
                  $additional_data = array('perfilid' => $this->input->post('perfilid'),                  	
                      'first_name'=> $this->input->post('nombres'),
                      'last_name'=> $this->input->post('apellidos'),
                      'phone'=> $this->input->post('telefono'),
                      'id'=> $this->input->post('id'));                  

				  if ($this->ion_auth->register($username, $password, $email, $additional_data))
					 {
					   $usuarioid = $this->db->insert_id();
                       $perfiles_menus  = $this->codegen_model->getSelect('adm_perfiles_menus','peme_menuid','WHERE peme_perfilid ='.$this->input->post('perfilid'));
                       foreach ($perfiles_menus as $key => $value) {
                           $data = array(
                              'usme_usuarioid' => $usuarioid,
                              'usme_menuid' => $value->peme_menuid,
                           );
				 
						   $respuestaProceso = $this->codegen_model->add('adm_usuarios_menus',$data);
                       }

					   $this->session->set_flashdata('successmessage', 'El usuario se ha creado con éxito');
					   redirect(base_url().'users/create_user');
					 } else
					 {
					   //set the flash data error message if there is one
			           $this->data['errormessage'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('errormessage')));	

					 }
				  }
				 $this->load->model('codegen_model','',TRUE); 
				 $this->data['perfiles']  = $this->codegen_model->getSelect('adm_perfiles','perf_id,perf_nombre');
				 $this->template->load($this->config->item('admin_template'),'users/create_user', $this->data);
			 }else 
			 {
			  redirect(base_url().'error_404');
			 }
		 } else
		 {
			 redirect(base_url().'users/login');
		 }
	}

	//edit a user
	function edit($id=0)
	{
	  if ($this->ion_auth->logged_in()) {
		  
		  if ($this->ion_auth->is_admin()) {
			  if ($id==0) 
			  {
			  	$id=$this->input->post('id');
			  }
			  $this->data['title'] = "Editar usuarios";
		      $this->data['message'] =$this->session->flashdata('message');
		      $user = $this->ion_auth->user($id)->row();
		      $this->data['result']=$user; 
		      
              //validate form input
              if ($user->email != $this->input->post('email')) 
              {
              	  $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
              } else
              {
                  $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
              }
              
		      $this->form_validation->set_rules('perfilid', 'Perfil',  'required|numeric');  
              //update the password if it was posted
              
              $datosActualizar = array();
			  if ($this->input->post('password'))
			  {
				  $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				  $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
                  $this->form_validation->set_rules('perfilid', 'Perfil',  'required|numeric|greater_than[0]'); 
                  $this->form_validation->set_rules('telefono', $this->lang->line('create_user_validation_telefono_label'), 'required|numeric');
		          $this->form_validation->set_rules('apellidos', $this->lang->line('create_user_validation_apellidos_label'), 'required|min_length[4]|max_length[40]');
    		      $this->form_validation->set_rules('nombres', $this->lang->line('create_user_validation_nombres_label'), 'required|min_length[4]|max_length[40]');
				  $datosActualizar['password'] = $this->input->post('password');
			  }

			  if ($this->form_validation->run() === TRUE)
			  {				  
				    $datosActualizar['email']  = $this->input->post('email');
                    $datosActualizar['perfilid']  = $this->input->post('perfilid');
                    $datosActualizar['phone'] = $this->input->post('telefono');
                    $datosActualizar['last_name'] = $this->input->post('apellidos');
                    $datosActualizar['first_name'] = $this->input->post('nombres');

				    $this->ion_auth->update($user->id, $datosActualizar);

				    /*
				    * Si se suministró contraseña se modifica
				    */
				    if(isset($datosActualizar['password']))
				    {
				    	$identity = $user->{$this->config->item('identity', 'ion_auth')};
                        $change = $this->ion_auth->reset_password($identity, $datosActualizar['password']);
				    }
				  
				  if ($user->perfilid != $this->input->post('perfilid')) { //si cambia de perfil borramos permisos anteriores y agregamos los nuevos

                      $this->codegen_model->delete('adm_usuarios_menus','usme_usuarioid',$user->id);
				  	  $perfiles_menus  = $this->codegen_model->getSelect('adm_perfiles_menus','peme_menuid','WHERE peme_perfilid ='.$this->input->post('perfilid'));
                      foreach ($perfiles_menus as $key => $value) {
                          $data = array(
                              'usme_usuarioid' => $user->id,
                              'usme_menuid' => $value->peme_menuid
                          );
				 
						  $respuestaProceso = $this->codegen_model->add('adm_usuarios_menus',$data);
                      } 
				  }
				  $this->session->set_flashdata('successmessage', 'El usuario se ha editado con éxito');
				  redirect("users/edit/".$id, 'refresh');
			
			  } 
              $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage'));
		      $this->data['successmessage']=$this->session->flashdata('successmessage');
		      $this->data['csrf'] = $this->_get_csrf_nonce();
		      $this->load->model('codegen_model','',TRUE); 
			  $this->data['perfiles']  = $this->codegen_model->getSelect('adm_perfiles','perf_id,perf_nombre');
			  $this->template->load($this->config->item('admin_template'),'users/edit_user', $this->data);
             

           } else  {
			  $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
			  redirect(base_url().'error_404');
		   }
		 } else {
			 redirect(base_url().'users/login');
		 }
		
	}
     

  function delete()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin()) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un usuario para eliminar');
                  redirect(base_url().'index.php/estampillas');
              }

                  $this->ion_auth->delete_user($this->input->post('id'));
                  $this->codegen_model->delete('adm_usuarios_menus','usme_usuarioid',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El usuario se ha eliminado con éxito');
                  redirect(base_url().'index.php/users');  

                         
          } else {
              redirect(base_url().'index.php/error_404');       
          } 
      } else {
          redirect(base_url().'index.php/users/login');
      }
  }



	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $render=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}
   
   function predeterminar(){        
        if ($this->ion_auth->is_admin())
           {
            $this->load->library('form_validation');  
            $id_menu=$this->input->post('id_menu');
            $id_usuario=$this->input->post('id_usuario'); 
            $this->form_validation->set_rules('id_menu', 'Menú', 'required|numeric|greater_than[0]');   
            $this->form_validation->set_rules('id_usuario','Usuario', 'required|numeric|greater_than[0]');  
            
            if ($this->form_validation->run() == false)
            {
                echo $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
            } 
            else
            { 
                
                $data = array(
                        'usme_menuid' => $id_menu,
                        'usme_usuarioid' => $id_usuario
                );
				 
				$respuestaProceso = $this->codegen_model->add('adm_usuarios_menus',$data);
                if ($respuestaProceso->bandRegistroExitoso)
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
                $this->codegen_model->delete('adm_usuarios_menus','usme_id',$id_permiso); 
                echo $this->data['custom_error'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Eliminado correctamente</div>';
              }   
            }
        else
            {
              redirect(base_url().'index.php/auth/login');
            }
    } 
      
  function permisos_datatable ()
  {
      if ($this->ion_auth->logged_in()) {
                
          if ($this->ion_auth->is_admin()) { 
              //$user = $this->ion_auth->user()->row();
              $usuarioid=$this->uri->segment(3);
              $usuarioid = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0 ;
              $this->load->library('datatables');
              $this->datatables->select('m.menu_id,m.menu_nombre,m.menu_ruta,um.usme_id');
              $this->datatables->from('adm_menus m');
              $this->datatables->join('adm_usuarios_menus um','um.usme_menuid=m.menu_id and um.usme_usuarioid='.$usuarioid,'left');
              //$this->datatables->join('users u','');
                
              echo $this->datatables->generate();
              // echo $this->db->last_query();
          } else 
          {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
            }           
  }



	 function datatable (){
        if ($this->ion_auth->is_admin())
           {
            $this->load->library('datatables');
            $this->datatables->select('u.id,u.email,p.perf_nombre,u.active');
            $this->datatables->from('users u');
            $this->datatables->join('adm_perfiles p','p.perf_id = u.perfilid','left');
            $this->datatables->add_column('edit', '<div class="btn-toolbar" role="toolbar">
                                                       <div class="btn-group">
                                                        <a href="'.base_url().'users/edit/$1" class="btn btn-default btn-xs" title="Editar datos de usuario"><i class="fa fa-pencil-square-o"></i></a>
                                                        <a href="'.base_url().'users/permisos/$1" class="btn btn-default btn-xs" title="Editar permisos predeterminados"><i class="fa fa-eye"></i></a>
                                                       </div>
                                                   </div>', 'u.id');
             echo $this->datatables->generate();
            }
            else
            {
              redirect(base_url().'index.php/users/login');
            }           
    }
    

    //Funcion que carga el arreglo de opciones para autocompletar
    //en la busqueda de usuarios para asignar papeleria
    function extraerUsuarios()
     {
       $fragmento = $this->input->post('piece');
       $where = ['perfilid ='=>'4'];
       $resultado = $this->codegen_model->getLike('users',"users.first_name, users.last_name, users.id",'concat(first_name, last_name)',$fragmento,$where);
       
       foreach ($resultado->result_array() as $value) 
       {
          $vector_usuarios['idd'][]=$value['id'];
          $vector_usuarios['nombre'][]=$value['first_name'].' '.$value['last_name'];
       }
    
       echo json_encode($vector_usuarios);
     }  
}
