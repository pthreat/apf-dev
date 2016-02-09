<?php

	namespace apf\db{

		use \apf\core\Directory 		as Dir;		
		use \apf\db\Connection			as	DatabaseConnection;
		use \apf\iface\Log				as	LogInterface;
		use \apf\core\Configurable;

		abstract class Adapter extends Configurable{

			use \apf\traits\log\Inner;

			private	static	$availableAdapters	=	Array();

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

			public static function listAvailableAdapters($refresh=FALSE){

				if(sizeof(self::$availableAdapters)&&!$refresh){

					return self::$availableAdapters;

				}

				$dir	=	sprintf('%s%sadapter',__DIR__,DIRECTORY_SEPARATOR);

				if(!is_dir($dir)){

					$msg	=	"Could not find database adapters directory where it's supposed to be. I tried to find them at \"$dir\"";
					$msg	=	sprintf('%s, however they seem not there, or is not a directory, please check your framework installation.',$msg);

					throw new \RuntimeException($msg);

				}

				return self::$availableAdapters	=	(new Dir($dir))->getIterator()

			}

			public function __clone(){

				throw(new \Exception("Cloning database adapters is not possible"));

			}

			abstract protected function __escapeValue($value);
			abstract protected function __startTransaction();
			abstract protected function __commitTransaction();
			abstract protected function __rollback();
			abstract protected function __directQuery($sql);
			abstract protected function __getDate($format="Y-m-d H:i:s");
			abstract public function __listTables($cache=TRUE);
			abstract public function __findTable($name);

		}

	}

