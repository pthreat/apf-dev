<?php

	/**
	 * This is a configuration object, it's sole purpose is to hold configuration values
	 */

	namespace apf\core{

		use \apf\core\config\Value as ConfigValue;

		abstract class Config implements \Iterator{

			private	$values					=	Array();
			private	$securedAttributes	=	Array();

			public function __construct(Config $config=NULL,Array $securedAttributes=Array()){

				if($config){

					$this->merge($config);

				}

				if(sizeof($securedAttributes)){

					$this->setSecuredAttributes($securedAttributes);

				}

			}

			public function setSecuredAttributes(Array $array){

				if(sizeof($this->securedAttributes)){

					throw new \LogicException("Secured attributes have already been established and can not be changed");

				}

				foreach($array as $value){

					if(!in_array($value,$this->values)){

						throw new \InvalidArgumentException("Value \"$value\" does not exists in this configuration values");

					}

				}

				$this->securedAttributes	=	$array;

				return $this;

			}

			public function getSecuredAttributes(){

				return $this->securedAttributes;

			}

			public function toArray(){

				$default	=	self::getDefaultInstance();

				if(!($default instanceof Config)){

					throw new \LogicException("The getDefaultInstance method must return a Config Object");

				}

				$values	=	Array();

				foreach($default as $value){

					$values[$value->getName()]	=	$value->getValue();

				}

				foreach($this->values as $value){

					$values[$value->getName()]	=	$value->getValue();

				}

				return $values;

			}

			public function  __set($key,$value){

				if(is_array($value)){

					$this->initMultipleAttribute($key);

					foreach($value as $k=>$val){
		
						$this->values[$key][$k]	=	new ConfigValue($k,$val);

					}

					return;

				}

				return $this->addValue(new ConfigValue($key,$value));

			}

			public function hasKey($key){

				return array_key_exists($key,$this->values);

			}

			public function &__get($key){

				if(!$this->hasKey($key)){

					throw new \InvalidArgumentException("Parameter \"$key\" not found in section \"{$this->name}\"");

				}

				return $this->values[$key];

			}

			public function attributeIsSecured($attr){

				return in_array($attr,$this->securedAttributes);

			}

			public function addValue(ConfigValue $value){

				$setter		=	"set{$value->getName()}";

				if(!method_exists($this,$setter)){

					throw new \InvalidArgumentException("No setter method named \"$setter\" was found in this configuration class");

				}

				//////////////////////////////////////////////////////////////////////////////////////////////
				//If the attribute is secured, set it as secure, this will avoid certain values be printed 
				//by accident in var_dump, remember that you need PHP 5.6 for this feature to work properly!
				//////////////////////////////////////////////////////////////////////////////////////////////

				if($this->attributeIsSecured($value->getName())){

					$value->setSecure(TRUE);

				}


				$this->values[strtolower($value->getName())]	=	$value;

				return $this;

			}

			protected function initMultipleAttribute($name){

				if($this->hasKey($name)){
					return;
				}

				$this->values[$name]	=	Array();
				return $this;

			}

			public function getValues(){

				return $this->values;

			}

			/**
			* Returns an array with NON exportable configuration attributes. 
			* i.e those attributes/properties that will *NOT* be exported when exporting this configuration to any format
			* This is provided due to the fact that some attributes are part of run time code and not configuration
			* For instance, say the injected Project attribute on a project Module "project" is not something that 
			* you would export into a module configuration file.
			 */

			abstract public function getNonExportableAttributes();

			//////////////////////////////////////
			//Iterator interface
			/////////////////////////////////////

			public function current(){

				return current($this->values);

			}

			public function key(){

				return key($this->values);

			}

			public function next(){

				return next($this->values);

			}

			public function rewind(){

				return reset($this->values);

			}

			public function valid(){

				$key	=	key($this->values);
				return $key!==NULL && $key!==FALSE;

			}

			public function merge(Config $config){

				foreach($config->values as $value){

					$this->addValue($value);

				}

				return $this;

			}

			//A configuration adapter is basically a configuration format
			//Say ... JSON, XML, INI, YML

			private static function __getAdapter($adapter){

				$adapter			=	ucwords($adapter);
				$exportClass	=	sprintf('\\apf\\core\\config\\adapter\\%s',$adapter);

				if(!class_exists($exportClass)){

					throw new \RuntimeException("Unknown configuration adapter \"$adapter\"");

				}

				return $exportClass;

			}

			public function export($format="ini"){

				$exportClass	=	self::__getAdapter($format);

				return $exportClass::export($this);

			}

			public function save($format="ini"){
			}

			public function __toString(){

				return $this->export("ini");

			}

			public function &__call($method,$values){

				$isGetter	=	strtolower(substr($method,0,3)) === 'get';

				if($isGetter){

					$attribute	=	strtolower(substr($method,3));

					if(array_key_exists($attribute,$this->values)){

						return $this->values[$attribute];

					}

					if(!method_exists($this,$method)){

						throw new \InvalidArgumentException("Undefined configuration attribute \"$attribute\"");

					}

					return NULL;

				}

				throw new \BadMethodCallException("Method \"$method\" does not exists in this class");

			}

		}

	}

