<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            liquidaciones
*   Ruta:              /application/controllers/liquidaciones.php
*   Descripcion:       controlador de liquidaciones
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
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen',
                            'css/plugins/bootstrap/fileinput.css' => 'screen',
                            'css/plugins/bootstrap/bootstrap-switch.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js',
                        'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                        'js/accounting.min.js',
                        'js/plugins/bootstrap/fileinput.min.js',
                        'js/plugins/bootstrap/bootstrap-switch.min.js',
                        'js/applicationEvents.js'
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

 function liquidartramites()
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
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen',
                            'css/plugins/bootstrap/fileinput.css' => 'screen',
                            'css/plugins/bootstrap/bootstrap-switch.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js',
                        'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                        'js/accounting.min.js',
                        'js/plugins/bootstrap/fileinput.min.js',
                        'js/plugins/bootstrap/bootstrap-switch.min.js'
                       );
              $resultado = $this->codegen_model->max('con_contratos','cntr_fecha_firma');
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              $vigencia_mayor=substr($aplilo['cntr_fecha_firma'], 0, 4);
              $vigencia_anterior=$vigencia_mayor-1;
              $this->data['vigencias']= array($vigencia_mayor,$vigencia_anterior);
              $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_liquidartramites', $this->data);
              
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
              //echo json_encode($estampillas) ;exit();
              $valorsiniva = $contrato->cntr_valor/(($contrato->regi_iva/100)+1);
              $totalestampilla= array();
              $valortotal=0;
              $parametros=$this->codegen_model->get('adm_parametros','para_redondeo','para_id = 1',1,NULL,true);
              foreach ($estampillas as $key => $value) {
                
                 $totalestampilla[$value->estm_id] = (($valorsiniva*$value->esti_porcentaje)/100);
                 $totalestampilla[$value->estm_id] = round ( $totalestampilla[$value->estm_id], -$parametros->para_redondeo );
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
                   'liqu_valorconiva' => $this->input->post('valorconiva'),
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
                       'fact_rutaimagen' => $this->input->post('rutaimagen'.$i),
                       );
                  	   $this->codegen_model->add('est_facturas',$data);
                  }

                 
                  $data = array(
                   'cntr_estadolocalid' => 1,
                   );
                  if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$idcontrato) == TRUE) {
                      
                      $this->session->set_flashdata('successmessage', 'La liquidación se realizó con éxito');
                      $this->session->set_flashdata('accion', 'liquidado');
                      redirect(base_url().'index.php/liquidaciones/liquidar/'.$idcontrato);
                
                  }
              }
                
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


              //$todopago variable bandera indica si la totalidad de facturas 
              //no se han pagado
              $todopago=0;
              $numerocomprobantes=0;
              $ncomprobantescargados=0;
              $totalpagado=0;
              //vector $comprobantecargado
              //almacena la relacion indice->valor
              //tal que ==>    (id factura)->(true)
              //si no se ha cargado comprobante para
              //esa factura.   (id factura)->(false)
              //si ya se cargó comprobante
              $comprobantecargado=array();
              $facturapagada=array();
              $facturas=$this->data['facturas']; 

              //Itera por las filas de informacion de las facturas
              //creadas para cada estampilla asignada al contrato
              foreach ($facturas as $key => $value) {
                  $totalpagado += $value->pago_valor;
                  $numerocomprobantes++;  

                  //si el valor en la tabla pagos es mayor o igual
                  //al valor de la factura de la estampilla
                  //se asigna al vector $facturapagada el indice 
                  //del id de la factura y el valor true
                 if ($value->pago_valor >= $value->fact_valor) {
                     $facturapagada[$value->fact_id]=true;
                 } else {
                    $todopago=1;
                    $facturapagada[$value->fact_id]=false;
                 }
                 if ($value->fact_rutacomprobante=='') {
                     $comprobantecargado[$value->fact_id]=true;
                     
                 } else {
                   $comprobantecargado[$value->fact_id]=false;
                   $ncomprobantescargados++;
                 }
              }
             
              $this->data['comprobantecargado'] = $comprobantecargado;
              $this->data['facturapagada'] =$facturapagada;
              $this->data['comprobantes'] = ($numerocomprobantes==$ncomprobantescargados) ? true : false ;
              $this->data['todopago'] = ($todopago==1) ? false : true ;
              $this->data['completado'] = ($todopago AND $this->data['comprobantes'] ) ? false : true ;
              $this->data['totalpagado'] =$totalpagado;
              $this->data['numerocomprobantes'] =$numerocomprobantes;
              $this->data['ncomprobantescargados'] =$ncomprobantescargados;
              $this->template->set('title', 'Contrato liquidado');
              $this->load->view('liquidaciones/liquidaciones_vercontratoliquidado', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 


  function cargar_comprobante()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $this->data['successmessage']=$this->session->flashdata('message');
              $this->data['errormessage']=''; 
              $this->form_validation->set_rules('numeroarchivos', 'numero archivos', 'trim|xss_clean|numeric|integer|greater_than[0]');
              if ($this->input->post('contratoid')) {
                  $this->form_validation->set_rules('contratoid', 'contrato id', 'trim|xss_clean|numeric|integer|greater_than[0]');
                  $carpeta='facturas';
                  $id=$this->input->post('contratoid');
              
              }
              if ($this->input->post('tramiteid')) {
                  $this->form_validation->set_rules('tramiteid', 'trámite id', 'trim|xss_clean|numeric|integer|greater_than[0]');
                  $carpeta='facturas_tramites';
                  $id=$this->input->post('tramiteid');
              }
              $numeroarchivos=$this->input->post('numeroarchivos');
              
              if ($id >0 && $numeroarchivos > 0 ) {
                  $path = 'uploads/'.$carpeta.'/'.$id;
                  if(!is_dir($path)) { //create the folder if it's not already exists
                      mkdir($path,0777,TRUE);      
                  }
                  $config['upload_path'] = $path;
                  $config['allowed_types'] = 'jpg|jpeg|gif|png';
                  $config['remove_spaces']=TRUE;
                  $config['max_size']    = '2048';
                  //$config['overwrite']    = TRUE;
                  $this->load->library('upload');


                  $success=0;
                  for ($i=0; $i < $numeroarchivos; $i++) {
                      $pago=$this->input->post('pago'.$i);
                      
                      $idfactura=$this->input->post('facturaid'.$i);
                      $config['file_name']=$idfactura.'_'.date("F_d_Y");
                      $this->upload->initialize($config);
                      if ($pago) {
                        $datos = array(
                                 'pago_facturaid' => $idfactura,
                                 'pago_fecha' => date("Y-m-d H:i:s"),
                                 'pago_valor' => $pago,
                                 'pago_metodo' => 'manual',
                               );
                        $this->codegen_model->add('est_pagos',$datos);
                      }
                      
                      
                      $this->form_validation->set_rules('facturaid'.$i, 'factura id '.$i, 'trim|xss_clean|numeric|integer|greater_than[0]'); 
                      if ($this->form_validation->run() == false) {
                          $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
                      } else {

                          if ($this->upload->do_upload("comprobante".$i)) {
                              $file_data= $this->upload->data();
                              $data = array(
                                 'fact_rutacomprobante' => $path.'/'.$file_data['orig_name'],
                                 'fact_fechacomprobante' => date("Y-m-d H:i:s")
                               );
                              if ($this->codegen_model->edit('est_facturas',$data,'fact_id',$idfactura) == TRUE) {
                                  $success++; 
                                    
                              } else {
                                   $this->data['errormessage'] .= '<br>No se pudo registrar el comprobante'.$i;
                              }  
                          } else {
                            $this->data['errormessage'] .=$this->upload->display_errors(); 
                          }  
                      }

                  }
              } else {
                  $this->data['errormessage'] = 'Datos incorrectos'.$this->input->post('contratoid').' ---- '.$numeroarchivos;
              }
              if ($success > 0) {
                $this->session->set_flashdata('successmessage', 'Se Cargó con éxito'); 

              } else {
                $this->session->set_flashdata('errormessage', '<strong>Error!</strong> '.$this->data['errormessage'] );
              }
              $this->session->set_flashdata('accion', 'liquidado');
              if ($this->input->post('contratoid')) {
                  redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);    
              }
              if ($this->input->post('tramiteid')) {
                  redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
              }   
              
              
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }  
  }



