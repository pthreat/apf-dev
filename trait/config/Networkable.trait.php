<?php

	namespace apf\traits\config{

		use \apf\net\Connection	as	NetworkConnection;

		trait Networkable{

			public function setConnections(Array $connections){

				if(!parent::getConnections()){

					$this->connections	=	Array();

				}

				foreach($connections as $key=>$connection){

					if(!is_a($connection,'\\apf\\net\\Connection')){

						throw new \InvalidArgumentException("Given array element ($key) is not a network connection");

					}

					$this->connections->append($connection);

				}

				return $this;

			}

			public function getConnections(){

				return parent::getConnections();

			}

			public function addConnection(NetworkConnection $connection){

				$this->setConnections(Array($connection));
				return $this;

			}

			public function getConnection($type,$name){

				$connections	=	parent::getConnections();	

				$type				=	trim(strtolower($type));
				$name				=	trim(strtolower($name));

				if(!$connections){

					return NULL;

				}

				foreach($connections as $connection){

					if($connection->getType()==$type && $connection->getName() == $name){

						return $connection;

					}

				}

				return FALSE;

			}

			public function hasConnections(){

				return parent::getConnections() ? TRUE : FALSE;

			}

			public function hasConnectionsOfType($type){

				$connections	=	parent::getConnections();	
				$type				=	trim(strtolower($type));

				if(!$connections){

					return NULL;

				}

				foreach($connections as $connection){

					if(strtolower($connection->getConfig()->getType()) == $type){

						return TRUE;

					}

				}

				return FALSE;

			}

		}

	}
