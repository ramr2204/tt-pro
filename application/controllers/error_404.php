<?php

class Error_404 extends MY_Controller {
    
    function __construct() {
        parent::__construct();		
	}	
	
	function index(){
    $this->template->set('title', 'Error 404');
    $this->data['message']='';
		$this->template->load($this->config->item('admin_template'),'templates/error_404', $this->data);
	}
     
}

/* End of file perfiles.php */
/* Location: ./system/application/controllers/perfiles.php */