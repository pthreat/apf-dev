<?php

	namespace apf\net\connection{

		use apf\net\Connection	as	NetworkConnection;

		abstract class Pool{

			use \apf\traits\log\Inner;

			private $connections	=	Array();

			private function checkPoolKeyConstant(){

				if(defined('static::POOL_KEY')){

					return TRUE;

				}

				throw new \LogicException("Invalid connection pool, no POOL_KEY constant specified");

			}

			private function __getConnectionId($connection){

				if(is_object($connection)){

					if(!($connection instanceof NetworkConnection)){

						throw new \InvalidArgumentException("Invalid argument provided, object passed is not a network connection");

					}

					$connection	=	$connection->getConfig()->getId();

				}

				if(is_string($connection)){

					$connection	=	trim($connection);

				}

				if(empty($connection)){

					throw new \InvalidArgumentException("Empty connection identifier provided!");

				}

				return $connection;

			}

			public function add(NetworkConnection $connection){

				if(!$connection->isValidated()){

					$connection->validateConfig();

				}

				$conConf	=	&$connection->getConfig();

				try{
					
					$this->hasConnection($conConf->getId());
					throw new \LogicException("A connection with the id \"{$conConf->getId()}\" already exists.");

				}catch(\Exception $e){

					$this->__logInfo("Adding connection \"{$conConf->getId()}\" to the connection pool");
					$this->connections[static::POOL_KEY][$this->__getConnectionId($connection)]	=	$connection;

				}

			}

			public function hasAvailableConnections(){

				$this->checkPoolKeyConstant();

				if(!array_key_exists(static::POOL_KEY,$this->connections)){

					return FALSE;

				}

				return sizeof($this->connections[static::POOL_KEY]);

			}

			private function __hasAvailableConnections(){

				if($this->hasAvailableConnections()){

					return TRUE;

				}

				throw new \LogicException(sprintf('No %s connections available',static::POOL_KEY));

			}

			public function get($id){

				$this->checkPoolKeyConstant();
				$this->__hasAvailableConnections();

				$id	=	$this->__getConnectionId($id);

				if(!$this->hasConnection($id)){

					$msg	=	sprintf('Invalid %s connection identifier "%s"',static::POOL_KEY,$id);
					throw new \InvalidArgumentException($msg);

				}

				$connection	=	$this->connections[static::POOL_KEY][$id];

				return $connection;

			}

			public function hasConnection($id){

				$this->checkPoolKeyConstant();
				$this->__hasAvailableConnections();

				return array_key_exists($id,$this->connections[static::POOL_KEY]);

			}

		}	

	}
