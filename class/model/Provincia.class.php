<?php

	namespace buscolaburo\model{

		class Provincia{

			private	$_pais		=	NULL;	//Modelo de tipo Pais

			private	$_id		=	NULL;
			private	$_nombre	=	NULL;
			private	$_table	=	"provincias";
			
			public static function getAllProvincias(){

				$arrayObj   =  Array();
				$class      =  __CLASS__;

				$obj        =  new $class();

				$table      =  new \apf\db\mysql5\Table("provincias");
				$select     =  new \apf\db\mysql5\Select($table);

				$fields = Array();
				$select->fields($fields);

				$res = $select->execute();

				return $res ;

			}

			public static function getInstanceById($idProvincia){

				$idProvincia	=	(int)$idProvincia;

				if(!$idProvincia){

					throw(new \Exception("id de provincia invalido especificado \"$idProvincia\""));

				}

				$class		=  __CLASS__;
				$objProv		=	new $class();

				$table		=	new \apf\db\mysql5\Table("provincias");
				$select		=	new \apf\db\mysql5\Select($table);

				$where		=	Array(
											Array(
												"field"=>"id",
												"value"=>$idProvincia
											)
				);

				$select->where($where);

				$select->fields(Array("id","id_pais","nombre"));

				$res = $select->execute($smartMode=TRUE);

				if(!$res){

					return FALSE;

				}

				$objProv->setId($res["id"]);
				$objProv->setNombre($res["nombre"]);
				$pais	=	new \apf\model\Pais((int)$res["id_pais"]);
				$objProv->setPais($pais);

				return $objProv;
				
			}


			public function getLocalidades($soloNormalizadas=FALSE){

				$table		=	new \apf\db\mysql5\Table("localidades");
				$select		=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre","normalizado"));

				$where		=	Array(
										Array(
												"field"=>"id_provincia",
												"value"=>$this->getId()
										)
				);

				if($soloNormalizadas){

					$where[]	=	Array(
											"operator"=>"AND"
					);
	

					$where[]	=	Array(
											"field"=>"normalizado",
											"value"=>"1"
					);
				}
				$orderBy = Array("nombre");
				$select->orderBy($orderBy,"DESC");	
				$select->where($where);

				$res	=	$select->execute($smartMode=FALSE);

				if(!$res){

					return FALSE;

				}				

				$return	=	Array();

				foreach($res as $r){

					$class	=	"\\apf\\model\\Localidad";
					$obj		=	new $class($this);
					$obj->setId($r["id"]);
					$obj->setNombre($r["nombre"]);
					$obj->setNormalizado($r["normalizado"]);

					$return[]	=	$obj;
	
				}

				return $return;

			}

			public function __construct(Pais $pais=NULL,$valor=NULL){

				if(!is_null($pais)){

					$this->setPais($pais);

				}

				if(!is_null($valor)){

					if(is_int($valor)){

						$this->setId($valor);
						$this->getProvinciaById();

					}else{

						$this->setNombre($valor);
						$this->getProvinciaByNombre();

					}

				}

			}

			public function setId($id){

				$this->_id	=	(int)$id;

			}

			public function getId(){
				return $this->_id;
			}	

			public function setPais(Pais $pais){

				$this->_pais	=	$pais;

			}

			public function getPais(){

				return $this->_pais;

			}

			public function setNombre($nombre){

				$this->_nombre	=	$nombre;

			}

			public function getNombre(){

				return $this->_nombre;

			}

			public function getProvinciaByNombre(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where	=	Array(
											Array(
												"field"=>"id_pais",
												"value"=>$this->_pais->getId()
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"nombre",
												"value"=>$this->_nombre
											)
				);


				$select->where($where);

				$res	=	$select->execute();

				$this->setId($res["id"]);

			}

			public function getProvinciaById(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","id_pais","nombre"));

				$where	=	Array(
											Array(
												"field"=>"id",
												"value"=>$this->_id
											)
				);

				$select->where($where);

				try{

					$res	=	$select->execute();

					$pais	=	new Pais($res["id_pais"]);

					$this->setPais($pais);
					$this->setNombre($res["nombre"]);

				}catch(\Exception $e){

					throw(new \Exception("No se pudo encontrar la provincia con el id ".$this->_id));

				}

			}

			public function agregar(){

				$table				=	new \apf\db\mysql5\Table($this->_table);
				$insert				=	new \apf\db\mysql5\Insert($table);
				$insert->nombre	=	$this->_nombre;
				$insert->id_pais	=	$this->_pais->getId();

				$res	=	$insert->execute();

				$this->setId($res);
			
			}

		}

	}

?>
