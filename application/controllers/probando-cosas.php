<?//a qui empieza la prueba 19/04 para usuarios con dos empresas	
					   $username = $this->input->post('id');
					   $id_empresa = $this->input->post('id_empresa');
					   
					   $data1 = array(
							'id' => $this->input->post('id'),
							'empresa' => $this->input->post('id_empresa'),
							

					   );

					   $this->db->insert('doble_empresa',$data1);

					//   $sql = "INSERT INTO doble_empresa (id, id_empresa, id_contratante) VALUES ('Test', 'Testing', 'Testing@tesing.com')";

					 
						
					  /* $query = $this->db->insert('INSERT INTO doble_empresa (id, id_empresa, id_nombre')->result_array();
			
			foreach($query as $i=>$id) {

				
				$this->db->where('u.id', $id['u.id']);
				$images_query = $this->db->insert('doble_empresa')->result_array();
			 
				
				$query[$i]['doble_empresa'] = $images_query;
			 
			 }*/


			 $res2=$this->codegen_model->getSelect(
                'declaraciones AS d',
                'd.id, d.id_empresa., estampilla.estm_nombre AS estampilla,
                d.periodo, d.tipo_declaracion, d.fecha_creacion, d.estado, d.soporte',
                '',
                'INNER JOIN con_contratantes empresa', 'empresa.id = d.id_empresa',
                'INNER JOIN est_estampillas estampilla', 'estampilla.estm_id = d.id_estampilla',
                'ORDER BY d.id'
            );



			$user = $this->ion_auth->user()->row();
            $data1 = array(
                'id' => $this->input->post('id'),
                'first_name' => $this->input->post('nombres'),
                'last_name' => $this->input->post('apellidos'),
                'phone' => $this->input->post('telefono')
            );
            $this->ion_auth->update($user->id, $data1);
            
            $datos=explode(', ', $data1->id_empresa);

			if(count($datos)>1){
				$txt='';
				foreach ($datos as $x) {
					$res2=$this->codegen_model->getSelect(
						'con_contratantes',
						'nombre',
						'WHERE id = '.$x
					);
					if(isset($res2[0]->nombre)){
						$txt .= ', '.$res2[0]->nombre;		
					}
				}
				$usuarios_empresas1[] = [
					$data1->id,
					$data1->email,
					substr($txt, 2), 
					$data1->perf_nombre,
					$data1->tipo,
					$data1->active,
					$estado
				];
			}else{
				$id_emp=$data1->id_empresa;
				$res2=$this->codegen_model->getSelect(
					'con_contratantes',
					'nombre',
					'WHERE id = '.($id_emp?$id_emp:0)
				);
				$nombre = '';
				if(isset($res2[0]->nombre)){
					$nombre = $res2[0]->nombre;		
				}
				
				$usuarios_empresas1[] = [
					$data1->id,
					$data1->email,
					$nombre, 
					$data1->perf_nombre,
					$data1->tipo,
					$data1->active,
					$estado
				];



// a qui va los requerimientos por revisar de boyaca listado 

/*Buen d??a

 

Ingeniero Carlos, envi?? listado de temas pendientes y recomendaciones por realizar en la plataforma desde el uso de las entidades como desde los usuarios de la Gobernaci??n de Boyac??.

 

Desde el uso de la plataforma como entidades declarantes:

Editar o eliminar contratos antes de generar la primera estampilla de cada contrato, despu??s de generada estampilla no se podr?? editar ni mucho menos borrar. 
Al crear contratista que la plataforma te visualice los contratistas de la empresa primero, con el hecho que est??n seguros que si quedo creado sus contratistas y ellos puedan evidenciar cuantos tienen creados como entidad. 
Poder modificar y eliminar contratista antes de generar la primera estampilla que tenga el contrato al cual est?? ligado, despu??s de generar la primera estampilla no permitir modificaci??n o eliminaci??n del contratista. 
Al generar las liquidaciones que antes de liquidar finalmente, cuando aparece la sugerida por el sistema, tenga la opci??n de editar el valor cuota, ya que en algunos casos se evidencia que ingresan el valor con IVA y hasta los descuentos sugeridos por la plataforma evidencian el error. 
Que los cargues masivos se puedan anular masivamente de las dos plantillas, antes de efectuar las estampillas, por temas que las entidades cargan nuevamente toda la plantilla corregida. 
Para las entidades Descentralizadas cuando quieran realizar cargue masivos en temas de liquidaci??n van a tener un conflicto porque ellos manejan 3 o hasta 4 estampillas, y la plantilla solo est?? dise??ada para las empresas de servicios p??blicos, por favor prever este posible problema. 
Cuando se genera una declaraci??n hasta que no est?? firmada por el contador y representante legal por favor que los datos sean editables encaso que quieran incluir un valor o generar una estampilla que se omiti??. 
Que al crear las estampillas deje ajustar o colocar la fecha en que se realiz?? la estampilla, y no la fecha en la que se est?? ingresando los datos, ya que se ha observado que emite la fecha en la que se diligencia la informaci??n y no brinda la opci??n de asignar fecha. 
Que los usuarios contador o revisor fiscal y representante legal tengan la oportunidad primero de visualizar y revisar la declaraci??n con el tema de las estampillas emitidas desde sus plataformas y segundo que puedan solicitar correcciones y ajustes al usuario liquidador antes de firmar. 
Que aparezcan todos los datos en el formulario de la entidad, como Nit, Tel??fono, Correo, etc... 
Que finalmente al cerrar cada mes el sistema diferencie o agrupe todos los datos ingresados o generados en ese periodo, con el motivo que ellos tengan presente y control de que se est?? generado en ese nuevo periodo.  
Finalmente, que se tenga la numeraci??n de cuantos contratos est??n vigentes, y cuantos contratistas tiene la empresa ingresados en la plataforma, motivo para que cada empresa tenga f??cil acceso visual de primer impacto del uso de la plataforma, y por otra parte el total de estampillas generadas del periodo. 
 
Desde el uso de la plataforma como Gobernaci??n de Boyac??: 

Que se tenga la oportunidad de anular, modificar las estampillas que se generen por error. 
Que se tenga enumerado como sucede en la bandeja de entrada de un correo normal, las solicitudes o declaraciones allegadas, las que se revisaron o abrieron y las que quedan pendientes por abrir, el motivo es para tener f??cil acceso a la informaci??n y control de lo que se allegue d??a a d??a. 
Que la informaci??n visual de contratos, contratistas y declaraciones se puedan agrupar por entidades, ya que a la fecha se est??n generando hojas y hojas que su visualizaci??n por entidad se hace complejo. 
Que los tipos de contratos se pueda editar o modificar los % de estampillas. 
En la lista de estampillas pro deber??a est??n habilitada la opci??n de ver que estampillas est??n en uso, y ya sea el punto anterior poder modificar el %. 
Presentadas las declaraciones por parte de la entidad, y pasado el periodo de declaraci??n el sistema me agrupe por mes y despu??s por entidad las declaraciones presentadas, para f??cil consulta y acceso de b??squeda. 
Que en el sistema presentada la declaraci??n, como funcionario brinde la opci??n de anexar el comprobante de tesorer??a para dejar cerrado el proceso de la declaraci??n con motivo que hasta que se reconozca el valor en tesorer??a el proceso se cierra. 
Que el bot??n de notificaciones funcione.  
Que a la fecha exista una plataforma de prueba con la cual el funcionario de la Gobernaci??n pueda brindar capacitaciones, que se ve imposibilitado de crear contratistas contratos y emitir estampillas y finalmente declaraciones. 
Finalmente, que el funcionario de la Gobernaci??n tenga la posibilidad de cerrar un contrato que no se efectu?? en su totalidad porque no se ejecut?? lo destinado para la vigencia, anexando soporte aclaratorio por la entidad, o en algunos casos cuando el total de los contratos est?? incluido el IVA y as?? lo ingresan a la plataforma y finalmente si o si queda un saldo por ejecutar que corresponde a lo real al impuesto del IVA. 
 

Deantemano agradezco su atenci??n y pronta respuesta a las sugerencias expuestas.*/