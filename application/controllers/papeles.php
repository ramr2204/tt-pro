<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            papeles
*   Ruta:              /application/controllers/papeles.php
*   Descripcion:       controlador de papeles
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Papeles extends MY_Controller {
    
  function __construct() 
  {
      parent::__construct();
	    $this->load->library('form_validation');		
		  $this->load->helper(array('form','url','codegen_helper'));
		  $this->load->model('codegen_model','',TRUE);

	}	
	
	function index()
  {
		  $this->manage();
	}

	function manage()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar inventario');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'papeles/papeles_list', $this->data);

          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }
	
    function add()
    {  
        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/add')) {

                $this->data['successmessage']=$this->session->flashdata('message');  
        		$this->form_validation->set_rules('codigoinicial', 'Código Inicial', 'required|xss_clean|max_length[7]');
                $this->form_validation->set_rules('codigofinal', 'Código Final', 'required|xss_clean|max_length[7]');   
                $this->form_validation->set_rules('observaciones', 'Observaciones', 'xss_clean|max_length[480]');
                $this->form_validation->set_rules('documentoRespPapel', 'Documento Responsable',  'required|numeric');
                $this->form_validation->set_rules('cantidad', 'Cantidad Papeleria',  'required|numeric|is_natural_no_zero');
      
                /*
                * Valida si se recibe por get la variable contin
                */
                $contingencia = 'NO';
                $this->data['contingencia'] = 'NO';
                if(isset($_GET['contin']) && $_GET['contin'] == 'SI')
                {
                    $contingencia = 'SI';
                    $this->data['contingencia'] = 'SI';
                }

                if($this->form_validation->run() == false)
                {
                    $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
                }else 
                    {
                        /*
                        * Valida si se seleccionó la bandera $contingencia
                        * para registrar el rango de papeleria con ese atributo
                        */                        
                        if(isset($_POST['contingencia']) && $this->input->post('contingencia') == 'SI')
                        {
                            $contingencia = $this->input->post('contingencia');
                        }

                        //Valida si alguno de los codigos de papeleria
                        //ingresados se encuentra dentro de, por lo menos
                        //uno de los rangos asignados actualmente
  
                        $codigoUp=(int)$this->input->post('codigofinal');
                        $codigoDown=(int)$this->input->post('codigoinicial');
  
                        $cadenaErrorPapelEnRango='null';
                        
                        /*
                        * Construye el where para realizar la consulta
                        * discriminando si es de contingencia o no
                        */
                        $where = ' WHERE est_papeles.pape_estadoContintencia = "'. $contingencia .'"';

                        $campo='pape_codigoinicial, pape_codigofinal';
                        $rangos=$this->codegen_model->getSelect('est_papeles',$campo, $where);
  
                        foreach ($rangos as $value) 
                        {
                            $up=(int)$value->pape_codigofinal;
                            $down=(int)$value->pape_codigoinicial;
  
                            if($codigoDown<=$up && $codigoDown>=$down)
                            {
                                 $cadenaErrorPapelEnRango='El codigo de papel Inicial -'.$codigoDown
                                     .'- ya fue asignado. ';
                            }
  
                            if ($codigoUp<=$up && $codigoUp>=$down) 
                            {
                                $cadenaErrorPapelEnRango.='El codigo de papel Final -'.$codigoUp
                                  .'- ya fue asignado.';
                            }
                        }

                        if($cadenaErrorPapelEnRango!='null')
                        {
                            $this->data['errormessage'] = $cadenaErrorPapelEnRango; 
                          
                        }else
                            {
                               $data = array(
                                      'pape_usuario' => $this->input->post('documentoRespPapel'),
                                      'pape_codigoinicial' => $this->input->post('codigoinicial'),
                                      'pape_codigofinal' => $this->input->post('codigofinal'),
                                      'pape_observaciones' => $this->input->post('observaciones'),      
                                      'pape_cantidad' => $this->input->post('cantidad'),
                                      'pape_estadoContintencia' => $contingencia,
                                      'pape_fecha' => date('Y-m-d H:i:s'),
                                      'pape_estado'=> 1,
                                      'pape_imprimidos'=> 0

                                   );
                                
                                $respuestaProceso = $this->codegen_model->add('est_papeles',$data);
                                if ($respuestaProceso->bandRegistroExitoso) {

                                    $this->session->set_flashdata('message', 'Se ha asignado la Papeleria correspondiente al rango '
                                        .$this->input->post('codigoinicial')
                                        .'-'.$this->input->post('codigofinal')
                                        .' al usuario '
                                        .$this->input->post('responsablePapel')
                                        .' con éxito ');

                                    redirect(base_url().'index.php/papeles/add');
                                } else {

                                    $this->data['errormessage'] = 'No se pudo registrar la Papeleria';

                                } 
                            }
    		         }              
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/jquery-ui.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/jquery-ui.js'
                    );  
              $this->template->set('title', 'Agregar Papeleria');

              /*
              * Construye el where para extraer el numero de rotulo maximo asignado
              * discriminando si es de contingencia o no
              */
              $where = 'pape_estadoContintencia = "'. $contingencia .'"';

              $this->data['maxcodigofinal']  = $this->codegen_model->max('est_papeles','pape_codigofinal', $where);
              $this->template->load($this->config->item('admin_template'),'papeles/papeles_add', $this->data);
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }
  }	


	function edit()
  {    
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/edit')) {  

              $idregimen = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idregimen==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un estampilla para editar');
                  redirect(base_url().'index.php/papeles');
              }
              $resultado = $this->codegen_model->get('est_papeles','pape_cuenta','pape_id = '.$idregimen,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['pape_cuenta']==$this->input->post('cuenta')) {
                  
                  $this->form_validation->set_rules('cuenta', 'Cuenta', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('cuenta', 'Cuenta', 'required|trim|xss_clean|max_length[100]|is_unique[est_papeles.pape_cuenta]');
              
              }
              $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[256]');
              $this->form_validation->set_rules('bancoid', 'Banco',  'required|numeric|greater_than[0]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                        'pape_nombre' => $this->input->post('nombre'),
                        'pape_cuenta' => $this->input->post('cuenta'),
                        'pape_descripcion' => $this->input->post('descripcion'),
                        'pape_bancoid' => $this->input->post('bancoid')

                     );
                           
                	if ($this->codegen_model->edit('est_papeles',$data,'pape_id',$idregimen) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El estampilla se ha editado con éxito');
                      redirect(base_url().'index.php/papeles/edit/'.$idregimen);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }   
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                        );
                  $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                        );    
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('est_papeles','pape_id,pape_nombre,pape_cuenta,pape_descripcion,pape_bancoid','pape_id = '.$idregimen,1,NULL,true);
                  $this->data['bancos']  = $this->codegen_model->getSelect('par_bancos','banc_id,banc_nombre');
                  $this->template->set('title', 'Editar estampilla');
                  $this->template->load($this->config->item('admin_template'),'papeles/papeles_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un estampilla para eliminar');
                  redirect(base_url().'index.php/papeles');
              }
              if (!$this->codegen_model->depend('con_tiposcontratos','pape_contratoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('est_papeles','pape_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El estampilla se ha eliminado con éxito');
                  redirect(base_url().'index.php/papeles');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El estampilla se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/papeles/edit/'.$this->input->post('id'));

              }
                         
          } else {
              redirect(base_url().'index.php/error_404');       
          } 
      } else {
          redirect(base_url().'index.php/users/login');
      }
  }
  
    /*
    * Funcion de apoyo que extrae de la tabla de impresiones
    * la cantidad impresa para el id de la papeleria
    */
    function contarpapeles()
    {
        $where = 'WHERE impr_codigopapel != 0 AND impr_papelid = '.$this->input->post('papelid');
        $resultado = $this->codegen_model->getSelect('est_impresiones',"COUNT(*) AS contador", $where);
        echo $resultado[0]->contador;
    }
 
  function datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('p.pape_id,concat(u.first_name," ",u.last_name)'
                                        .' as nombre ,p.pape_codigoinicial,p.pape_codigofinal,'
                                        .'p.pape_cantidad,p.pape_imprimidos,p.pape_estado,'
                                        .'p.pape_fecha,p.pape_observaciones', false);
              $this->datatables->from('est_papeles p');
              $this->datatables->join('users u','u.id = p.pape_usuario','left');
              
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }


  //Funcion renderiza que el contenido de la columna de rangos
  //de papeleria asignada a cada usuario
  function extraerRangosPapel()
    {

    if ($this->ion_auth->logged_in()) { 
     $idLiquidador = $this->input->post('idLiquidador');
     $tabla='est_papeles';
     $campos="pape_codigoinicial, pape_codigofinal";
     $estructuraWhere='where pape_usuario = '.$idLiquidador;
     $estructuraGroup='group by pape_codigoinicial';

     $resultado = $this->codegen_model->getSelect($tabla,$campos,$estructuraWhere,'',$estructuraGroup);

     $cadenaPapeles='';

     foreach ($resultado as $value) 
     {
       $cadenaPapeles.=$value->pape_codigoinicial.' - '.$value->pape_codigofinal.' | ';
     }

     echo $cadenaPapeles;

     } else{
              redirect(base_url().'index.php/users/login');
           }  

    }



  //Función que renderiza la interfaz de re-asignación
  //de papeleria fisica de estampillas

  function getReassign() 
  {
       if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/getReassign') ) { 
              
              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              
              $this->template->set('title', 'Reasignar papeleria');
              $this->data['style_sheets']= array(
                        'css/jquery-ui.css' => 'screen'
                    );
              $this->data['javascripts']= array(                        
                        'js/jquery-ui.js'
                    );

            /*
            * Variable que determina si se debe trabajar con papelería de contingencia
            */
            $objContin = $this->codegen_model->get('adm_parametros','para_contingencia','para_id = 1',1,NULL,true);
            
            $this->data['contingencia'] = $objContin->para_contingencia;
            $this->template->load($this->config->item('admin_template'),'papeles/papeles_reassign', $this->data);                            

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }   
  }


  //Función que modifica el inventario de papeleria fisica de estampillas
  //según re-asignación

  function postReassign() 
  {
       if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/getReassign') ) {
              
              $this->data['successmessage']=$this->session->flashdata('message');  
              $this->form_validation->set_rules('codigoinicial', 'Código Inicial', 'required|xss_clean|max_length[7]');
              $this->form_validation->set_rules('codigofinal', 'Código Final', 'required|xss_clean|max_length[7]');   
              $this->form_validation->set_rules('observaciones', 'Observaciones', 'xss_clean|max_length[480]');
              $this->form_validation->set_rules('docuOldResponsable', 'Documento Responsable Actual',  'required|numeric');
              $this->form_validation->set_rules('docuNewResponsable', 'Documento Nuevo Responsable',  'required|numeric');
              $this->form_validation->set_rules('cantidad', 'Cantidad Papeleria',  'required|numeric|is_natural_no_zero'); 


              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else 
                {

                      //verifica que el liquidador que entrega tenga papeleria asignada o 
                      //disponible para reasignar
                      $verificacionDisponibilidad = $this->validarDisponibilidadCodigos($this->input->post('docuOldResponsable')); 
                      if(!$verificacionDisponibilidad)
                      {
                           $this->session->set_flashdata('errormessage','El Responsable Actual no tiene papeleria para re-asignación!');
                           redirect(base_url().'index.php/papeles/getReassign'); 
                      } 


                      //valida si los responsables son la misma persona
                      if($this->input->post('docuOldResponsable') == $this->input->post('docuNewResponsable'))
                      {
                           $this->session->set_flashdata('errormessage','Los liquidadores suministrados deben ser distintos!');
                           redirect(base_url().'index.php/papeles/getReassign');
                      }

                        /*
                        * Variable que determina si se debe trabajar con papelería de contingencia
                        */
                        $objContin = $this->codegen_model->get('adm_parametros','para_contingencia','para_id = 1',1,NULL,true);
                        if($objContin->para_contingencia == 1)
                        {
                            $contingencia = 'SI';
                        }else
                            {
                                $contingencia = 'NO';
                            }
                      
                           //actualiza los datos en el registro de rango del responsable que entrega 
                           $this->actualizacionRangosReasignados($this->input->post('docuOldResponsable'), 
                               $this->input->post('idRango'), 
                               $this->input->post('cantidad'), 
                               $this->input->post('codigoinicial'), 
                               $this->input->post('codigofinal'),
                               $this->input->post('newResponsablePapel'),
                               $contingencia
                            );
                            
                            $obsRotulosReasignados = 'Rotulos Re-Asignados del Usuario '
                                .$this->input->post('oldResponsablePapel')
                                .' comprendidos en el Rango ('.$this->input->post('codigoinicial')
                                .'-'.$this->input->post('codigofinal').')'
                                .'. '.$this->input->post('observaciones');

                            $data = array(
                                      'pape_usuario' => $this->input->post('docuNewResponsable'),
                                      'pape_codigoinicial' => $this->input->post('codigoinicial'),
                                      'pape_codigofinal' => $this->input->post('codigofinal'),
                                      'pape_observaciones' => $obsRotulosReasignados,      
                                      'pape_cantidad' => $this->input->post('cantidad'),
                                      'pape_fecha' => date('Y-m-d H:i:s'),
                                      'pape_estado'=> 1,
                                      'pape_imprimidos'=> 0,
                                      'pape_estadoContintencia' => $contingencia
                                   );

                                   $respuestaProceso = $this->codegen_model->add('est_papeles',$data);
                            if ($respuestaProceso->bandRegistroExitoso) 
                            {

                                    $this->session->set_flashdata('successmessage', 'Se ha re-asignado la Papeleria correspondiente al rango '
                                        .$this->input->post('codigoinicial')
                                        .'-'.$this->input->post('codigofinal')
                                        .' al usuario '
                                        .$this->input->post('newResponsablePapel')
                                        .' con éxito ');

                                    redirect(base_url().'index.php/papeles/getReassign');
                            }  else 
                                  {
                                      $this->session->set_flashdata('errormessage','No se pudo re-asignar la Papeleria!');
                                      redirect(base_url().'index.php/papeles/getReassign');                                  
                                  }                       
                      
                }


              $this->template->set('title', 'Reasignar papeleria');
              $this->data['style_sheets']= array(
                        'css/jquery-ui.css' => 'screen'
                    );
              $this->data['javascripts']= array(                        
                        'js/jquery-ui.js'
                    );  
              $this->template->load($this->config->item('admin_template'),'papeles/papeles_reassign', $this->data);                            


          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }    
  } 



  //Función de apoyo que determina si se re-asigna el rango de papeleria
  //original o si solo se re-asigna una sección del rango

  function actualizacionRangosReasignados($docuOldResponsable='', $idRango='', $cantidad='', $codigoinicial='', $codigofinal='', $newResponsablePapel='', $estadoContingencia = '')
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/getReassign') ) { 

            //extrae los datos del rango
            //de papeleria de donde se sacarán los codigos            
            $rangoOriginal = $this->codegen_model->getSelect('est_papeles','pape_cantidad'
            .',pape_codigoinicial,pape_codigofinal,pape_id,pape_observaciones',
            'where pape_usuario = '.$docuOldResponsable
            .' AND pape_id = '.$idRango);
                                 
            /*
            * Se calcula la cantidad Neta disponible según la cantidad
            * de impresiones realizadas con el id del rango de papel original
            */
            $rotulosImpresos = $this->codegen_model->countwhere('est_impresiones','impr_papelid = '.$rangoOriginal[0]->pape_id);
            $cantidadNetaDisponible = (int)$rangoOriginal[0]->pape_cantidad - (int)$rotulosImpresos->contador;

            /*
            * Se calcula el numero de rotulo inicial del rango disponible
            * sumando al codigo inicial del rango original la cantidad de rotulos impresos.
            * Nota: el rotulo final disponible siempre será el rotulo final del rango original
            */
            $rotuloInicialDisponible = (int)$rotulosImpresos->contador + (int)$rangoOriginal[0]->pape_codigoinicial;
            
            /*
            * Se calcula la cantidad Neta a reasignar segun el rango
            * suministrado (se incrementa el valor en 1 para precision)
            */
            $cantidadNetaReasignar = ((int)$codigofinal - (int)$codigoinicial)+1;
            
            /*
            * Valida si los codigos inicial y final suministrados para reasignar
            * se encuentran en el rango de codigos disponibles
            * y valida que el codigo inicial suministrado sea igual al codigo
            * inicial disponible para re-asignar
            */
            if(($codigofinal <= $rangoOriginal[0]->pape_codigofinal) && ($codigofinal >= $rotuloInicialDisponible))
            {
                if(($codigoinicial <= $rangoOriginal[0]->pape_codigofinal) && ($codigoinicial >= $rotuloInicialDisponible))
                {
                    /*
                    * Valida que el rotulo final suministrado sea mayor o igual
                    * al rotulo inicial suministrado
                    */
                    if($codigofinal >= $codigoinicial)
                    {
                        /*
                        * Valida que el codigo inicial suministrado sea igual
                        * al codigo inicial disponible
                        */
                        if($codigoinicial == $rotuloInicialDisponible)
                        {
                            /*
                            * Se calcula la cantidad de posibles rotulos restantes
                            * para saber si es mayor a cero, crear un Rango restante
                            * y asignarlo al liquidador que entrega
                            */            
                            $rotulosRestantes = $cantidadNetaDisponible - $cantidadNetaReasignar;
                        }else
                            {
                                $this->session->set_flashdata('errormessage','El Codigo Inicial Suministrado No Puede Ser Distinto al Codigo Inicial del Rango Disponible! ('.$rotuloInicialDisponible.'-'.$rangoOriginal[0]->pape_codigofinal.')');
                                redirect(base_url().'index.php/papeles/getReassign');
                            }
                    }else
                        {
                            $this->session->set_flashdata('errormessage','El Codigo Final Suministrado No Puede Ser Menor que el Codigo Inicial Suminstrado!');
                            redirect(base_url().'index.php/papeles/getReassign');
                        }
                }else
                    {
                        $this->session->set_flashdata('errormessage','El Codigo Inicial Suministrado No se encuentra dentro del Rango Disponible a Re-Asingar! ('.$rotuloInicialDisponible.'-'.$rangoOriginal[0]->pape_codigofinal.')');
                        redirect(base_url().'index.php/papeles/getReassign');
                    }
            }else
                {
                    $this->session->set_flashdata('errormessage','El Codigo Final Suministrado No se encuentra dentro del Rango Disponible a Re-Asingar! ('.$rotuloInicialDisponible.'-'.$rangoOriginal[0]->pape_codigofinal.')');
                    redirect(base_url().'index.php/papeles/getReassign');                    
                }

            /*
            * Valida que la cantidad suministrada de rotulos a re-asignar
            * sea valida
            */             
            if($cantidad != $cantidadNetaReasignar)
            {
                $this->session->set_flashdata('errormessage','La Cantidad Suminstrada para Re-Asignar No corresponde a la Cantidad de Rotulos en el Rango Suminstrado para Re-Asingar! ('.$codigoinicial.'-'.$codigofinal.')');
                redirect(base_url().'index.php/papeles/getReassign'); 
            }                        

            /*
            * Valida si quedan rotulos restantes o no
            */
            if($rotulosRestantes == 0)
            {
                /*
                * valida si los codigos a re-asignar son iguales a los
                * codigos del rango, entonces se borrará
                * el registro para el liquidador que entrega
                */
                if($rangoOriginal[0]->pape_codigoinicial == $codigoinicial && $rangoOriginal[0]->pape_codigofinal == $codigofinal)
                {                                    
                     $this->codegen_model->delete('est_papeles', 'pape_id', $idRango);
                }else
                    {
                        /*
                        * Se Modifica la Informacion del rango original para 
                        * ajustar el codigo final, la cantidad y se especifica
                        * en las observaciones el cambio de valores por re-asignacion
                        */
                        $obsOriginal = $rangoOriginal[0]->pape_observaciones.'. Se Modificó el Codigo Final y la Cantidad de Rotulos '
                            .'del Rango Previo ('.$rangoOriginal[0]->pape_codigoinicial.'-'.$rangoOriginal[0]->pape_codigofinal
                            .') por Reasignación de Rotulos al Usuario '.$newResponsablePapel.' comprendidos en el Rango ('
                            .$codigoinicial.'-'.$codigofinal.')';
                        
                        $this->codegen_model->edit('est_papeles',
                            ['pape_cantidad'=>$rotulosImpresos->contador,
                            'pape_codigofinal'=>(int)$codigoinicial-1,
                            'pape_observaciones'=>$obsOriginal],
                            'pape_id', $idRango);
                    }                                       
            }elseif($rotulosRestantes > 0)
                {
                    /*
                    * Si la cantidad de rotulos restantes es mayor que cero se crea
                    * un registro con el fragmento de rotulos restantes para el liquidador
                    * que entrega los rotulos
                    */                   
                    $codigoInicialFragmento = (int)$codigofinal+1;                    

                    $observacionesFragmento = 'Fragmento restante de la re-asignación del rango '
                        .$codigoinicial.'-'.$codigofinal.' al liquidador '.$newResponsablePapel;

                    $data = array(
                              'pape_usuario' => $docuOldResponsable,
                              'pape_codigoinicial' => $codigoInicialFragmento,
                              'pape_codigofinal' => $rangoOriginal[0]->pape_codigofinal,
                              'pape_observaciones' => $observacionesFragmento,      
                              'pape_cantidad' => $rotulosRestantes,
                              'pape_fecha' => date('Y-m-d H:i:s'),
                              'pape_estado'=> 1,
                              'pape_imprimidos'=> 0,
                              'pape_estadoContintencia' => $estadoContingencia
                                  );
                    $respuestaProceso = $this->codegen_model->add('est_papeles',$data);

                    /*
                    * Se Modifica la Informacion del rango original para 
                    * ajustar el codigo final, la cantidad y se especifica
                    * en las observaciones el cambio de valores por re-asignacion
                    */
                    $obsOriginal = $rangoOriginal[0]->pape_observaciones.'. Se Modificó el Codigo Final y la Cantidad de Rotulos '
                        .'del Rango Previo ('.$rangoOriginal[0]->pape_codigoinicial.'-'.$rangoOriginal[0]->pape_codigofinal
                        .') por Reasignación de Rotulos al Usuario '.$newResponsablePapel.' comprendidos en el Rango ('
                        .$codigoinicial.'-'.$codigofinal.')';
                    
                    $this->codegen_model->edit('est_papeles',
                        ['pape_cantidad'=>$rotulosImpresos->contador,
                        'pape_codigofinal'=>(int)$codigoinicial-1,
                        'pape_observaciones'=>$obsOriginal],
                        'pape_id', $idRango);
                }
          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }  
  }

  //Función que extrae el rango disponible del liquidador
  //al que se le retirará papeleria y se reasignará a 
  //otro

  function extraerPapeleriaAsignada()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/getReassign') ) {

              $idLiquidador = $this->input->post('idLiquidador');

              $codigosReasignar = $this->validarDisponibilidadCodigos($idLiquidador);

              echo json_encode($codigosReasignar);
          
          } else {
                     redirect(base_url().'index.php/error_404');
                 }
               
      } else{
                redirect(base_url().'index.php/users/login');
            }   

  }

  

  function validarDisponibilidadCodigos($idLiquidador='')
  {
        if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('papeles/getReassign') ) { 

            /*
            * Variable que determina si se debe trabajar con papelería de contingencia
            */
            $objContin = $this->codegen_model->get('adm_parametros','para_contingencia','para_id = 1',1,NULL,true);
            if($objContin->para_contingencia == 1)
            {
                $contingencia = 'SI';
            }else
                {
                    $contingencia = 'NO';
                }
            
            /*
            * Extrae los posibles rangos de papeleria asignados al usuario
            * que se encuentra logueado que debe ser un liquidador
            */
            $tabla='est_papeles';
            $campos="pape_codigoinicial, pape_codigofinal, pape_id";
            $estructuraWhere='WHERE pape_usuario = '. $idLiquidador .' AND pape_estadoContintencia = "'. $contingencia .'"';
            $estructuraGroup='group by pape_codigoinicial';

            $papeles = $this->codegen_model->getSelect($tabla,$campos,$estructuraWhere,'',$estructuraGroup);

              //verifica que tenga asignada papeleria para reasignar
              if($papeles)
              {
                   if(count($papeles)>1)
                   {
                        foreach ($papeles as $value) 
                        {
                            $codigoinicial = (int)$value->pape_codigoinicial;
                            $codigofinal = (int)$value->pape_codigofinal;

                            //extrae el maximo codigo impreso registrado del rango  
                            //para establecer el codigo menor de ese rango                           
                            $tablaJoin='est_papeles';
                            $equivalentesJoin='est_impresiones.impr_papelid = est_papeles.pape_id';
                            $where='est_papeles.pape_usuario ='.$idLiquidador.' AND est_papeles.pape_id = '.$value->pape_id;

                            $maxImpreso = $this->codegen_model->max('est_impresiones','impr_codigopapel',$where, $tablaJoin, $equivalentesJoin);
                            
                            //verifica si ya habia asignado por lo menos
                            //un consecutivo a una impresion
                            //de lo contrario elige el primer y ultimo
                            //codigo de la papeleria
                            $limites = $this->validarCodigoMaximo($maxImpreso, $codigoinicial, $codigofinal);

                            if($limites[1] > 0)
                            {
                                $codigosReasignar['limiteInferior'][] = $limites[0];
                                $codigosReasignar['limiteSuperior'][] = $limites[1];
                                $codigosReasignar['idRango'][] = $value->pape_id;
                                $codigosReasignar['varios'] = true;
                            }
                            
                        }

                        //si ninguno de los rangos que tiene asignados
                        //el liquidador esta disponible envia objeto
                        //vacio para la notificación del error
                        if(!isset($codigosReasignar))
                        {
                             $codigosReasignar = [] ;
                        }else
                            {
                                 //si solo uno de los rangos que tiene asignados
                                 //el liquidador esta disponible envia objeto
                                 //especifico para que no se renderice la modal
                                 if(count($codigosReasignar['limiteInferior'])==1)
                                 {
                                      $codReasignar = ['limiteInferior' => $codigosReasignar['limiteInferior'][0], 'limiteSuperior' => $codigosReasignar['limiteSuperior'][0], 'idRango' => $codigosReasignar['idRango'][0]]; 
                                      unset($codigosReasignar);
                                      $codigosReasignar = $codReasignar;
                                 } 
                            }                                                                        

                   }else
                       {           
                           
                           $codigoinicial = (int)$papeles[0]->pape_codigoinicial;
                           $codigofinal = (int)$papeles[0]->pape_codigofinal;
                
                           //extrae el ultimo codigo de papeleria resgistrado
                           //en las impresiones para el liquidador 
                           $tablaJoin='est_papeles';
                           $equivalentesJoin='est_impresiones.impr_papelid = est_papeles.pape_id';
                           $where='est_papeles.pape_usuario ='.$idLiquidador;

                           $maxImpreso = $this->codegen_model->max('est_impresiones','impr_codigopapel',$where, $tablaJoin, $equivalentesJoin);

                           //verifica si ya habia asignado por lo menos
                           //un consecutivo a una impresion
                           //de lo contrario elige el primer y ultimo
                           //codigo de la papeleria
                           $limites = $this->validarCodigoMaximo($maxImpreso, $codigoinicial, $codigofinal);

                            if($limites[1] > 0)
                            {
                                $codigosReasignar = ['limiteInferior' => $limites[0], 'limiteSuperior' => $limites[1], 'idRango' => $papeles[0]->pape_id]; 
                            }else
                              {
                                  $codigosReasignar = [];
                              }
                        }
                    
              }else
                 {
                       $codigosReasignar = [];
                 }

              return $codigosReasignar;   

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }   
    
  }



  //Función de apoyo que determina los limites del rango
  function validarCodigoMaximo($maxQuery, $codigoinicial, $codigofinal)
  {
       
       if((int)$maxQuery['impr_codigopapel']>0)
        {
            $limiteInferior = $maxQuery['impr_codigopapel']+1;

           //valida que el limite inferior encontrado no sea igual al ultimo
           //codigo asignado al liquidador

           if($limiteInferior <= $codigofinal)
           {
                $limiteSuperior = $codigofinal;
           }else
                {
                     //se establece el limite superior como cero
                     //para identificar que no tiene papeleria
                     //disponible para reasignar
                     $limiteSuperior = 0;
                }   
        }else
            {
                $limiteInferior = $codigoinicial;
                $limiteSuperior = $codigofinal; 
            } 
        
        return [$limiteInferior, $limiteSuperior];

  }



}
