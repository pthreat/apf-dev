<?php

	namespace apf\model{

		class Usuario{

			private	$_id							=	NULL;
			private	$_nombre						=	NULL;
			private	$_apellido					=	NULL;
			private	$_email						=	NULL;
			private	$_fechaNacimiento			=	NULL;
			private	$_cvFile						=	NULL;
			private	$_preferencias				=	NULL;
			private	$_table						=	"usuarios";
			private	$_ultimaActualizacion	=	NULL;


			/**
			*Funcion privada, generica para obtener datos de usuario y generar una instancia
			*se puso esta funcion como privada para poder utilizar las funciones derivadas
			*que correspondan a cada caso, por ejemplo si quiero obtener una instancia
			*del objeto Usuario mediante Email, entonces utilizaria la funcion publica y estatica
			*getInstanceByEmail. Y asi sucesivamente.
			*/

			private static function getInstance($field,$value){

				switch(strtolower($field)){

					case	"id":
					case	"id_usuario":

						$where	=	Array(
											Array(
												"field"=>"usuarios_datos.id_usuario",
												"value"=>(int)$value
											)
						);


					break;

					case "email":

						$where	=	Array(
											Array(
												"field"=>"usuarios_datos.email",
												"value"=>$value
											)
						);

					break;

					default:

						throw(new \Exception("No puedo obtener una instancia mediante el campo $field con valor $value"));

					break;

				}

				$usuarios					=	new \apf\db\mysql5\Table("usuarios");
				$usuariosDatos				=	new \apf\db\mysql5\Table("usuarios_datos");
				$usuariosCategorias		=	new \apf\db\mysql5\Table("usuarios_categorias");
				$usuariosCriterios		=	new \apf\db\mysql5\Table("usuarios_criterios");
				$usuariosUbicaciones		=	new \apf\db\mysql5\Table("usuarios_ubicaciones");
				$usuariosCv					=	new \apf\db\mysql5\Table("usuarios_cv");

				$select						=	new \apf\db\mysql5\Select($usuarios);

				$fields						=	Array(
															"usuarios.id AS idUsuario",
															"usuarios.nombre",
															"usuarios.apellido",
															"usuarios_datos.salario",
															"usuarios_datos.clave",
															"usuarios_datos.fecha_nacimiento",
															"usuarios_datos.firma",
															"usuarios_datos.disponibilidad",
															"GROUP_CONCAT(DISTINCT usuarios_categorias.id_categoria) AS categorias",
															"usuarios_datos.email",
															"usuarios_cv.cv_file",
															"usuarios_cv.texto",
															"GROUP_CONCAT(DISTINCT usuarios_ubicaciones.id_provincia) AS provincias",
															"GROUP_CONCAT(DISTINCT usuarios_ubicaciones.id_localidad) AS localidades",
															"GROUP_CONCAT(DISTINCT usuarios_ubicaciones.id_barrio) AS barrios",
															"GROUP_CONCAT(DISTINCT usuarios_criterios.criterio) AS criterios"
				);

				$select->fields($fields);

				//INNER JOIN usuarios_datos
				/////////////////////////////////////////////////////////

				$joinUsuariosDatos		=	new \apf\db\mysql5\Join($usuariosDatos);
				$joinUsuariosDatos->type("INNER");

				$on						=	Array(
														Array(
															"field"=>"usuarios.id",
															"value"=>"usuarios_datos.id_usuario",
															"quote"=>FALSE
														)
				);

				$joinUsuariosDatos->on($on);

				$select->join($joinUsuariosDatos);

				//LEFT JOIN usuarios_categorias
				/////////////////////////////////////////////////////////

				$joinUsuariosCategorias	=	new \apf\db\mysql5\Join($usuariosCategorias);
				$joinUsuariosCategorias->type("LEFT");

				$on						=	Array(
														Array(
															"field"=>"usuarios_categorias.id_usuario",
															"value"=>"usuarios_datos.id_usuario",
															"quote"=>FALSE
														)
				);

				$joinUsuariosCategorias->on($on);

				$select->join($joinUsuariosCategorias);


				//LEFT JOIN usuarios_criterios
				/////////////////////////////////////////////////////////

				$joinUsuariosCriterios	=	new \apf\db\mysql5\Join($usuariosCriterios);
				$joinUsuariosCriterios->type("LEFT");

				$on						=	Array(
														Array(
															"field"=>"usuarios_datos.id_usuario",
															"value"=>"usuarios_criterios.id_usuario",
															"quote"=>FALSE
														)
				);

				$joinUsuariosCriterios->on($on);

				$select->join($joinUsuariosCriterios);

				//LEFT JOIN usuarios_cv
				/////////////////////////////////////////////////////////

				$joinUsuariosCv	=	new \apf\db\mysql5\Join($usuariosCv);
				$joinUsuariosCv->type("LEFT");

				$on						=	Array(
														Array(
															"field"=>"usuarios_datos.id_usuario",
															"value"=>"usuarios_cv.id_usuario",
															"quote"=>FALSE
														)
				);

				$joinUsuariosCv->on($on);

				$select->join($joinUsuariosCv);


				//LEFT JOIN usuarios_ubicaciones
				/////////////////////////////////////////////////////////

				$joinUsuariosUbicaciones	=	new \apf\db\mysql5\Join($usuariosUbicaciones);
				$joinUsuariosUbicaciones->type("LEFT");

				$on						=	Array(
														Array(
															"field"=>"usuarios_datos.id_usuario",
															"value"=>"usuarios_ubicaciones.id_usuario",
															"quote"=>FALSE
														)
				);

				$joinUsuariosUbicaciones->on($on);

				$select->join($joinUsuariosUbicaciones);


				//WHERE
				/////////////////////////////////////////////////////////


				$select->where($where);
				$select->group(Array("usuarios.id"));

				$res	=	$select->execute();

				if(!sizeof($res)){

					return FALSE;

				}

				$class	=	__CLASS__;
				$obj		=	new $class();
				$obj->setId($res["idUsuario"]);
				$obj->setNombre($res["nombre"]);
				$obj->setApellido($res["apellido"]);
				$obj->setFechaNacimiento($res["fecha_nacimiento"]);
				$obj->agregarPreferencia("salario",$res["salario"],FALSE);
				$obj->agregarPreferencia("clave",$res["clave"],FALSE);
				$obj->agregarPreferencia("firma",$res["firma"],FALSE);
				$obj->agregarPreferencia("disponibilidad",$res["disponibilidad"],FALSE);
				//echo "<pre>";
				//var_dump($res);
				//echo "</pre>";
				if(sizeof($res["categorias"])){

					$categorias	=	explode(',',$res["categorias"]);

					foreach($categorias as $categoria){

						$obj->agregarPreferencia("categorias",$categoria);

					}

				}

				if(sizeof($res["localidades"])){

					$localidades	=	explode(',',$res["localidades"]);

					foreach($localidades as $localidad){

						$obj->agregarPreferencia("localidades",$localidad);

					}

				}
				if(sizeof($res["provincias"])){

					$provincias	=	explode(',',$res["provincias"]);

					foreach($provincias as $provincia){

						$obj->agregarPreferencia("provincias",$provincia);

					}

				}

				if(sizeof($res["barrios"])){

					$barrios	=	explode(',',$res["barrios"]);

					foreach($barrios as $barrio){

						$obj->agregarPreferencia("barrios",$barrio);

					}

				}


				if(sizeof($res["criterios"])){

					$criterios	=	explode(',',$res["criterios"]);
					foreach($criterios as $criterio){

						$obj->agregarPreferencia("criterios",$criterio);

					}

				}

				$obj->setEmail($res["email"]);
				$obj->setCvFile($res["cv_file"]);

				return $obj;

			}

			public function getCategoriasUsuario(){
				return isset($this->_preferencias["categorias"])?$this->_preferencias["categorias"]:FALSE;		
			
			}

			public function getUbicacionesUsuario(){

				$provincias		=	isset($this->_preferencias["provincias"])?$this->_preferencias["provincias"]:FALSE;		
				$localidades	=	isset($this->_preferencias["localidades"])?$this->_preferencias["localidades"]:FALSE;		
				$barrios			=	isset($this->_preferencias["barrios"])?$this->_preferencias["barrios"]:FALSE;		
				if( !$localidades && !$provincias && !$barrios ){
					return FALSE;
				}
				
				$return['localidades']			=	Array();
				$return['provincias']			=	Array();
				$return['barrios']				=	Array();

				if($localidades){
					foreach($localidades as $id_localidad){
						if($id_localidad!=0)
							$return['localidades'][]	=	\apf\model\Localidad::getInstanceById($id_localidad);
					}
				}
				
				if($provincias){
					foreach($provincias as $id_provincia){
						if($id_provincia!=0)
							$return['provincias'][]	=	\apf\model\Provincia::getInstanceById($id_provincia);
					}
				}
				if($barrios){
					foreach($barrios as $id_barrio){
						if($id_barrio!=0)
							$return['barrios'][]	=	\apf\model\Barrio::getInstanceById($id_barrio);

					}
				}

				return $return;

			}

			public function eliminarTodasCategoriasUsuario(){

				$table   =  new \apf\db\mysql5\Table("usuarios_categorias");
            $delete  =  new \apf\db\mysql5\Delete($table);
				
				$where = Array(
										Array (
											"field"=>"id_usuario",
											"value"=>$this->getId()
										)
								  );

				$delete->where($where);
				$ret = $delete->execute();
				return $ret;
	

			}
	
			public function eliminarTodasUbicacionesUsuario(){

            $tableUsuariosUbicaciones  	=  new \apf\db\mysql5\Table("usuarios_ubicaciones");

            $delete 								=  new \apf\db\mysql5\Delete($tableUsuariosUbicaciones);
				

            $where = Array(
										Array(
											"field"=>"usuarios_ubicaciones.id_usuario",
											"value"=>$this->getId()
										)
                          );


            $delete->where($where);

            $ret = $delete->execute();
            return $ret;

         }

			public function eliminarCriterioUsuario($id_usuario=NULL,$criterio=NULL){

				$table   =  new \apf\db\mysql5\Table("usuarios_criterios");
            $delete  =  new \apf\db\mysql5\Delete($table);
			
				$where   =  Array(
                              Array(
                                 "field"=>"id_usuario",
                                 "value"=>$id_usuario
                              ),
                              Array(
                                 "operator"=>"AND"
                              ),
                              Array(
                                 "field"=>"criterio",
                                 "value"=>$criterio
                              )
            );

				$delete->where($where);
				$res 	= 	$delete->execute();
				return $res;

			}

			public function eliminarLocalidadUsuario($id_usuario=NULL,$localidad=NULL){

				$table   =  new \apf\db\mysql5\Table("usuarios_ubicaciones");
            $delete  =  new \apf\db\mysql5\Delete($table);
			
				$where   =  Array(
                              Array(
                                 "field"=>"id_usuario",
                                 "value"=>$id_usuario
                              ),
                              Array(
                                 "operator"=>"AND"
                              ),
                              Array(
                                 "field"=>"id_localidad",
                                 "value"=>$localidad
                              )
            );

				$delete->where($where);
				$res 	= 	$delete->execute();
				return $res;
			}

			public function getCriterios(){
					return $this->_preferencias["criterios"] ;
			}
	
			public function getDisponibilidad(){
					return $this->_preferencias["disponibilidad"] ;
			}	

			public function getFirma(){
					return $this->_preferencias["firma"] ;
			}
	
			public static function getInstanceById($idUsuario){

				$obj =  self::getInstance("id",$idUsuario);

				if (is_bool($obj) and $obj == FALSE)
				   throw(new \Exception("No se pudo obtener la instancia del usuario"));
			   else 
					return $obj ;

			}

			public static function getInstanceByEmail($email){

				return self::getInstance("email",$email);

			}

			public function getPostulaciones(){

				$table	=	new \apf\db\mysql5\Table("usuarios_postulaciones");
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id_oferta"));

				$where	=	Array(
										Array(
											"field"=>"id_usuario",
											"value"=>$this->_id
										)
				);

				$select->where($where);

				$res	=	$select->execute();

				if(!sizeof($res)){

					return FALSE;

				}

				return $res;

			}

			public function agregarPreferencia($tipo,$valor,$multi=TRUE){

				if(isset($this->_preferencias[$tipo])){

					if(in_array($valor,$this->_preferencias[$tipo])){

						return FALSE;

					}

				}	

				if($multi){

					$this->_preferencias[$tipo][]	=	$valor;

				}else{

					$this->_preferencias[$tipo]	=	$valor;

				}

			}

			public function setId($id){

				$this->_id	=	$id;

			}

			public function getId(){

				return $this->_id;

			}

			public function getPreferencia($nombre){

				if(!isset($this->_preferencias[$nombre])){

					return FALSE;

				}

				return $this->_preferencias[$nombre];

			}

			public function getDatos(){

			}

			public function setNombre($value){

				$this->_nombre	=	$value;

			}

			public function getNombre(){

				return $this->_nombre;

			}

			public function sePostulo(Oferta $oferta){

				$table	=	new \apf\db\mysql5\Table("usuarios_postulaciones");
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id_usuario","id_oferta"));

				$where	=	Array(
					Array(
						"field"=>"id_usuario",
						"value"=>$this->_id
					),
					Array(
						"operator"=>"AND"
					),
					Array(
						"field"=>"id_oferta",
						"value"=>$oferta->getId()
					)
				);

				$select->where($where);

				$res	=	$select->execute();

				if(!sizeof($res)){

					return FALSE;	

				}

				return TRUE;

			}

			/**
			*Obtiene una instancia del objeto usuario mediante 
			*el hash asignado a una oferta ya enviada de 
			*la tabla usuarios_postulaciones
			*/

			public static function getInstanceByHash($hash){

				$table	=	new \apf\db\mysql5\Table("usuarios_postulaciones");
				$select	=	new \apf\db\mysql5\Select($table);
				
				$select->fields(Array("id_usuario","id_oferta","momento_postulacion","momento_respuesta","lecturas","hash"));

				$where	=	Array(
										Array(
											"field"=>"hash",
											"value"=>$hash
										)
				);

				$select->where($where);

				$select->limit(Array(1));

				$res	=	$select->execute();

				if(!sizeof($res)){

					return FALSE;

				}

				return Array(
									"datos_oferta"=>$res,
									"usuario"=>self::getInstanceById($res["id_usuario"])
				);

			}
			public function actualizarFirma($firma=NULL) { 

 				$table   =  new \apf\db\mysql5\Table("usuarios_datos");
            $update  =  new \apf\db\mysql5\Update($table);

            $fields  =  Array(
                              "firma"=>$firma,
            );

            $update->fields($fields);

            $where   =  Array(
                               Array(
                                  "field"=>"id_usuario",
                                  "value"=>$this->getId()
                              )
            );

				$ret = $update->execute();
				return $ret;

			}

			public function actualizarDisponibilidad($disponibilidad=NULL) { 

 				$table   =  new \apf\db\mysql5\Table("usuarios_datos");
            $update  =  new \apf\db\mysql5\Update($table);

            $fields  =  Array(
                              "disponibilidad"=>$disponibilidad,
            );

            $update->fields($fields);

            $where   =  Array(
                               Array(
                                  "field"=>"id_usuario",
                                  "value"=>$this->getId()
                              )
            );

				$ret = $update->execute();
				return $ret;

				}
			public function actualizarHash(Array $datosOferta,$remoteAddr="127.0.0.1"){

				$table	=	new \apf\db\mysql5\Table("usuarios_postulaciones");

				$replace	=	new \apf\db\mysql5\Replace($table);

				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("lecturas"));

				$where	=	Array(
										Array(
											"field"=>"hash",
											"value"=>$datosOferta["hash"]
										),
										Array(
											"operator"=>"AND"
										),
										Array(
											"field"=>"remote_addr",
											"value"=>$remoteAddr
										)
				);


				$select->where($where);

				$res			=	$select->execute();

				if($res){

					$lecturas	=	$res["lecturas"];
					$lecturas	+=1;

				}else{

					$lecturas	=	1;

				}


				$replace->id_usuario				=	$datosOferta["id_usuario"];
				$replace->id_oferta				=	$datosOferta["id_oferta"];
				$replace->hash						=	$datosOferta["hash"];
				$replace->momento_postulacion	=	$datosOferta["momento_postulacion"];
				$replace->remote_addr			=	$remoteAddr;
				$replace->momento_respuesta	=	Array("value"=>"NOW()","quote"=>FALSE);
				$replace->lecturas				=	$lecturas;

				$replace->execute();

			}

			public function agregarCriterioUsuario($criterio=NULL,$excluir=0){

				$table            	=  new \apf\db\mysql5\Table("usuarios_criterios");
            $insert           	=  new \apf\db\mysql5\Insert($table);
            $insert->criterio   	=  $criterio;
            $insert->excluir 		=  $excluir;
            $insert->id_usuario 	=  $this->getId();
            $res  =  $insert->execute();
				return $res;

			}

			public function existeProvinciaUsuario($id_provincia=NULL){

            $table   =  new \apf\db\mysql5\Table("usuarios_ubicaciones");
            $select  =  new \apf\db\mysql5\Select($table);

            $select->fields(Array("id_usuario"));

            $where   =  Array(
                              Array(
                                 "field"=>"id_usuario",
                                 "value"=>$this->getId()
                              ),
										Array(
											"operator"=>"AND"
										),	
 										Array(
                                 "field"=>"id_provincia",
                                 "value"=>$id_provincia
                              )

            );

            $select->where($where);

            $select->limit(Array(1));

            $res  =  $select->execute();
	
            if(!sizeof($res)){

               return FALSE;

            }

				return true;	

         }

			public function existeLocalidadUsuario($id_localidad){

            $table   =  new \apf\db\mysql5\Table("usuarios_ubicaciones");
            $select  =  new \apf\db\mysql5\Select($table);

            $select->fields(Array("id_usuario"));

            $where   =  Array(
                              Array(
                                 "field"=>"id_usuario",
                                 "value"=>$this->getId()
                              ),
										Array(
											"operator"=>"AND"
										),	
 										Array(
                                 "field"=>"id_localidad",
                                 "value"=>$id_localidad
                              )

            );

            $select->where($where);

            $select->limit(Array(1));

            $res  =  $select->execute();
	
            if(!sizeof($res)){

               return FALSE;

            }

				return true;	

         }

			public function existeBarrioUsuario($id_barrio){

            $table   =  new \apf\db\mysql5\Table("usuarios_ubicaciones");
            $select  =  new \apf\db\mysql5\Select($table);

            $select->fields(Array("id_usuario"));

            $where   =  Array(
                              Array(
                                 "field"=>"id_usuario",
                                 "value"=>$this->getId()
                              ),
										Array(
											"operator"=>"AND"
										),	
 										Array(
                                 "field"=>"id_barrio",
                                 "value"=>$id_barrio
                              )

            );

            $select->where($where);

            $select->limit(Array(1));

            $res  =  $select->execute();
	
            if(!sizeof($res)){

               return FALSE;

            }

				return true;	

         }


		 	public function existeCategoriaUsuario($id_categoria){

            $table   =  new \apf\db\mysql5\Table("usuarios_categorias");
            $select  =  new \apf\db\mysql5\Select($table);

            $select->fields(Array("id_usuario"));

            $where   =  Array(
                              Array(
                                 "field"=>"id_usuario",
                                 "value"=>$this->getId()
                              ),
                              Array(
                                 "operator"=>"AND"
                              ),
                              Array(
                                 "field"=>"id_categoria",
                                 "value"=>$id_categoria
                              )

            );

            $select->where($where);

            $select->limit(Array(1));

            $res  =  $select->execute();

            if(!sizeof($res)){

               return FALSE;

            }

            return true;

         }

			public function getSalario(){
				return $this->_preferencias["salario"];
			} 
         		
			public function actualizarSalario($salario=NULL){

				$table   =  new \apf\db\mysql5\Table("usuarios_datos");
           	$update  =  new \apf\db\mysql5\Update($table);
            $fields  =  Array(
                              "salario"=>$salario,
           	);

				$update->fields($fields);

           	$where   =  Array(
                               Array(
                                  "field"=>"id_usuario",
                                  "value"=>$this->getId()
                              )
            );

            $update->where($where);

            return $update->execute();

			}

			public function agregarLocalidadUsuario($id_localidad=NULL){
         	
				if($id_localidad==NULL){
					return FALSE;	
				}	
   
				$table            	=  new \apf\db\mysql5\Table("usuarios_ubicaciones");
				$insert           	=  new \apf\db\mysql5\Insert($table);
				$res = '';
	
				if(is_numeric($id_localidad) && !$this->existeLocalidadUsuario($id_localidad)){
					$insert->id_usuario   = $this->getId();
					$insert->id_localidad = $id_localidad;
					$res  =  $insert->execute();
				}
				return $res ;	 

			}

			public function guardarDatosUsuario($categorias=NULL,$salario=NULL,$firma=NULL,$disponibilidad=NULL,$localidades=NULL,$provincias=NULL,$barrios=NULL){

				$table_cat          	=  new \apf\db\mysql5\Table("usuarios_categorias");
				$table_ubicaciones  	=  new \apf\db\mysql5\Table("usuarios_ubicaciones");
            $insert_cat         	=  new \apf\db\mysql5\Insert($table_cat);
            $insert_ubicaciones 	=  new \apf\db\mysql5\Insert($table_ubicaciones);
				$categorias_array    =  split(",",$categorias);			
				$provincias_array    =  split(",",$provincias);			
				$localidades_array   =  split(",",$localidades);			
				$barrios_array    	=  split(",",$barrios);			


				$res = '';
				$this->eliminarTodasCategoriasUsuario();
				$this->eliminarTodasUbicacionesUsuario();	

				foreach($barrios_array as $key => $val){
               if(is_numeric($val) && $val!=0 && !$this->existeBarrioUsuario($val)){
                     $insert_ubicaciones->id_usuario      = $this->getId();
                     $insert_ubicaciones->id_barrio	    = $val;
                     $res                                 = $insert_ubicaciones->execute();
               }
            }

            $insert_ubicaciones        =  new \apf\db\mysql5\Insert($table_ubicaciones);

				foreach($localidades_array as $key => $val){
					if(is_numeric($val) && $val!=0 && !$this->existeLocalidadUsuario($val)){
							$insert_ubicaciones->id_usuario  	 = $this->getId();
							$insert_ubicaciones->id_localidad	 = $val;
         				$res 								          = $insert_ubicaciones->execute();
					}
				}
				
            $insert_ubicaciones     	=  new \apf\db\mysql5\Insert($table_ubicaciones);

				foreach($provincias_array as $key => $val){
					if(is_numeric($val) && $val!=0 && !$this->existeProvinciaUsuario($val)){
							$insert_ubicaciones->id_usuario  	 = $this->getId();
							$insert_ubicaciones->id_provincia	 = $val;
         				$res 								 			 =  $insert_ubicaciones->execute();
					}
				}

            $insert_ubicaciones     	=  new \apf\db\mysql5\Insert($table_ubicaciones);

				foreach ($barrios_array as $key => $val){
					if(is_numeric($val) && $val!=0 && !$this->existeBarrioUsuario($val)){
							$insert_ubicaciones->id_usuario  	 = $this->getId();
							$insert_ubicaciones->id_barrio		 = $val;
         				$res 								 			 = $insert_ubicaciones->execute();
					}
				}

				foreach($categorias_array as $key => $val){
						if(is_numeric($val) && !$this->existeCategoriaUsuario($val)){
							$insert_cat->id_usuario   = $this->getId();
							$insert_cat->id_categoria = $val;
         	   		$res  =  $insert_cat->execute();
						}
				}

				$this->actualizarFirma($firma);
				$this->actualizarDisponibilidad($disponibilidad);
				$this->actualizarSalario($salario);

				return $res;

			}		
	
	
			public function postular(Oferta $oferta){

				$mailOferta		=	$oferta->getEmail();
					
				$firma			=	ucwords(strtolower($this->_nombre)). ' ' . ucwords(strtolower($this->_apellido));
				$asunto			=	"CV ". $firma . '| '.$oferta->getTitulo();

				if($this->_preferencias["salario"]){

					$remuneracion	=	'$'.$this->_preferencias["salario"].' netos';

				}

				if(!empty($remuneracion)){

					$remuneracion	=	"&nbsp;&nbsp;&nbsp;&nbsp;<strong>Remuneracion pretendida:".$remuneracion."</strong><br /><br />\r\n";

				}else{

					$remuneracion	=	"";

				}

				$hash	=	sha1($this->_id.$oferta->getId().$oferta->getTitulo().$oferta->getEmail());

				$body	=	"<p>Ante quien corresponda:<br /><br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Espero tenga a bien contemplar mi curriculum vitae.\r\n<br /><br />
				$remuneracion
				&nbsp;&nbsp;&nbsp;Desde ya muchas gracias.<br /><br />
				<img src=\"http://tebuscolaburo.no-ip.org/oferta/reply/hash/$hash\" />;
				$firma\r\n</p>";

				try{

					$mail					=	\Mail\Factory::getInstance($this->_email,$this->_email,$this->_preferencias["clave"]);
					$mail->Subject		=	$asunto;
					$mail->addAddress($oferta->getEmail(),"");
					$mail->setFrom($this->_email,ucwords($this->_nombre).' '.ucwords($this->_apellido));
					$mail->MsgHTML($body);
					$mail->AddAttachment("files/".$this->_cvFile);
					$mail->Send();

					//Si el mail se envia correctamente, entonces inserto 

					$table	=	new \apf\db\mysql5\Table("usuarios_postulaciones");
					$replace	=	new \apf\db\mysql5\Replace($table);

					$replace->id_usuario				=	$this->_id;
					$replace->id_oferta				=	$oferta->getId();
					$replace->momento_postulacion	=	Array("value"=>"NOW()","quote"=>FALSE);

					//Con este hash luego podemos comprobar si a la persona/entidad a la cual le enviamos el mail
					//Lo recibio o no, siempre y cuando acepte imagenes en su correo.

					$replace->hash			=	$hash;

					$replace->execute();


				}catch (Exception $e){
		

					echo $e->getMessage()."\n"; //Boring error messages from anything else!

				}

			}

			public function agegarCriterio($criterio=NULL){

				$table   =  new \apf\db\mysql5\Table("usuarios_criterio");

            $replace =  new \apf\db\mysql5\Replace($table);

            $insert  =  new \apf\db\mysql5\Insert($table);
				
				$insert->criterio = $criterio;
				
			$insert->id_usuario= $this->id_usuario;
				
				$res = $insert->execute();

			}	

			public function setApellido($value){

				$this->_apellido	=	$value;

			}

			public function getEdad(){

				$dias = explode("-",$this->_fechaNacimiento, 3);
				$dias = mktime(0,0,0,$dias[2],$dias[1],$dias[0]);
				$edad = (int)((time()-$dias)/31556926 );

				return $edad; 
			}

			public function getApellido(){

				return $this->_apellido;


				return $this->_apellido;

			}

			public function setFechaNacimiento($value){

				$this->_fechaNacimiento	=	$value;

			}

			public function getFechaNacimiento(){

				return $this->_fechaNacimiento;

			}

			public function setEmail($value){

				$this->_email	=	$value;

			}

			public function getEmail(){

				return $this->_email;

			}

			public function setCvFile($value){

				$this->_cvFile	=	$value;

			}

			public function getCvFile(){

				return $this->_cvFile;

			}

			public function setPreferencias($value){

				$this->_preferencias	=	$value;

			}

			public function getPreferencias(){

				return $this->_preferencias;

			}

		}

	}

?>
