<?php

	namespace buscolaburo\model{

		class Empresa{

			private	$_localidad		=	NULL;	//Modelo de tipo Localidad

			private	$_id				=	NULL;
			private	$_nombre			=	NULL;
			private	$_email			=	NULL;
			private	$_direccion		=	NULL;
			private	$_descripcion	=	NULL;
			private	$_uriEmpresa	=	NULL;
			private	$_uriImport		=	NULL;
			private	$_uriLogo		=	NULL;
			private	$_contacto		=	NULL;

			private	$_table			=	"empresas";


			public function __construct($valor=NULL){
		
				if(!is_null($valor)){

					if(is_int($valor)){

						$this->setId($valor);
						$this->getEmpresaById();

					}else{

						$this->setNombre($valor);
						$this->getEmpresaByNombre();

					}

				}

			}

			public function setContacto($contacto){

				$this->_contacto	=	$contacto;

			}

			public function getContacto(){

				return $this->_contacto;

			}

			public function setDireccion($direccion){

				$this->_direccion	=	$direccion;

			}

			public function getDireccion(){

				return $this->_direccion;

			}

			public function setDescripcion($descripcion){

				$this->_descripcion	=	$descripcion;

			}

			public function getDescripcion(){

				return $this->_descripcion;

			}

			public function setUriImport($uri){

				$this->_uriImport	=	$uri;

			}

			public function getUriImport(){

				return $this->_uriImport;

			}

			public function setUriLogo($uriLogo){

				$this->_uriLogo	=	$uriLogo;

			}

			public function getUriLogo(){

				return $this->_uriLogo;

			}

			public function setUriEmpresa($uriEmpresa){

				$this->_uriEmpresa	=	$uriEmpresa;

			}

			public function getUriEmpresa(){

				return $this->_uriEmpresa;

			}



			public function setLocalidad(Localidad $localidad){

				$this->_localidad	=	$localidad;

			}

			public function getLocalidad(){

				return $this->_localidad;

			}

			public function setEmail($email){

				$this->_email	=	$email;

			}

			public function getEmail(){

				return $this->_email;

			}

			public function setId($id){

				$this->_id	=	(int)$id;

			}

			public function getId(){

				return $this->_id;

			}


			public function setNombre($nombre){

				$this->_nombre	=	$nombre;

			}

			public function getNombre(){

				return $this->_nombre;

			}

			public function getEmpresaByNombre(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre","email"));

				$where	=	Array(
											Array(
												"field"=>"nombre",
												"value"=>$this->_nombre
											)
				);

				$select->where($where);

				$res	=	$select->execute();

				if(!sizeof($res)){

					throw(new \Exception("No se encontro la empresa con nombre ".$this->_nombre));

				}

				$this->setId($res["id"]);
				$this->setEmail($res["email"]);

			}


			public function getEmpresaById(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$select->where(	
										Array(
												Array(
													"field"=>"id_localidad",
													"value"=>$this->_localidad->getId()
												),
												Array(
													"operator"=>"AND"
												),
												Array(
													"field"=>"id",
													"value"=>$this->_id	
												)
										)
				);


				$res	=	$select->execute();

				if(!sizeof($res)){

					throw(new \Exception("No se encontro la empresa con ID \"".$this->_id."\" para la localidad ".$this->_localidad->getNombre()));

				}

				$this->setNombre($res["nombre"]);
				$this->setEmail($res["email"]);

			}

			public function agregar(){

				$table						=	new \apf\db\mysql5\Table($this->_table);
				$insert						=	new \apf\db\mysql5\Insert($table);

				$insert->nombre			=	$this->_nombre;
				$res							=	$insert->execute();

				$this->setId($res);

			}

			public function tieneDatosDeEmpresa(){

				$table	=	new \apf\db\mysql5\Table("empresas_datos");
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id"));

				$where	=	Array(
										Array(
											"field"=>"id_empresa",
											"value"=>$this->_id
										)
				);

				$select->where($where);

				return $select->execute();

			}

			public function tieneUriImport(){

				$table	=	new \apf\db\mysql5\Table("empresas_datos");
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("uri_import"));

				$where	=	Array(
										Array(
											"field"=>"id_empresa",
											"value"=>$this->_id
										),
										Array(
											"operator"=>"AND"
										),
										Array(
											"field"=>"uri_import",
											"value"=>$this->_uriImport
										)
				);

				$select->where($where);

				return $select->execute();

			}

			public function agregarDescripcion(){

				$table						=	new \apf\db\mysql5\Table("empresas_datos");
				$insert						=	new \apf\db\mysql5\Insert($table);

				$insert->id_empresa		=	$this->_id;
				$insert->uri_import		=	$this->_uriImport;
				$insert->uri_empresa		=	$this->_uriEmpresa;
				$insert->uri_logo			=	$this->_uriLogo;
				$insert->email				=	$this->_email;
				$insert->descripcion		=	$this->_descripcion;
				$insert->direccion		=	$this->_direccion;
				$insert->contacto			=	$this->_contacto;

				return $insert->execute();


			}

			public function existe(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id"));

				$where	=	Array(
										Array(
												"field"=>"nombre",
												"value"=>$this->_nombre
										)
				);

				$select->where($where);

				return $select->execute();

			}

		}

	}

?>
