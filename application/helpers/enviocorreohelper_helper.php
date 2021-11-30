<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class EnvioCorreoHelper extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

	public function enviar($data)
    {
        $config = $this->config->item('email_config');
        $config['newline']  = "\r\n";
        $config['mailtype'] = 'html'; // or text
        $this->email->initialize($config);

        $this->email->from($this->config->item('smtp_user'), $data['sender_name']);
        $this->email->to($data['to']); 
        $this->email->subject($data['subject']);
        $this->email->set_alt_message($data['alt']);

        $this->email->message($data['body']);

        if($this->email->send())
        {
            return true;
        }
        else
        {
            return false;
            // echo $this->email->print_debugger();exit();
        }
   }
}
