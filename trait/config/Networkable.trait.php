<?php

	namespace apf\traits\config{

		use \apf\net\Connection	as	NetworkConnection;

		trait Networkable{

			public function setConnections(Array $connections){

				if(!parent::getAssets()){

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

			}

			public function hasConnections(){

				return parent::getConnections() ? TRUE : FALSE;

			}

			public function hasConnectionsOfType($type){

				$connections	=	parent::getConnections();	

				if(!$connections){

					return NULL;

				}

				foreach($connections as $connection){

					if(strtolower($connection->getType()) == strtolower($type)){

						return TRUE;

					}

				}

				return FALSE;

			}

		}

	}
