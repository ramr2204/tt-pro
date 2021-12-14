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

class Contratos extends MY_Controller {
    
	function __construct() 
	{
      	parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);
		$this->load->model('liquidaciones_model','',TRUE);
		$this->load->helper('Equivalencias');
	}
	
	function index()
  {
		  $this->manage();
	}

	function manage()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar contratos');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js',
                        'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                       );
            
              $this->template->load($this->config->item('admin_template'),'contratos/contratos_list', $this->data);

          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }

    /**
     * Procesa el renderizado de la vista de creacion
     * y el registro del mismo
     * 
     * @return null
     */
    public function add()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/add'))
            {
                $this->data['successmessage'] = $this->session->flashdata('message');

                $respuestaRegistro = $this->registrarContrato();

                if($respuestaRegistro['exito'])
                {
                    $this->session->set_flashdata('message', 'El contrato se ha creado con éxito');
                    redirect(base_url().'index.php/contratos/add');
                } else {
                    $this->data['errormessage'] = $respuestaRegistro['error'];
                }

                $this->data['style_sheets'] = [
                    'css/chosen.css' => 'screen',
                    'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen'
                ];
                $this->data['javascripts'] = [
                    'js/chosen.jquery.min.js',
                    'js/plugins/bootstrap/moment.js',
                    'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                    'js/autoNumeric.js'
                ];
                $this->template->set('title', 'Ingreso manual de contrato');

                $this->data['tiposcontratos']           = $this->codegen_model->getSelect('con_tiposcontratos','tico_id,tico_nombre');
                $this->data['contratistas']             = $this->codegen_model->getSelect('con_contratistas','cont_id,cont_nombre,cont_nit');
                $this->data['contratantes']             = $this->codegen_model->getSelect('con_contratantes', 'id,nombre,nit');
                $this->data['municipios']               = $this->codegen_model->getSelect('par_municipios','muni_id,muni_nombre', 'WHERE muni_departamentoid = 6');
                $this->data['clasificacion_contrato']   = Equivalencias::clasificacionContratos();
                $this->data['contrato_normal']          = Equivalencias::contratoNormal();

                $this->template->load($this->config->item('admin_template'),'contratos/contratos_add', $this->data);
            } else {
                redirect(base_url().'index.php/error_404');
            }

        } else {
            redirect(base_url().'index.php/users/login');
        }
    }

    /**
     * Procesa el registro del contrato
     * (sin redirecciones o validaciones de usuario)
     * 
     * @return array
     */
    private function registrarContrato()
    {
        $respuesta = [
            'exito' => false,
            'error' => '',
            'id' => null
        ];

        /*
        * Extrae el usuario autenticado para establecer que usuario
        * creó el contrato
        */
        $usuario = $this->ion_auth->user()->row();
        
        /*
        * Se da formato al valor del contrato para que guarde los valores decimales
        */
        $valor = str_replace(',', '.', str_replace('.','',$this->input->post('valor')));

        $vigencia = explode("-", $this->input->post('fecha'));

        $this->form_validation->set_rules('cntr_municipio_origen', 'Municipio Origen','required|trim|xss_clean|numeric|is_exists[par_municipios.muni_id]');
        $this->form_validation->set_rules('contratistaid', 'contratista','required|trim|xss_clean|numeric|is_exists[con_contratistas.cont_id]');
        $this->form_validation->set_rules('contratanteid', 'contratante', 'required|trim|xss_clean|numeric|is_exists[con_contratantes.id]');
        $this->form_validation->set_rules('tipocontratoid', 'Tipo de contrato','required|trim|xss_clean|numeric|is_exists[con_tiposcontratos.tico_id]');
        $this->form_validation->set_rules('fecha', 'Fecha', [
            'required',
            'trim',
            'xss_clean',
            'regex_match[/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/]',
        ]);
        $this->form_validation->set_rules('objeto', 'objeto',  'required|trim|xss_clean');  
        $this->form_validation->set_rules('numero', 'Número','required|trim|xss_clean|numeric|greater_than[0]');
        $this->form_validation->set_rules('valor', 'valor','required|trim|xss_clean');
        $this->form_validation->set_rules('clasificacion_contrato', 'Clasificación del contrato','required|trim|xss_clean|numeric|greater_than[0]');

        $aplica_numero_relacionado = $this->input->post('clasificacion_contrato') != Equivalencias::contratoNormal();

        if($aplica_numero_relacionado){
            $this->form_validation->set_rules('contrato_relacionado', 'Número de contrato relacionado','required|trim|xss_clean|is_exists[con_contratos.cntr_numero]');
        }

        if ($this->form_validation->run() == false) {
            $respuesta['error'] = $this->form_validation->error_string();
        }
        else
        {
            if($this->input->post('clasificacion_contrato') == Equivalencias::contratoModificacion())
            {
                $contrato_relacionado = $this->codegen_model->get(
                    'con_contratos',
                    'cntr_id',
                    'cntr_numero = '. $this->input->post('contrato_relacionado') .' AND cntr_vigencia = "'. $vigencia[0] .'"',
                    1,NULL,true
                );

                $this->codegen_model->edit(
                    'con_contratos',
                    ['cntr_estadolocalid' => Equivalencias::contratoModificado()],
                    'cntr_id',$contrato_relacionado->cntr_id
                );
            }

            /*
            * Valida que el contratista exista en la base de datos
            */
            $objContratista = $this->codegen_model->get('con_contratistas','cont_regimenid','cont_id = '.$this->input->post('contratistaid'),1,NULL,true);
            $objContratante = $this->codegen_model->get('con_contratantes', 'regimenid', 'id = ' . $this->input->post('contratanteid'), 1, null, true);

            $msjError = '';
            $bandContinuar = true;
            if(count($objContratista) <= 0)
            {
                $bandContinuar = false;
                $msjError .= '<br>No existe el contratista seleccionado!';
            }

            if(count($objContratante) <= 0)
            {
                $bandContinuar = false;
                $msjError .= '<br>No existe el contratante seleccionado!';
            }

            if($bandContinuar)
            {
                $data = [
                    'cntr_contratistaid'	=> $this->input->post('contratistaid'),
                    'cntr_contratanteid'	=> $this->input->post('contratanteid'),
                    'cntr_tipocontratoid'	=> $this->input->post('tipocontratoid'),
                    'cntr_fecha_firma'		=> $this->input->post('fecha'),
                    'cntr_numero'			=> $this->input->post('numero'),
                    'cntr_objeto'			=> $this->input->post('objeto'),
                    'cntr_valor'			=> $valor,
                    'cntr_vigencia'			=> $vigencia[0],
                    'fecha_insercion'		=> date('Y-m-d H:i:s'),
                    'cntr_usuariocrea'		=> $usuario->id,
                    'cntr_municipio_origen'	=> $this->input->post('cntr_municipio_origen'),
                    'clasificacion'			=> $this->input->post('clasificacion_contrato'),
                    'numero_relacionado'	=> $aplica_numero_relacionado ? $this->input->post('contrato_relacionado') : null
                ];

                /*
                * Valida si el tipo de régimen es otros
                */
                $registrarContrato = true;
                if($objContratista->cont_regimenid == 6 || $objContratista->cont_regimenid == 8)
                {
                    /*
                    * Valida que se haya recibido valor de IVA otros
                    */
                    if($this->input->post('valor_iva_otros') == '')
                    {
                        $respuesta['error'] = 'Debe suministrar el valor del IVA!';
                        $registrarContrato = false;
                    }else
                        {
                            $data['cntr_iva_otros'] = str_replace('.','',$this->input->post('valor_iva_otros'));
                        }
                }

                /*
                * Valida si se debe registrar el contrato
                */
                if($registrarContrato)
                {
                    $respuestaProceso = $this->codegen_model->add('con_contratos',$data);
                    if ($respuestaProceso->bandRegistroExitoso) 
                    {
                        $respuesta['exito'] = true;
                        $respuesta['id'] = $respuestaProceso->idInsercion;
                    }else
                        {
                            $respuesta['error'] = 'No se pudo registrar el contrato';
                        }
                }
            }else
                {
                    $respuesta['error'] = $msjError;
                }
        }

        $this->form_validation->reset_validation();
        return $respuesta;
    }

	function edit()
	{
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/edit')) {  

              $idcontrato = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idcontrato==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contrato para editar');
                  redirect(base_url().'index.php/contratos');
              }
              
              $this->data['successmessage'] = $this->session->flashdata('message');
              $valor = str_replace(',', '.', str_replace('.', '', $this->input->post('valor')));
              $vigencia = explode("-", $this->input->post('fecha'));

              $this->form_validation->set_rules('cntr_municipio_origen', 'Municipio Origen','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('contratistaid', 'contratista','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('contratanteid', 'contratante', 'required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('tipocontratoid', 'Tipo de contrato','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('fecha', 'Fecha',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('objeto', 'objeto',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('numero', 'Número','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('valor', 'valor','required|trim|xss_clean'); 
			  $this->form_validation->set_rules('clasificacion_contrato', 'Clasificación del contrato','required|trim|xss_clean|numeric|greater_than[0]');

			  $aplica_numero_relacionado = $this->input->post('clasificacion_contrato') != Equivalencias::contratoNormal();

              if($aplica_numero_relacionado){
                $this->form_validation->set_rules('contrato_relacionado', 'Número de contrato relacionado','required|trim|xss_clean|is_exists[con_contratos.cntr_numero]');
              }

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {

                    /*
                     * Valida que el contratista exista en la base de datos
                     */
                    $objContratista = $this->codegen_model->get('con_contratistas', 'cont_regimenid', 'cont_id = ' . $this->input->post('contratistaid'), 1, null, true);
                    $objContratante = $this->codegen_model->get('con_contratantes', 'regimenid', 'id = ' . $this->input->post('contratanteid'), 1, null, true);

                    $msjError = '';
                    $bandContinuar = true;
                    if (count($objContratista) <= 0) 
                    {
                        $bandContinuar = false;
                        $msjError .= '<br>No existe el contratista seleccionado!';
                    }

                    if (count($objContratante) <= 0) 
                    {
                        $bandContinuar = false;
                        $msjError .= '<br>No existe el contratante seleccionado!';
                    }

                    if ($bandContinuar)
                    {
                        $data = array(
                            'cntr_contratistaid' => $this->input->post('contratistaid'),
                            'cntr_contratanteid' => $this->input->post('contratanteid'),
                            'cntr_tipocontratoid' => $this->input->post('tipocontratoid'),
                            'cntr_fecha_firma' => $this->input->post('fecha'),
                            'cntr_numero' => $this->input->post('numero'),
                            'cntr_objeto' => $this->input->post('objeto'),
                            'cntr_valor' => $valor,
                            'cntr_vigencia' => $vigencia[0],
                            'cntr_municipio_origen' => $this->input->post('cntr_municipio_origen'),
                            'clasificacion'			=> $this->input->post('clasificacion_contrato'),
                            'numero_relacionado'	=> $aplica_numero_relacionado ? $this->input->post('contrato_relacionado') : null,
						);

                        /*
                        * Valida si el tipo de régimen es otros
                        */
                        $registrarContrato = true;
                        if($objContratista->cont_regimenid == 6 || $objContratista->cont_regimenid == 8)
                        {
                            /*
                            * Valida que se haya recibido valor de IVA otros
                            */
                            if($this->input->post('valor_iva_otros') == '')
                            {
                                $this->data['errormessage'] = 'Debe suministrar el valor del IVA!';
                                $registrarContrato = false;
                            }else
                                {
                                    $data['cntr_iva_otros'] = str_replace('.','',$this->input->post('valor_iva_otros'));
                                }
                        }

                        /*
                        * Valida si se debe registrar el contrato
                        */
                        if($registrarContrato)
                        {
                            if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$idcontrato) == TRUE) 
                            {
                                $this->session->set_flashdata('message', 'El contrato se ha editado con éxito');
                                redirect(base_url().'index.php/contratos/edit/'.$idcontrato);
                            }else
                                {
                                    $this->data['errormessage'] = 'No se pudo modificar el contrato';
                                }
                        }
                    }else
                        {
                            $this->data['errormessage'] = $msjError;
                        }
              }   
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/moment.js',
                        'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                        'js/autoNumeric.js'
                    );

                $this->data['result'] = $this->codegen_model->get(
                    'con_contratos',
                    'cont_regimenid,cntr_id,cntr_contratistaid,cntr_contratanteid,cntr_municipio_origen,
                        cntr_tipocontratoid,cntr_fecha_firma,cntr_numero,cntr_objeto,cntr_valor,
                        cntr_iva_otros,clasificacion,numero_relacionado',
                    'cntr_id = '.$idcontrato,
                    1,NULL,true,array(),
                    'con_contratistas',
                    'con_contratistas.cont_id = con_contratos.cntr_contratistaid'
                );
                $this->data['tiposcontratos']  = $this->codegen_model->getSelect('con_tiposcontratos','tico_id,tico_nombre');
                $this->data['contratistas']  = $this->codegen_model->getSelect('con_contratistas','cont_id,cont_nombre,cont_nit');
                $this->data['contratantes'] = $this->codegen_model->getSelect('con_contratantes', 'id,nombre,nit');
                $this->data['municipios']  = $this->codegen_model->getSelect('par_municipios','muni_id,muni_nombre', 'WHERE muni_departamentoid = 6');
				$this->data['clasificacion_contrato']  = Equivalencias::clasificacionContratos();
                $this->data['contrato_normal']  = Equivalencias::contratoNormal();
                $this->template->set('title', 'Editar contrato');
                $this->template->load($this->config->item('admin_template'),'contratos/contratos_edit', $this->data);

          }else {
              redirect(base_url().'index.php/error_404');
          }
      } else {
          redirect(base_url().'index.php/users/login');
      }
        
  }
	
  function delete()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contrato para eliminar');
                  redirect(base_url().'index.php/contratos');
              }
              // if (!$this->codegen_model->depend('con_contratos','cntr_contratoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_contratos','cntr_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El contrato se ha eliminado con éxito');
                  redirect(base_url().'index.php/contratos');  

              // } else {

              //     $this->session->set_flashdata('errormessage', 'El contrato se encuentra en uso, no es posible eliminarlo.');
              //     redirect(base_url().'index.php/contratos/edit/'.$this->input->post('id'));

              // }
                         
          } else {
              redirect(base_url().'index.php/error_404');       
          } 
      } else {
          redirect(base_url().'index.php/users/login');
      }
  }

  function importarcontratos()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/importarcontratos')) {

              $this->data['successmessage']=$this->session->flashdata('successmessage');  
              $this->data['errormessage']=$this->session->flashdata('errormessage'); 
              $this->data['infomessage']=$this->session->flashdata('infomessage'); 
              $this->form_validation->set_rules('vigencia', 'Vigencia','required|trim|xss_clean|numeric|greater_than[0]');
     

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $vigencia=$this->input->post('vigencia');
                 
                  //WEB SERVICE a contratos en SISCON
                  if (function_exists('curl_init')) 
                  {
                      
                      $ch = curl_init();
                      //asignamos la direccion al cual se conecta
                      //direccion a servidor 19 de pruebas -->http://192.168.77.19/siscon/main/modulos/informes/general/contratos.php?vige=".$vigencia
                      curl_setopt($ch, CURLOPT_URL,"http://190.121.133.172:81/siscon/main/modulos/informes/general/contratos.php?vige=".$vigencia);
                      //el tiempo maximo de respuesta
                      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                      //hace que cada que realice la peticion cree una nueva
                      curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);                      
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                      // se guarda el resultado obtenido
                      $response = curl_exec ($ch);
                      curl_close($ch);
                      //se imprime
                               
                      $result = json_decode($response); 
                      $contratos = $result->contratos;
                      $contratistas = $result->contratistas;
                                            
                  }

                  $contratos_nuevos=0;
                  $contratos_importados=0;
                  $contratos_falloimpotacion=0;
                  $contratistas_nuevos=0;
                  $contratistas_importados=0;
                  $contratistas_falloimpotacion=0;
                   
                  //Se extraen los nombres de los campos
                  //pre-formateados en la API para los contratos
                  foreach ($contratos[0] as $key => $value) 
                   {                       
                       $camposContratos[] = $key;                    
                   } 


                  //Se extraen los nombres de los campos
                  //pre-formateados en la API para los contratistas
                  foreach ($contratistas[0] as $key => $value) 
                   {                       
                       $camposContratistas[] = $key;                    
                   } 
                   
                  //Recorre los contratos obtenidos del API
                  //y verifica que el contrato no exista en
                  //la base de datos local 
                  foreach ($contratos as $contrato) 
                  {
                      
                      if ($contrato) 
                      {
                      
                          $datos_contrato = $this->codegen_model->get('con_contratos','cntr_id','cntr_numero = '.$contrato->cntr_numero.' AND cntr_vigencia = '.$contrato->cntr_vigencia,1,NULL,true);                          

                          // cargamos contratos nuevos
                          if (!$datos_contrato) 
                          {           

                               //Recorre los contratistas obtenidos del API
                               //y verifica que el contratista no exista en
                               //la base de datos local 
                               foreach ($contratistas as $contratista) 
                               {
                                   
                                   if ($contratista->contrato_remoto == $contrato->contrato_remoto ) 
                                   {
                                       $datos_contratista = $this->codegen_model->get('con_contratistas','cont_id','cont_nit = '.$contratista->cont_nit,1,NULL,true);
             
                                       // cargamos contratistas nuevos
                                       if (!$datos_contratista) 
                                       {
                                                                                            
                                           $contratistas_nuevos++;
             
                                           $data = array();
                                           //Crea el arreglo para el query según los campos
                                           //codificados en el API                              
                                           foreach ($camposContratistas as $value) 
                                           {    
                                                if($value != 'contrato_remoto')
                                                {
                                                    $data [$value] = $contratista->$value; 
                                                }
                                                
                                           }
                                           
                                            $respuestaProceso = $this->codegen_model->add('con_contratistas',$data);
                                           if ($respuestaProceso->bandRegistroExitoso) {
                                               $contratistas_importados++;     

                                               //capturamos el id del nuevo contratista
                                               //para establecerlo como foreign key
                                               //en el contrato que se importará
                                               $tistaid = $respuestaProceso->idInsercion;
                                           } else {
             
                                               $contratistas_falloimpotacion=0;
                                           }
             
                                        }else
                                            {
                                                 $tistaid=$datos_contratista->cont_id; 
                                            }
             
                                        //sale del foreach de los contratistas si encontró
                                        //coincidencia
                                        break;  
                                    }
                                } 


                              $contratos_nuevos++;

                              //se establece el id del contratista nuevo o existente
                              //en la bd local como foreign key del contrato importado
                              $contrato->cntr_contratistaid = $tistaid;
                              
                              $data = array();
                              //Crea el arreglo para el query según los campos
                              //codificados en el API
                              foreach ($camposContratos as $value) 
                              {    
                                   if($value != 'contrato_remoto')
                                   {
                                       $data [$value] = $contrato->$value; 
                                   }
                                   
                              } 
                              //establece el estado local del contrato
                              //como no liquidado
                              $data['cntr_estadolocalid'] = 0;
                              
                                $respuestaProceso = $this->codegen_model->add('con_contratos',$data);
                              if ($respuestaProceso->bandRegistroExitoso) {
                                  $contratos_importados++;     
                              } else {

                                  $contratos_falloimpotacion=0;
                              }

                          }

                      }
                  }                  
                  
                               
                   if ($contratos_importados>0 || $contratistas_nuevos>0) {
                       $this->session->set_flashdata('successmessage', 'Se importaron '.$contratos_importados.' contratos y '.$contratistas_importados.' contratistas con éxito');
                   }
                   if ($contratos_falloimpotacion>0 || $contratistas_falloimpotacion>0) {
                     $this->session->set_flashdata('errormessage', 'No se pudo completar la importación de  '.$contratos_falloimpotacion.' contratos y/o '.$contratistas_falloimpotacion.' contratistas');
                   } 
                   if ($contratos_nuevos<1 || $contratos_importados<1) {
                     $this->session->set_flashdata('infomessage', 'No se encontraron nuevos contratos ni nuevos contratistas, la base de datos está actualizada');
                   } 
                    redirect(base_url().'index.php/contratos/importarcontratos');

              }
              $this->template->set('title', 'Nueva aplicación');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );  
              $vigencia_actual=date('Y');
              $vigencias=array();
              for ($i=0; $i < 10 ; $i++) { 
                  $vigencias[]=$vigencia_actual-$i;
              }
              $this->template->set('title', 'Importación de contratos');
              $this->data['vigencias']  = $vigencias;
              $this->template->load($this->config->item('admin_template'),'contratos/contratos_importarcontratos', $this->data);
             
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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/liquidar') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('c.cntr_id,c.cntr_numero,co.cont_nit,co.cont_nombre,c.cntr_fecha_firma,c.cntr_objeto,c.cntr_valor,el.eslo_nombre');
              $this->datatables->from('con_contratos c');
              $this->datatables->join('con_contratistas co', 'co.cont_id = c.cntr_contratistaid', 'left');
              $this->datatables->join('con_estadoslocales el', 'el.eslo_id = c.cntr_estadolocalid', 'left');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/contratos/edit/$1" class="btn btn-default btn-xs agrega" title="Editar contrato" id="$1"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 'c.cntr_id');

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


 
  function datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/manage') ) { 
              
            /*
            * Se Valida si el usuario tiene la opcion de editar contratos
            * para renderizar el boton de editar
            */ 
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/edit')) 
            {
                $this->load->library('datatables');
                $this->datatables->add_column('edit', '<div class="btn-toolbar">
                    <div class="btn-group">
                    <a href="'.base_url().'index.php/contratos/edit/$1" class="btn btn-default btn-xs agrega" title="Editar contrato" id="$1"><i class="fa fa-pencil-square-o"></i></a>
                    </div>
                    </div>', 'c.cntr_id');
            }else 
                {
                    $this->load->library('datatables');
                    $this->datatables->add_column('edit', '', 'c.cntr_id'); 
                }
              
              $this->datatables->select('c.cntr_id,c.cntr_numero,co.cont_nit,co.cont_nombre,ctte.nit,ctte.nombre,c.cntr_fecha_firma,c.cntr_objeto,c.cntr_valor,c.cntr_vigencia');
              $this->datatables->from('con_contratos c');
              $this->datatables->join('con_contratistas co', 'co.cont_id = c.cntr_contratistaid', 'left');
              $this->datatables->join('con_contratantes ctte', 'ctte.id = c.cntr_contratanteid', 'left');

              $helper = new HelperGeneral;
              $verificacion = $helper->verificarRestriccionEmpresa();

              if($verificacion !== true) {
                  $this->datatables->where('c.cntr_contratanteid = "'. $verificacion .'"');
              }

              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }

    /*
    * Funcion de apoyo que valida el tipo de regimen del contratista
    * para verificar si es o no tipo otros (6)
    */
    public function validarRegimen()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if(isset($_POST['idContratista']) && $_POST['idContratista'] != '0')
            {
                /*
                * Valida que el contratista exista en la base de datos
                */
                $objContratista = $this->codegen_model->get('con_contratistas','cont_regimenid','cont_id = '.$_POST['idContratista'],1,NULL,true);

                if(count($objContratista) > 0)
                {
                    /*
                    * Valida si el tipo de régimen es otros
                    */
                    $esOtros = 'NO';
                    if($objContratista->cont_regimenid == 6 || $objContratista->cont_regimenid == 8)
                    {
                        $esOtros = 'SI';
                    }

                    echo json_encode(array('msj' => '', 'es_otros' => $esOtros));
                }else
                    {
                        echo json_encode(array('msj' => 'No existe el contratista seleccionado!'));
                    }
            }
        }else
            {
                redirect(base_url().'index.php/users/login');
            }
    }

    /**
     * Renderiza la vista para cargar los contratos
     * 
     * @return null
     */
    public function importarContratosExcel()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/importarcontratos'))
            {
                $this->data['successmessage'] = $this->session->flashdata('successmessage');
                $this->data['errormessage'] = $this->session->flashdata('errormessage');
                $this->data['infomessage'] = $this->session->flashdata('infomessage');

                $this->template->set('title', 'Importar Contratos');

                $this->data['style_sheets'] = [
                    'css/chosen.css' => 'screen'
                ];
                $this->data['javascripts'] = [
                    'js/chosen.jquery.min.js'
                ];

                $this->template->load($this->config->item('admin_template'),'contratos/importar', $this->data);
            } else {
                redirect(base_url().'index.php/error_404');
            }
        } else {
            redirect(base_url().'index.php/users/login');
        }
    }

    /**
     * Procesa la subida del cargue de contratos
     * 
     * @return null
     */
    public function cargarImportarContratos()
    {
        $path = 'uploads/temporal/excel/';
        if(!is_dir($path))
        {
            mkdir($path,0777,TRUE);
        }

		$config['upload_path'] = $path;
		$config['allowed_types'] = 'xls|xlsx';
		$config['max_size'] = '0';
        $config['remove_spaces']= TRUE;
        $config['max_size'] = '3000';
        $config['overwrite'] = TRUE;
        $config['file_name'] = 'contratos_'. time() .'_'. rand(1, 100);

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('archivo') ){
            $this->session->set_flashdata('errormessage', 'Ocurrio un problema al cargar el archivo.');
            redirect(base_url().'index.php/contratos/importarContratosExcel');
            exit();
		}

        $this->load->library('excel');

        $registrosExitosos = 0;
        $data = array('upload_data' => $this->upload->data());
        $inputFileName = $path . $data['upload_data']['file_name'];


        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            $datosExcel = $objPHPExcel->getActiveSheet()->toArray(null, true, true);

            # Elimina el archivo
            unlink($inputFileName); 

            # Se elimina la primera fila del encabezado
            unset($datosExcel[0]);

            # Se obtiene un regimen de ejemplo
            $regimen = $this->codegen_model->get(
                'con_regimenes',
                'regi_id AS id',
                '',
                1,NULL,true
            );

            $errores = [];

            $this->load->library('../controllers/contratistas');

            $usuario = $this->ion_auth->user()->row();

            foreach($datosExcel AS $linea => $datos)
            {
                $municipio = $this->codegen_model->get(
                    'par_municipios',
                    'muni_id AS id',
                    'codigo_dane = "'. $datos[6] .'"',
                    1,NULL,true
                );

                $contratista = [
                    'nit'               => $datos[0],
                    'tipocontratistaid' => $datos[1],
                    'nombre'            => $datos[2],
                    'direccion'         => $datos[3],
                    'telefono'          => $datos[4],
                    'email'             => $datos[5],
                    'municipioid'       => isset($municipio->id) ? $municipio->id : '',
                    'regimenid'         => $regimen->id,
                ];

                $verificacion = $this->codegen_model->get(
                    'con_contratistas',
                    'cont_id AS id',
                    'cont_nit = "'. $contratista['nit'] .'"',
                    1,NULL,true
                );

                $id_contratista = null;

                if(empty($verificacion) == false) {
                    $id_contratista = $verificacion->id;
                }
                else
                {
                    # Definir los datos para la validacion y registro
                    $_POST = $contratista;

                    $respuestaRegistro = $this->contratistas->registrarContratista();

                    if($respuestaRegistro['exito'])
                    {
                        $id_contratista = $respuestaRegistro['id'];
                    } else {
                        $errores[] = '<b>Fila '. ($linea+1) .'</b>' . $respuestaRegistro['error'];
                    }
                }

                if($id_contratista)
                {
                    $municipio = $this->codegen_model->get(
                        'par_municipios',
                        'muni_id AS id',
                        'muni_departamentoid = 6
                            AND codigo_dane = "'. $datos[11] .'"',
                        1,NULL,true
                    );

                    # Definir los datos para la validacion y registro
                    $_POST = [
                        'tipocontratoid'            => $datos[7],
                        'fecha'                     => $datos[8],
                        'numero'                    => $datos[9],
                        'valor'                     => $datos[10],
                        'cntr_municipio_origen'     => isset($municipio->id) ? $municipio->id : '',
                        'clasificacion_contrato'    => $datos[12],
                        'objeto'                    => $datos[13],
                        'contrato_relacionado'      => $datos[14],
                        'contratistaid'             => $id_contratista,
                        'contratanteid'             => $usuario->id_empresa,
                    ];

                    $respuestaRegistro = $this->registrarContrato();

                    if($respuestaRegistro['exito'] == true)
                    {
                        $registrosExitosos++;
                    } else {
                        $errores[] = '<b>Fila '. ($linea+1) .'</b>' . $respuestaRegistro['error'];
                    }
                }
            }

            $this->session->set_flashdata('successmessage', "Se han registrado correctamente $registrosExitosos contrato(s)");

            if(count($errores) > 0) {
                $this->session->set_flashdata('errormessage', implode('<br>', $errores));
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('errormessage', 'Ocurrio un problema al proecesar el archivo.');
        }

		redirect(base_url().'index.php/contratos/importarContratosExcel');
    }

    /**
     * Crea un excel para la guia de cargue de contratos
     * (se deja asi para que cargue las equivalencias dinamicamente)
     * 
     * @return null
     */
    public function plantillaExcel()
    {
        //redirect them to the login page
        if (!$this->ion_auth->logged_in()) {
			redirect('users/login', 'refresh');
		}
		elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/detalles'))) {
			redirect('error_404', 'refresh');
		}

        $this->data['tipos_contratistas'] = $this->codegen_model->getSelect(
            'con_tiposcontratistas AS tipo',
            'tpco_id AS id, tpco_nombre AS nombre'
        );

        $this->data['tipos_contratos'] = $this->codegen_model->getSelect(
            'con_tiposcontratos AS tipo',
            'tico_id AS id, tico_nombre AS nombre'
        );

        $this->data['clasificacion_contrato']  = Equivalencias::clasificacionContratos();

        $_SESSION['fecha_informe_excel'] = 'Plantilla cargue contratos';

        // $this->template->load($this->config->item('excel_template'),'contratos/plantilla_excel.php', $this->data);
        $this->load->view('contratos/plantilla_excel.php', $this->data);
    }
}