function legalizar()
  {        
      if ($this->ion_auth->logged_in()) 
      {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) 
          {
               
               //realiza el proceso de actualización
               //dependiendo del tipo de documento
               //para el que se generó el evento
               if ($this->input->post('contratoid')) 
               {
                   $id=$this->input->post('contratoid');

                   $data = array(
                   'cntr_estadolocalid' => 2,
                   );
                   if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$id) == TRUE) {
                       $this->session->set_flashdata('accion', 'legalizado');
                       $this->session->set_flashdata('successmessage', 'La legalización se realizó con éxito');
                   }

                   redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);
               }

               if ($this->input->post('tramiteid')) 
               {
                   $id=$this->input->post('tramiteid');

                   $data = array(
                   'litr_estadolocalid' => 2,
                   );
                   if ($this->codegen_model->edit('est_liquidartramites',$data,'litr_id',$id) == TRUE) {
                       $this->session->set_flashdata('accion', 'legalizado');
                       $this->session->set_flashdata('successmessage', 'La legalización se realizó con éxito');
                   }

                   redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
               }

                
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }
  } 




function vercontratolegalizado()
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
              $todopago=0;
              $numerocomprobantes=0;
              $ncomprobantescargados=0;
              $totalpagado=0;
              $comprobantecargado=array();
              $facturapagada=array();
              $facturas=$this->data['facturas']; 
              foreach ($facturas as $key => $value) {
                  $totalpagado += $value->pago_valor;
                  $numerocomprobantes++;  
                 if ($value->pago_valor >= $value->fact_valor) {
                     $facturapagada[$value->fact_id]=true;
                 } else {
                    $todopago=1;
                    $facturapagada[$value->fact_id]=false;
                 }
                 if ($value->fact_rutacomprobante=='') {
                     $comprobantecargado[$value->fact_id]=true;
                     
                 } else {
                   $comprobantecargado[$value->fact_id]=false;
                   $ncomprobantescargados++;
                 }
              }
             
              $this->data['comprobantecargado'] = $comprobantecargado;
              $this->data['facturapagada'] =$facturapagada;
              $this->data['comprobantes'] = ($numerocomprobantes==$ncomprobantescargados) ? true : false ;
              $this->data['todopago'] = ($todopago==1) ? false : true ;
              $this->data['completado'] = ($todopago AND $this->data['comprobantes'] ) ? false : true ;
              $this->data['totalpagado'] =$totalpagado;
              $this->data['numerocomprobantes'] =$numerocomprobantes;
              $this->data['ncomprobantescargados'] =$ncomprobantescargados;
              $this->template->set('title', 'Contrato liquidado');
              $this->load->view('liquidaciones/liquidaciones_vercontratolegalizado', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }
