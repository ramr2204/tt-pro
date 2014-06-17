<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            contratos
*   Ruta:              /application/controllers/contratos.php
*   Descripcion:       controlador de contratos
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Liquidaciones extends MY_Controller {
    
  function __construct() 
  {
      parent::__construct();
	    $this->load->library('form_validation');		
		  $this->load->helper(array('form','url','codegen_helper'));
      $this->load->model('liquidaciones_model','',TRUE);
      $this->load->model('codegen_model','',TRUE);
	}	
	
	function index()
  {
		  $this->liquidar();
	}

	
  function liquidar()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')){

              $this->data['successmessage2']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              $this->data['accion']=$this->session->flashdata('accion');
              if ($this->uri->segment(3)>0){
                  $this->data['idcontrato']= $this->uri->segment(3);
               } else {
               	  $this->data['idcontrato']= 0;
               }
              //template data
              $this->template->set('title', 'Administrar liquidaciones');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js',
                        'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                        'js/accounting.min.js'
                       );
              $resultado = $this->codegen_model->max('con_contratos','cntr_fecha_firma');
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              $vigencia_mayor=substr($aplilo['cntr_fecha_firma'], 0, 4);
              $vigencia_anterior=$vigencia_mayor-1;
              $this->data['vigencias']= array($vigencia_mayor,$vigencia_anterior);
              $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_liquidar', $this->data);
              
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }



  function liquidarcontrato()
  {        
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $idcontrato=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->get($idcontrato);
              $contrato = $this->data['result'];
      
              $this->data['estampillas'] = $this->liquidaciones_model->getestampillas($contrato->cntr_tipocontratoid);
              $estampillas=$this->data['estampillas'];   
              $valorsiniva = $contrato->cntr_valor-(($contrato->cntr_valor*$contrato->regi_iva)/100);
              $totalestampilla= array();
              $valortotal=0;
              foreach ($estampillas as $key => $value) {
                
                 $totalestampilla[$value->estm_id] = (($valorsiniva*$value->esti_porcentaje)/100);
                 $valortotal+=$totalestampilla[$value->estm_id];
              }
              $this->data['est_totalestampilla']=$totalestampilla;
              $this->data['cnrt_valorsiniva']=$valorsiniva;
              $this->data['est_valortotal']=$valortotal;
              $this->template->set('title', 'Editar contrato');
              $this->load->view('liquidaciones/liquidaciones_liquidarcontrato', $this->data); 
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }	

 

 function verrecibos()
  {        
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $idcontrato=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibos($idcontrato);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);
              $this->template->set('title', 'Contrato liquidado');
              //$this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_vercontratoliquidado', $this->data);
              $this->load->view('liquidaciones/liquidaciones_vercontratoliquidado', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 
  function procesarliquidacion()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
               $codigo='00000000';
               $idcontrato=$this->input->post('idcontrato');
              $data = array(
                   'liqu_contratoid' => $this->input->post('idcontrato'),
                   'liqu_nombrecontratista' => $this->input->post('nombrecontratista'),
                   'liqu_nit' => $this->input->post('nit'),
                   'liqu_tipocontratista' => $this->input->post('tipocontratista'),
                   'liqu_numero' => $this->input->post('numero'),
                   'liqu_vigencia' => $this->input->post('vigencia'),
                   'liqu_valor' => $this->input->post('valor'),
                   'liqu_valorsiniva' => $this->input->post('valorsiniva'),
                   'liqu_tipocontrato' => $this->input->post('tipocontrato'),
                   'liqu_regimen' => $this->input->post('regimen'),
                   'liqu_nombreestampilla' => $this->input->post('nombreestampilla'),
                   'liqu_cuentas' => $this->input->post('cuentas'),
                   'liqu_porcentajes' => $this->input->post('porcentajes'),
                   'liqu_totalestampilla' => $this->input->post('totalestampillas'),
                   'liqu_valortotal' => $this->input->post('valortotal'),
                   'liqu_comentarios' => $this->input->post('comentarios'),
                   'liqu_codigo' => $codigo

                 );
                  
              if ($this->codegen_model->add('est_liquidaciones',$data) == TRUE) {
              	  $liquidacionid=$this->db->insert_id();
                  for ($i=1; $i < $this->input->post('numeroestampillas'); $i++) { 
                  	   $data = array(
                       'fact_nombre' => $this->input->post('nombreestampilla'.$i),
                       'fact_porcentaje' => $this->input->post('porcentaje'.$i),
                       'fact_valor' => $this->input->post('totalestampilla'.$i),
                       'fact_banco' => $this->input->post('banco'.$i),
                       'fact_cuenta' => $this->input->post('cuenta'.$i),
                       'fact_liquidacionid' => $liquidacionid,
                       );
                  	   $this->codegen_model->add('est_facturas',$data);
                  }

                  //print_r($data);
                  $data = array(
                   'cntr_estadolocalid' => 1,
                   );
                  if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$idcontrato) == TRUE) {
                      
                      $this->session->set_flashdata('successmessage', 'La liquidación se realizó con éxito');
                      $this->session->set_flashdata('accion', 'liquidado');
                      redirect(base_url().'index.php/liquidaciones/liquidar/'.$idcontrato);
                     // echo $this->db->last_query();
                  }
              }
                
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 

