<?php

	namespace apf\model{

		class Queue{

			private	$_id					=	NULL;
			private	$_categoria			=	NULL;
			private	$_pais				=	NULL;
			private	$_provincia			=	NULL;
			private	$_uri					=	NULL;
			private	$_estado				=	NULL;
			private	$_momentoProceso	=	NULL;
			private	$_pid					=	NULL;
			private	$_error				=	0;
			private	$_msgError			=	NULL;

			private	$_idOferta			=	0;

			private	$_fromHist			=	FALSE;

			private	$_table				=	"uri_queue";

			public function setPid($pid){

				$this->_pid	=	(int)$pid;

			}

			public function getPid(){

				return $this->_pid;

			}

			public function setError($bool=TRUE,$mensaje=NULL){

				$this->_error		=	(string)$bool;
				$this->_msgError	=	$mensaje;

			}

			public function getError(){

				return $this->_error;

			}

			public function getUnprocessedQueueAmount(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("COUNT(*) AS cant"));

				$where	=	Array(
											Array(
												"field"=>"estado",
												"value"=>"0"
											)
				);

				$select->where($where);

				return $select->execute();

			}

			public function obtenerDesdeHistorico($bool=TRUE){

				$this->_fromHist	=	(boolean)$bool;

			}

			public function fetch($limit=1,$offset=0,$pid=NULL,$wherePid=FALSE){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$fields	=	Array(
					"id",
					"estado",
					"uri",
					"categoria",
					"pais",
					"provincia"
				);

				$select->fields($fields);

				$where	=	Array(
											Array(
												"field"=>"estado",
												"operator"=>"!=",
												"value"=>"-1"
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"estado",
												"operator"=>"!=",
												"value"=>"1"
											)
											
				);

				if($wherePid){

					$where[]	=	Array(
									"operator"=>"AND"
					);	

					$where[]	=	Array(
											"field"=>"pid",
											"value"=>$pid
									);

				}

				if($offset>0){

					$select->offset($offset);

				}

				$select->where($where);

				$select->limit(Array($limit));

				$uris					=	$select->execute($smartFetch=FALSE);

				$returnQueue		=	Array();

				$class				=	__CLASS__;

				foreach($uris as $uri){

					$queue			=	new $class();
					
					$queue->setId($uri["id"]);
					$queue->setEstado('-1');

					if($pid){

						$queue->setPid($pid);

					}

					$queue->update();

					$queue->setUri($uri["uri"]);
					$queue->setCategoria($uri["categoria"]);
					$queue->setPais($uri["pais"]);
					$queue->setProvincia($uri["provincia"]);

					$returnQueue[]	=	$queue;

				}

				return $returnQueue;

			}

			public function cleanUp(){

				if(!$this->_pid){

					throw(new \Exception("Can't clean up without a pid, sorry"));

				}

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$delete	=	new \apf\db\mysql5\Delete($table);

				$where	=	Array(
										Array(
											"field"=>"estado",
											"value"=>"1"
										),
										Array(
											"operator"=>"AND"
										),
										Array(
											"field"=>"pid",
											"value"=>$this->_pid
										)
				);

				//$res		=	$select->execute();

				//foreach($res as $queue){
					
				//}

				$delete->where($where);

				return $delete->execute();

			}


			public function setId($id){

				$this->_id	=	(int)$id;

			}

			public function getId(){

				return $this->_id;
		
			}

			public function setEstado($estado){

				$this->_estado	=	(string)$estado;

			}

			public function getEstado(){

				return $this->_estado;
		
			}

			public function setPais($pais){

				$this->_pais	=	$pais;

			}

			public function getPais(){

				return $this->_pais;

			}

			public function setProvincia($provincia){

				$this->_provincia	=	$provincia;

			}

			public function getProvincia(){

				return $this->_provincia;

			}

			public function setCategoria($categoria){

				$this->_categoria	=	$categoria;

			}

			public function getCategoria(){

				return $this->_categoria;

			}

			public function setUri($uri){

				$this->_uri	=	$uri;

			}

			public function getUri(){

				return $this->_uri;

			}

			public function exists(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id"));

				$where	=	Array(
											Array(
												"field"=>"uri",
												"value"=>$this->_uri
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"categoria",
												"value"=>$this->_categoria
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"provincia",
												"value"=>$this->_provincia
											)
											
										
				);

				$select->where($where);

				$res	=	$select->execute();

				if($res){

					return TRUE;

				}

				return FALSE;

			}


			public function delete(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$delete	=	new \apf\db\mysql5\Delete($table);

				$where	=	Array(
										Array(
												"field"=>"id",
												"value"=>$this->_id
										)
				);

				$delete->where($where);

				return $delete->execute();
				
			}

			public function setIdOferta($idOferta){

				$this->_idOferta	=	(int)$idOferta;

			}

			public function getIdOferta(){

				return $this->_idOferta;

			}

			public function moverAHistorico(){

				$table	=	new \apf\db\mysql5\Table("uri_queue_hist");
				$insert	=	new \apf\db\mysql5\Replace($table);

				$insert->id						=	$this->_id;
				$insert->id_oferta			=	$this->_idOferta;
				$insert->categoria			=	$this->_categoria;
				$insert->pais					=	$this->_pais;
				$insert->provincia			=	$this->_provincia;
				$insert->uri					=	$this->_uri;
				$insert->estado				=	$this->_estado;
				$insert->pid					=	$this->_pid;
				$insert->error					=	$this->_error;
				$insert->mensaje_error		=	$this->_msgError;
				$insert->momento_proceso	=	$this->_momentoProceso;

				if($insert->execute()){

					$this->delete();
					return TRUE;

				}

				return FALSE;

			}

			public function getMomentoProceso(){

				return $this->_momentoProceso;

			}

			public function setMomentoProceso($date){
				$this->_momentoProceso	=	$date;
			}

			public function reset(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$update	=	new \apf\db\mysql5\Update($table);

				$fields	=	Array(
											"estado"=>0
				);

				$update->fields($fields);

				return $update->execute();

			}

			public function update(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$update	=	new \apf\db\mysql5\Update($table);

				$db		=	\apf\db\mysql5\Adapter::getInstance();
				$date		=	$db->getDate();

				$this->setMomentoProceso($date);
	
				$fields	=	Array(
											"id_oferta"=>$this->_idOferta,
											"estado"=>(string)$this->_estado,
											"momento_proceso"=>$date,
											"pid"=>$this->_pid,
											"error"=>$this->_error,
											"mensaje_error"=>$this->_msgError
				);


				$update->fields($fields);

				$where	=	Array(
										Array(
											"field"=>"id",
											"value"=>$this->_id
										)
				);

				$update->where($where);

				return $update->execute();

			}

			public function agregar(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$insert	=	new \apf\db\mysql5\Insert($table);

				$insert->pais			=	$this->_pais;
				$insert->categoria	=	$this->_categoria;
				$insert->estado		=	'0';
				$insert->provincia	=	$this->_provincia;
				$insert->uri			=	$this->_uri;

				return $insert->execute();

			}

		}

	}

?>
