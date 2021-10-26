<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class EnvioCorreoHelper extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('codegen_model', '', true);
    }

	public function enviar($data)
    {
        $this->load->library('email');

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

        $mesg = $this->load->view('templates/mail','',true);
        $this->email->message($mesg);

        if($this->email->send())
        {
            return true;
        }
        else
        {
            echo $this->email->print_debugger();exit();
        }

   }   


}