function procesarpago()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
               $codigo='00000000';
               $idcontrato=$this->input->post('idcontrato');
              $data = array(
                   'liqu_contratoid' => $this->input->post('idcontrato'),
                   'liqu_nombrecontratista' => $this->input->post('nombrecontratista'),
                   'liqu_nit' => $this->input->post('nit'),
                   'liqu_tipocontratista' => $this->input->post('tipocontratista'),
                   'liqu_numero' => $this->input->post('numero'),
                   'liqu_vigencia' => $this->input->post('vigencia'),
                   'liqu_valor' => $this->input->post('valor'),
                   'liqu_valorsiniva' => $this->input->post('valorsiniva'),
                   'liqu_tipocontrato' => $this->input->post('tipocontrato'),
                   'liqu_regimen' => $this->input->post('regimen'),
                   'liqu_nombreestampilla' => $this->input->post('nombreestampilla'),
                   'liqu_cuentas' => $this->input->post('cuentas'),
                   'liqu_porcentajes' => $this->input->post('porcentajes'),
                   'liqu_totalestampilla' => $this->input->post('totalestampillas'),
                   'liqu_valortotal' => $this->input->post('valortotal'),
                   'liqu_comentarios' => $this->input->post('comentarios'),
                   'liqu_codigo' => $codigo

                 );
              if ($this->codegen_model->add('est_liquidaciones',$data) == TRUE) {
                  $data = array(
                   'cntr_estadolocalid' => 0,
                   );
                  if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$idcontrato) == TRUE) { 
                      //envia a donde se generan los recibos PDF
                      redirect(base_url().'index.php/liquidaciones/liquidar');
                      echo $this->db->last_query();
                  }
              }
                
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 

  function procesarterminado()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
               $codigo='00000000';
               $idcontrato=$this->input->post('idcontrato');
              $data = array(
                   'liqu_contratoid' => $this->input->post('idcontrato'),
                   'liqu_nombrecontratista' => $this->input->post('nombrecontratista'),
                   'liqu_nit' => $this->input->post('nit'),
                   'liqu_tipocontratista' => $this->input->post('tipocontratista'),
                   'liqu_numero' => $this->input->post('numero'),
                   'liqu_vigencia' => $this->input->post('vigencia'),
                   'liqu_valor' => $this->input->post('valor'),
                   'liqu_valorsiniva' => $this->input->post('valorsiniva'),
                   'liqu_tipocontrato' => $this->input->post('tipocontrato'),
                   'liqu_regimen' => $this->input->post('regimen'),
                   'liqu_nombreestampilla' => $this->input->post('nombreestampilla'),
                   'liqu_cuentas' => $this->input->post('cuentas'),
                   'liqu_porcentajes' => $this->input->post('porcentajes'),
                   'liqu_totalestampilla' => $this->input->post('totalestampillas'),
                   'liqu_valortotal' => $this->input->post('valortotal'),
                   'liqu_comentarios' => $this->input->post('comentarios'),
                   'liqu_codigo' => $codigo

                 );
              if ($this->codegen_model->add('est_liquidaciones',$data) == TRUE) {
                  $data = array(
                   'cntr_estadolocalid' => 1,
                   );
                  if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$idcontrato) == TRUE) {
                      
                      //envia a donde se generan los recibos PDF
                     redirect(base_url().'index.php/liquidaciones/liquidar');
                   // echo $this->db->last_query();
                  }
              }
                
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }
  
 
  function liquidaciones_datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('c.cntr_id,c.cntr_numero,co.cont_nit,co.cont_nombre,c.cntr_fecha_firma,c.cntr_objeto,c.cntr_valor,el.eslo_nombre');
              $this->datatables->from('con_contratos c');
              $this->datatables->join('con_contratistas co', 'co.cont_id = c.cntr_contratistaid', 'left');
              $this->datatables->join('con_estadoslocales el', 'el.eslo_id = c.cntr_estadolocalid', 'left');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
                  
                  $this->datatables->add_column('edit', '-');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 'c.cntr_id'); 
              }
              
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }

}
