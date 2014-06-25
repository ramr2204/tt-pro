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
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect(base_url().'liquidaciones/liquidar', 'refresh');
			}
			else
			{
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('users/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
              'id' => 'identity',
              'type' => 'email',
              'class' => 'form-control',
              'placeholder' => 'Email',
              'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
              'id' => 'password',
              'class' => 'form-control',
              'type' => 'password',
            );
            $this->template->load($this->config->item('admin_template'),'users/login', $this->data);
			
		}
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('users/login', 'refresh');
	}

	//change password
	function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('users/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			//render
			$this->template->load($this->config->item('admin_template'),'users/change_password', $this->data);

		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('users/change_password', 'refresh');
			}
		}
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->template->load($this->config->item('admin_template'),'users/forgot_password', $this->data);

		}
		else
		{
			// get identity for that email
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            if(empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("users/forgot_password", 'refresh');
            }
            
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("users/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("users/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_error_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				//display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
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

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						//if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						$this->logout();
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('users/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
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
			  $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
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
		      $this->form_validation->set_rules('id', $this->lang->line('create_user_validation_id_label'), 'required|xss_clean');
		      $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
		      $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		      $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

			  if ($this->form_validation->run() == false)
				 {
				  $this->data['message'] = (validation_errors() ? '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.validation_errors().'</div>' : $this->session->flashdata('message'));

				 } else
				 {
				  $username = $this->input->post('id');
			      $email    = strtolower($this->input->post('email'));
			      $password = $this->input->post('password');
                  $additional_data = array();

				  if ($this->ion_auth->register($username, $password, $email, $additional_data))
					 {
					   $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario se ha creado con éxito.</div>');
					   redirect(base_url().'users/create_user');
					 } else
					 {
					   //set the flash data error message if there is one
			           $this->data['message'] = (validation_errors() ? '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.validation_errors().'</div>' : '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.($this->ion_auth->errors() ? $this->ion_auth->errors().'</div>' : $this->session->flashdata('message')));	

					 }
				  }
				 $this->load->model('codegen_model','',TRUE); 
				 $this->data['perfiles']  = $this->codegen_model->getSelect('adm_perfiles','perf_id,perf_nombre');
				 $this->template->load($this->config->item('admin_template'),'users/create_user', $this->data);
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

	//edit a user
	function edit($id=0)
	{
	  if ($this->ion_auth->logged_in())
		 {
		  if ($this->ion_auth->is_admin())
			 {
			  if ($id==0) 
			  {
			  	$id=$this->input->post('id');
			  }
			  $this->data['title'] = "Editar usuarios";
		      //$this->data['message'] =$this->session->flashdata('message');
		      $user = $this->ion_auth->user($id)->row();
		      $this->data['result']=$user; var_dump($this->db->last_query());
		      
		      $groups=$this->ion_auth->groups()->result_array();
		      $currentGroups = $this->ion_auth->get_users_groups($id)->result();
              //validate form input
              if ($user->email != $this->input->post('email')) 
              {
              	  $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
              } else
              {
                  $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
              }
		     
             // $this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');
              

              //update the password if it was posted

			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
				'email' => $this->input->post('email')
			    );
				$this->ion_auth->update($user->id, $data); 
                //Update the groups user belongs to
			    $groupData = $this->input->post('groups');

			    if (isset($groupData) && !empty($groupData)) 
			    {

				    $this->ion_auth->remove_from_group('', $id);

				    foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				    }

			    } 
				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El usuario se ha creado con éxito.</div>');
				
				//redirect("users/edit/".$id, 'refresh');
			} else
			{



			}
               //set the flash data error message if there is one
			   $this->data['message'] = (validation_errors() ? '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.validation_errors().'</div>' : ''.($this->ion_auth->errors() ? '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$this->ion_auth->errors().'</div>' : $this->session->flashdata('message')));	
              //display the edit user form
		     $this->data['message'] = $this->session->flashdata('message');

		      $this->data['csrf'] = $this->_get_csrf_nonce();

		      //set the flash data error message if there is one
              
              $this->data['groups'] = $groups;
		      $this->data['currentGroups'] = $currentGroups;
		      $this->load->model('codegen_model','',TRUE); 
			  $this->data['perfiles']  = $this->codegen_model->getSelect('perfiles','idperfil,nombreperfil');
			  $this->template->load($this->config->item('admin_template'),'users/edit_user', $this->data);
             

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

	// create a new group
	function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('users', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("users", 'refresh');
			}
		}
		else
		{
			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->template->load($this->config->item('admin_template'),'users/create_group', $this->data);
		}
	}

	//edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('users', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('users', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("users", 'refresh');
			}
		}

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('users/edit_group', $this->data);
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
                 
                if ($this->codegen_model->add('adm_usuarios_menus',$data) == TRUE)
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
            $this->datatables->select('u.id,u.email,u.active');
            $this->datatables->from('users u');
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

}
