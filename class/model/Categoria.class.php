<?php

	namespace buscolaburo\model{

		class Categoria{

			private	$_id			=	NULL;
			private	$_nombre		=	NULL;
			private	$_table		=	"categorias";
         
         public static function getAllCategorias(){
 				
				$arrayObj	=	Array();
				$class		=	__CLASS__;

				$obj			=	new $class();

				$table      =  new \apf\db\mysql5\Table("categorias");
            $select     =  new \apf\db\mysql5\Select($table);         

				$fields = Array();
            $select->fields($fields);
           
            $res = $select->execute();

            return $res ;

        }


			public function __construct($valor=NULL){

				if(!is_null($valor)){

					if(is_int($valor)){

						$this->setId($valor);
						$this->getCategoriaById();

					}else{

						$this->setNombre($valor);
						$this->getCategoriaByNombre();

					}

				}

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

			public function getCategoriaById(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where	=	Array(
											Array(
												"field"=>"id",
												"value"=>$this->_id
											)
				);

				$select->where($where);

				$res	=	$select->execute();

				if(!sizeof($res)){

					throw(new \Exception("No se encontro la categoria ".$this->_id));

				}

				$this->setNombre($res["nombre"]);

			}
	
			public function getCategoriaByNombre(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where	=	Array(
											Array(
												"field"=>"nombre",
												"value"=>$this->_nombre
											)
				);

				$select->where($where);

				$res	=	$select->execute();

				if(!sizeof($res)){

					throw(new \Exception("No se encontro la categoria ".$this->_nombre));

				}

				$this->setId($res["id"]);

			}

			public function agregar(){

				$table				=	new \apf\db\mysql5\Table($this->_table);
				$insert				=	new \apf\db\mysql5\Insert($table);

				$insert->nombre	=	$this->_nombre;

				$res	=	$insert->execute();

				$this->setId($res);

			}

		}

	}

?>
