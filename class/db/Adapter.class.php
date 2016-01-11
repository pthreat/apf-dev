<?php

	namespace apf\db{

		use \apf\core\Directory as Dir;		
		use \apf\db\Connection	as	DatabaseConnection;
		use \apf\iface\Log		as	LogInterface;

		abstract class Adapter{

			use \apf\traits\log\Inner;

			private				$connection				=	NULL;
			private	static	$availableAdapters	=	Array();

			final private function __construct(DatabaseConnection &$connection){

				if(!$connection->isValidated()){

					$connection->validateConfig();

				}

				$child				=	get_class($this);
				$childConnection	=	sprintf('%s\\%s',substr($child,0,strrpos($child,'\\')),'Connection');

				if(!($connection instanceof $childConnection)){

					$passed	=	get_class($connection);
					$msg		=	sprintf('The database connection for this adapter must be of type "%s", "%s" was given',$passed,$childConnection);
					throw new \LogicException($msg);

				}

				$this->connection	=	$connection;

			}

			/**
			 *
			 * This is a small factory method for getting an adapter.
			 *
			 * Passing an object:
			 *---------------------------------------------------------------------------------------------------
			 *
			 * If an object is passed it will call the class constructor with the given object.
			 * Given object must be of type \apf\db\Connection.
			 *
			 * If passing an object, the given database connection object must be properly configured.
			 *
			 * Passing a string:
			 *---------------------------------------------------------------------------------------------------
			 *
			 * If a string is passed, it will try to consult the DatabaseConnectionPool (\apf\db\connection\Pool)
			 * For a connection with the id $connection.
			 *
			 * If passing a string, the given string must be a valid connection identifier in the database
			 * connection pool.
			 *
			 *---------------------------------------------------------------------------------------------------
			 *
			 * @param mixed $connection A connection identifier of an \apf\db\Connection object.
			 * @return \apf\db\Adapter
			 *
			 */

			public static function getInstance($connection,LogInterface $log=NULL){

				if(is_object($connection)){

					return new static($connection);

				}

				$obj	=	new static(DatabaseConnectionPool::get($connection));

				if($connection->getConfig()->getEnableLogging() && !is_null($log)){

					$obj->setLog($log);

				}

				return $obj;

			}

			final protected function getConnection(){

				return $this->connection;

			}

			final public function getCommentOpenings(){

				$openings	=	$this->__getCommentOpenings();

				if(!is_array($openings)){

					throw new \Exception("__getCommentOpenings must return an array");

				}

				return $openings;

			}

			final public function getSingleQuoteCharacter(){

				$char = $this->__getQuoteCharacter();

				if(empty($char)){

					throw new \InvalidArgumentException("__getSingleQuoteCharacter must return a string");

				}

				return $char;

			}

			final public function escapeValue($value){

				return $this->__escapeValue($value);

			}

			public function getDate($format="Y-m-d H:i:s"){

				$date	=	$this->getDate($format);

				if(empty($date)){

					throw new \RuntimeException("Adapter has returned an invalid date string!");

				}

				return $date;

			}

			public function getDateObject(){

				$date	=	\DateTime::createFromFormat('Y-m-d H:i:s',$this->getDate());

				if(!$date){

					throw new \RuntimeException("Method getDate has returned an invalid date string!");

				}

				return $date;

			}

			public function directQuery($sql){

				$this->__logWarning("WARNING!!! Executing direct query (not recommended)");
				$this->__logDebug($sql);

				return $this->__directQuery($sql);

			}

			//List available adapters

			public static function listAvailable($refresh=FALSE){

				if(sizeof(self::$availableAdapters)&&!$refresh){

					return self::$availableAdapters;

				}

				$dir	=	sprintf('%s%sadapter',__DIR__,DIRECTORY_SEPARATOR);

				if(!is_dir($dir)){

					$msg	=	"Could not find database adapters directory where it's supposed to be. I tried to find it in \"$dir\"";
					$msg	=	sprintf('%s, however it is not there or is not a directory, please check your framework installation.',$msg);

					throw new \RuntimeException($msg);

				}

				return self::$availableAdapters	=	(new Dir($dir))->toArray();

			}

			public function __clone(){

				throw(new \Exception("Cloning database connections is not possible"));

			}

			abstract protected function __escapeValue($value);
			abstract protected function __getCommentOpenings();
			abstract protected function __getCommentClosings();
			abstract protected function __getQuoteCharacter();
			abstract protected function __startTransaction();
			abstract protected function __commitTransaction();
			abstract protected function __rollback();
			abstract protected function __directQuery($sql);
			abstract protected function __getDate($format="Y-m-d H:i:s");
			abstract public function listTables($cache=TRUE);
			abstract public function findTable($name);

		}

	}

