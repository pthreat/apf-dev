<?php

	namespace apf\traits{

		abstract class Collection{

			private static $data	=	Array();

			private static function checkCollectionKeyConstant(){

				if(defined('static::COLLECTION_KEY')){

					return TRUE;

				}

				throw new \LogicException("Invalid collection, no COLLECTION_KEY constant specified");

			}

			public function add(Connection $connection){

				self::checkCollectionKeyConstant();
				self::$connections[self::COLLECTION_KEY][self::__getConnectionId($connection)]	=	$connection;

			}

		}

	}