function verliquidartramite()
  {        
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $idliquidacion=$this->uri->segment(3);
              
              $this->data['result'] = $this->liquidaciones_model->getliquidartramite($idliquidacion);
              $contrato = $this->data['result'];
              $parametros=$this->codegen_model->get('adm_parametros','para_redondeo,para_salariominimo','para_id = 1',1,NULL,true);
              $this->data['estampillas'] = $this->liquidaciones_model->getestampillastramites($this->data['result']->litr_tramiteid);

              $estampillas=$this->data['estampillas'];   
              $valorsiniva = $parametros->para_salariominimo;
              $totalestampilla= array();
              $valortotal=0;
              
              foreach ($estampillas as $key => $value) {
                
                 $totalestampilla[$value->estm_id] = (($valorsiniva*$value->estr_porcentaje)/100);
                 $totalestampilla[$value->estm_id] = round ( $totalestampilla[$value->estm_id], -$parametros->para_redondeo );
                 $valortotal+=$totalestampilla[$value->estm_id];
              }
              $this->data['est_totalestampilla']=$totalestampilla;
              $this->data['cnrt_valorsiniva']=$valorsiniva;
              $this->data['est_valortotal']=$valortotal;
              $this->template->set('title', 'Editar contrato');
              $this->load->view('liquidaciones/liquidaciones_verliquidartramite', $this->data); 
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 
 
 function procesarliquidaciontramite()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
               $codigo='00000000';
               $idtramite=$this->input->post('idcontrato');
              $data = array(
                   'liqu_tramiteid' => $this->input->post('idcontrato'),
                   'liqu_nombrecontratista' => $this->input->post('nombrecontratista'),
                   'liqu_nit' => $this->input->post('nit'),
                   'liqu_valorsiniva' => $this->input->post('valorsiniva'),
                   'liqu_totalestampilla' => $this->input->post('totalestampillas'),
                   'liqu_valortotal' => $this->input->post('valortotal'),
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
                       'fact_rutaimagen' => $this->input->post('rutaimagen'.$i),
                       );
                       $this->codegen_model->add('est_facturas',$data);
                  }

                  //print_r($data);
                  $data = array(
                   'litr_estadolocalid' => 1,
                   );
                  if ($this->codegen_model->edit('est_liquidartramites',$data,'litr_id',$idtramite) == TRUE) {
                      
                      $this->session->set_flashdata('successmessage', 'La liquidación se realizó con éxito');
                      $this->session->set_flashdata('accion', 'liquidado');
                      redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$idtramite);
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

  function vertramiteliquidado()
 {        
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $idcontrato=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibostramites($idcontrato);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);
              $todopago=0;
              $numerocomprobantes=0;
              $ncomprobantescargados=0;
              $totalpagado=0;
              $comprobantecargado=array();
              $facturapagada=array();
              $facturas=$this->data['facturas']; 
              foreach ($facturas as $key => $value) {
                  $totalpagado += $value->pago_valor;
                  $numerocomprobantes++;  
                 if ($value->pago_valor >= $value->fact_valor) {
                     $facturapagada[$value->fact_id]=true;
                 } else {
                    $todopago=1;
                    $facturapagada[$value->fact_id]=false;
                 }
                 if ($value->fact_rutacomprobante=='') {
                     $comprobantecargado[$value->fact_id]=true;
                     
                 } else {
                   $comprobantecargado[$value->fact_id]=false;
                   $ncomprobantescargados++;
                 }
              }
             // print_r($facturapagada);
              $this->data['comprobantecargado'] = $comprobantecargado;
              $this->data['facturapagada'] =$facturapagada;
              $this->data['comprobantes'] = ($numerocomprobantes==$ncomprobantescargados) ? true : false ;
              $this->data['todopago'] = ($todopago==1) ? false : true ;
              $this->data['completado'] = ($todopago AND $this->data['comprobantes'] ) ? false : true ;
              $this->data['totalpagado'] =$totalpagado;
              $this->data['numerocomprobantes'] =$numerocomprobantes;
              $this->data['ncomprobantescargados'] =$ncomprobantescargados;
              $this->template->set('title', 'Contrato liquidado');
              //$this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_vercontratoliquidado', $this->data);
              $this->load->view('liquidaciones/liquidaciones_vertramiteliquidado', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 



  function vertramitelegalizado()
 {        
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $idtramite=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibostramites($idtramite);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);
              $todopago=0;
              $numerocomprobantes=0;
              $ncomprobantescargados=0;
              $totalpagado=0;
              $comprobantecargado=array();
              $facturapagada=array();
              $facturas=$this->data['facturas']; 
              foreach ($facturas as $key => $value) {
                  $totalpagado += $value->pago_valor;
                  $numerocomprobantes++;  
                 if ($value->pago_valor >= $value->fact_valor) {
                     $facturapagada[$value->fact_id]=true;
                 } else {
                    $todopago=1;
                    $facturapagada[$value->fact_id]=false;
                 }
                 if ($value->fact_rutacomprobante=='') {
                     $comprobantecargado[$value->fact_id]=true;
                     
                 } else {
                   $comprobantecargado[$value->fact_id]=false;
                   $ncomprobantescargados++;
                 }
              }
             // print_r($facturapagada);
              $this->data['comprobantecargado'] = $comprobantecargado;
              $this->data['facturapagada'] =$facturapagada;
              $this->data['comprobantes'] = ($numerocomprobantes==$ncomprobantescargados) ? true : false ;
              $this->data['todopago'] = ($todopago==1) ? false : true ;
              $this->data['completado'] = ($todopago AND $this->data['comprobantes'] ) ? false : true ;
              $this->data['totalpagado'] =$totalpagado;
              $this->data['numerocomprobantes'] =$numerocomprobantes;
              $this->data['ncomprobantescargados'] =$ncomprobantescargados;
              $this->template->set('title', 'Contrato liquidado');
              //$this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_vercontratoliquidado', $this->data);
              $this->load->view('liquidaciones/liquidaciones_vertramitelegalizado', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }


 function addtramite()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {

              $this->data['successmessage']=$this->session->flashdata('message');  

              $this->form_validation->set_rules('encontrado', 'encontrado','required|trim|xss_clean|numeric');
              $this->form_validation->set_rules('documento', 'Documento',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('nombre', 'Nombre',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('tramiteid', 'Trámite','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('observaciones', 'Observaciones','trim|xss_clean'); 
              
              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    
                  if ($this->input->post('encontrado')>0) {
                      $contratista= $this->codegen_model->get('con_contratistas','cont_id','cont_nit = '.$this->input->post('documento'),1,NULL,true);
                      $contratistaid= $contratista->cont_id;
                  } else {
                      $datos = array(
                        'cont_nit' => $this->input->post('documento'),
                        'cont_nombre' => $this->input->post('nombre')
                      );
                      $this->codegen_model->add('con_contratistas',$datos);
                      $contratistaid=$this->db->insert_id();
                  }
                  


                  $data = array(
                        'litr_tramiteid' => $this->input->post('tramiteid'),
                        'litr_contratistaid' => $contratistaid,
                        'litr_fechaliquidacion' => date("Y-m-d H:i:s"),
                        'litr_estadolocalid' => 0,
                        'litr_observaciones' => $this->input->post('observaciones')
                     );
                 
                  if ($this->codegen_model->add('est_liquidartramites',$data) == TRUE) {
                      $id=$this->db->insert_id();
                      $this->session->set_flashdata('message','La liquidación se realizó con éxito');
                      $this->session->set_flashdata('accion', 'creado');
                      redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
                  } else {

                      $this->data['errormessage'] = 'No se pudo realizar la liquidación';
                  }

              }
              $this->template->set('title', 'Liquidar trámites');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );  
              $this->data['tramites']  = $this->codegen_model->getSelect('est_tramites','tram_id,tram_nombre');
              $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_addtramite', $this->data);
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 
  

  function consultardocumento()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
            
              $contratista= $this->codegen_model->get('con_contratistas','cont_id,cont_nombre','cont_nit = '.$this->input->post('documento'),1,NULL,true);
              if ($contratista) {
                 echo $contratista->cont_nombre;
              } else {
                echo 0;
              }
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 

 function tramites_datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('l.litr_id,co.cont_nit,co.cont_nombre,tr.tram_nombre,l.litr_fechaliquidacion,l.litr_observaciones,el.eslo_nombre');
              $this->datatables->from('est_liquidartramites l');
              $this->datatables->join('con_contratistas co', 'co.cont_id = l.litr_contratistaid', 'left');
              $this->datatables->join('est_tramites tr', 'tr.tram_id = l.litr_tramiteid', 'left');
              $this->datatables->join('con_estadoslocales el', 'el.eslo_id = l.litr_estadolocalid', 'left');
              $this->datatables->add_column('edit', '-');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
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
              $this->datatables->add_column('edit', '-');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }        
  }



  function procesarConsecutivos()
  {
    if ($this->ion_auth->logged_in())
    {
          //verifica que el usuario que llama el metodo
          //tenga perfil de liquidador
          $usuarioLogueado=$this->ion_auth->user()->row();

          if ($usuarioLogueado->perfilid==4)
          {
              $validacionPapeleriaAsignada = $this->codegen_model->getSelect('est_papeles',
                  'pape_codigoinicial, pape_codigofinal', ' where pape_usuario = '.$usuarioLogueado->id);

              
              if($validacionPapeleriaAsignada)
              {

                  if ($this->uri->segment(3)=='')
                  {
                      redirect(base_url().'index.php/error_404');
                  
                  } else 
                       {
                           $idFactura = $this->uri->segment(3);

                           $codigo='00000000';

                           $ObjetoFactura = $this->liquidaciones_model->getfacturaIndividual($idFactura);

    
                           //extrae el ultimo codigo de papeleria resgistrado
                           //en las impresiones para el liquidador autenticado
                           $tablaJoin='est_papeles';
                           $equivalentesJoin='est_impresiones.impr_papelid = est_papeles.pape_id';
                           $where='est_papeles.pape_usuario ='.$usuarioLogueado->id;

                           $max = $this->codegen_model->max('est_impresiones','impr_codigopapel',$where, $tablaJoin, $equivalentesJoin);

                           //verifica si ya habia asignado por lo menos
                           //un consecutivo a una impresion
                           //de lo contrario elige el primer codigo

                           if((int)$max['impr_codigopapel']>0)
                           {
                                $nuevoingreso=$max['impr_codigopapel']+1;
                           }else
                               {
                                     //extrae el primer codigo de papeleria resgistrado
                                     //en los rangos de papel asginado al liquidador autenticado
                                     $where='est_papeles.pape_usuario ='.$usuarioLogueado->id;
                                     $primerCodigo = $this->codegen_model->min('est_papeles','pape_codigoinicial',$where);
                                     $nuevoingreso = (int)$primerCodigo['pape_codigoinicial'];
                               }
                       

                           //extrae los posibles rangos de papeleria asignados
                           //al usuario que se encuentra logueado que debe ser
                           //un liquidador
                   
                           $papeles = $this->codegen_model->get('est_papeles','pape_id'
                           .',pape_codigoinicial,pape_codigofinal',
                           'pape_codigoinicial <= '.$nuevoingreso
                           .' AND pape_codigofinal >='
                           .$nuevoingreso
                           .' AND pape_usuario = '.$usuarioLogueado->id,1,NULL,true);


                           //verifica que exista un rango de papeleria asignado
                           //al liquidador en el que se encuentre el posible
                           //codigo a registrar
                           if ($papeles)
                           {
                           
                               //comprueba si ya se está usando el codigo del papel
                               $nousado=0;

                               while ($nousado==0)
                               {
                                   $combrobacionImpresiones = $this->codegen_model->get('est_impresiones','impr_id','impr_codigopapel = '.$nuevoingreso,1,NULL,true);
    
                                   if (!$combrobacionImpresiones) 
                                   {
                                       $nousado=1;
                                   } else
                                       {
                                       $nuevoingreso++;
                                       }
                                }

                                //verifica si no se encuentra asignada papeleria
                                //a esa factura en la tabla de impresiones
                                //para crear el registro de la impresion
                                $impresiones = $this->codegen_model->get('est_impresiones','impr_id,impr_estado','impr_facturaid = '.$ObjetoFactura[0]->fact_id,1,NULL,true);
                                if (!$impresiones)
                                {
    
                                    $data = array(
                                    'impr_codigopapel' => $nuevoingreso,
                                    'impr_papelid' => $papeles->pape_id,
                                    'impr_facturaid' => $ObjetoFactura[0]->fact_id,
                                    'impr_observaciones' => 'Correcta',
                                    'impr_fecha' => date('Y-m-d H:i:s',now()),
                                    'impr_codigo' => $codigo,
                                    'impr_estado' => '1'
                                    );
    
                                    //extrae la cantidad actual impresa para el rango
                                    //de papeleria de donde se sacará el consecutivo
                                    //luego aumenta ese valor y lo actualiza en la bd
                                    $cantidadImpresa = $this->codegen_model->getSelect('est_papeles','pape_imprimidos',
                                    'pape_usuario = '.$usuarioLogueado->id
                                    .' AND pape_id = '.$papeles->pape_id);
                                    
                                    $cantidadNeta=(int)$cantidadImpresa['pape_imprimidos'];
                                                            
                                    $this->codegen_model->edit('est_papeles',
                                    ['pape_imprimidos'=>$cantidadNeta+1],
                                    'pape_id', $papeles->pape_id);
                                
                                    $this->codegen_model->add('est_impresiones',$data);
                                }else
                                    {
                                        $this->session->set_flashdata('errormessage', '<strong>Error!</strong> Ya se ha impreso la Factura No.'.$ObjetoFactura[0]->fact_id.' !');
                                        redirect(base_url().'index.php/liquidaciones/liquidar'); 
                                    }
                                
                            } else
                                {
                                    $this->session->set_flashdata('errormessage', '<strong>Error!</strong> No hay papeleria disponible para realizar esta impresión!');
                                    redirect(base_url().'index.php/liquidaciones/liquidar'); 
                                }

                        }     


              }else
                {
                     $this->session->set_flashdata('errormessage', '<strong>Error!</strong> No se puede imprimir, usted no tiene asignada papeleria!');
                     redirect(base_url().'index.php/liquidaciones/liquidar');  
                }

          }else 
              {
                  redirect(base_url().'index.php/error_404');
              }
    }else 
        {
            redirect(base_url().'index.php/users/login');
        }


  }



}
