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
        $config['protocol']     = $this->config->item('protocol');
        $config['smtp_host']    = $this->config->item('smtp_host');
        $config['smtp_port']    = $this->config->item('smtp_port');
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = $this->config->item('smtp_user');
        $config['smtp_pass']    = $this->config->item('smtp_pass');
        $config['charset']      = $this->config->item('charset');
        $config['newline']      = "\r\n";
        $config['mailtype']     = 'html'; // or text
        $config['validation']   = TRUE; // bool whether to validate email or not      

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
