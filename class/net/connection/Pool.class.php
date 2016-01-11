<?php

	namespace apf\net\connection{

		use apf\net\Connection	as	NetworkConnection;

		abstract class Pool{

			use \apf\traits\log\InnerStatik;

			private static $connections	=	Array();

			private static function checkPoolKeyConstant(){

				if(defined('static::POOL_KEY')){

					return TRUE;

				}

				throw new \LogicException("Invalid connection pool, no POOL_KEY constant specified");

			}

			private static function __getConnectionId($connection){

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

			public static function add(NetworkConnection $connection){

				if(!$connection->isValidated()){

					$connection->validateConfig();

				}

				$conConf	=	&$connection->getConfig();

				try{
					
					self::hasConnection($conConf->getId());
					throw new \LogicException("A connection with the id \"{$conConf->getId()}\" already exists.");

				}catch(\Exception $e){

					self::__innerStaticLogInfo("Adding connection \"{$conConf->getId()}\" to the connection pool");
					self::$connections[static::POOL_KEY][self::__getConnectionId($connection)]	=	$connection;

				}

			}

			public static function hasAvailableConnections(){

				self::checkPoolKeyConstant();

				if(!array_key_exists(static::POOL_KEY,self::$connections)){

					return FALSE;

				}

				return sizeof(self::$connections[static::POOL_KEY]);

			}

			private static function __hasAvailableConnections(){

				if(self::hasAvailableConnections()){

					return TRUE;

				}

				throw new \LogicException(sprintf('No %s connections available',static::POOL_KEY));

			}

			public static function get($id){

				self::checkPoolKeyConstant();
				self::__hasAvailableConnections();

				$id	=	self::__getConnectionId($id);

				if(!self::hasConnection($id)){

					$msg	=	sprintf('Invalid %s connection identifier "%s"',static::POOL_KEY,$id);
					throw new \InvalidArgumentException($msg);

				}

				$connection	=	self::$connections[static::POOL_KEY][$id];

				return $connection;

			}

			public static function hasConnection($id){

				self::checkPoolKeyConstant();
				self::__hasAvailableConnections();

				return array_key_exists($id,self::$connections[static::POOL_KEY]);

			}

		}	

	}
