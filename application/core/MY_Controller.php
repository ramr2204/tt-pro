<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 *
 * @author      Iván Viña
 */

class MY_Controller extends CI_Controller
{
	
	//public $template_file = 'templates/main2';
	/**
	 * Class constructor
	 */
  public function __construct()
  {
  		// creación dinámica del menú
  		parent::__construct();
  		header('Pragma: no-cache');
          $this->load->helper('array');
          $this->load->helper('HelperGeneral');
  		//$this->load->model('menu_usuario_model','',TRUE)

      $this->data['menus'] = $this->ion_auth_model->get_menus($this->session->userdata('user_id'));
      $menus_nav = array();
      $aplicaciones_nav= array();
      $modulos_nav= array();
      $procesos_nav= array();
      $get_menus=false;
      
      if ($this->data['menus'] ) {
          foreach ($this->data['menus'] as $key => $value) {
                $procesos_nav[$value['proc_id']] = array('proc_id' => $value['proc_id'],'proc_nombre' => $value['proc_nombre']);       
                $aplicaciones_nav[$value['apli_id']] = array('apli_id' => $value['apli_id'],'apli_nombre' => $value['apli_nombre'],'apli_procesoid' => $value['apli_procesoid']);   
                $modulos_nav[$value['modu_id']] = array('modu_id' => $value['modu_id'],'modu_nombre' => $value['modu_nombre'],'modu_aplicacionid' => $value['modu_aplicacionid']);
                $menus_nav[$value['menu_id']] = array('menu_id' => $value['menu_id'],'menu_nombre' => $value['menu_nombre'],'menu_moduloid' => $value['menu_moduloid'],'menu_ruta' => $value['menu_ruta']);     
          } 
          $get_menus=true;   
      } 
                
                  // echo $this->db->last_query().'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
               
       $this->template->set('nav_menus',$menus_nav);
       $this->template->set('nav_aplicaciones',$aplicaciones_nav);
       $this->template->set('nav_modulos',$modulos_nav);
       $this->template->set('nav_procesos',$procesos_nav);
       $this->template->set('get_menus',$get_menus);
    }	
  	
}

/* End of file MY_Controller.php */
/* Location: /application/libraries/MY_Controller.php */