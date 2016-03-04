<?php

	class ConfigAttribute{

		private	$name				=	NULL;
		private	$description	=	NULL;
		private	$value			=	NULL;
		private	$validate		=	TRUE;
		private	$onSetValue		=	NULL;
		private	$config			=	NULL;

		public function __construct(Config $config,$name,$description,$value=NULL){

			$this->setConfig($config);
			$this->setName($name);
			$this->setDescription($description);
			$this->setValue($value);

		}

		public function setConfig(Config $config){

			$this->config	=	$config;
			return $this;

		}

		public function getConfig(){

			return $this->config;

		}

		public function setValidate($validate){

			$this->validate	=	(boolean)$validate;
			return $this;

		}

		public function getValidate(){

			return $this->validate;

		}

		public static function factory($params){

			if($params instanceof ConfigAttribute){

				return $params;

			}

			if(!is_array($params)){

				throw new \InvalidArgumentException("Factory parameter must be an array or a configuration instance");

			}


			$config			=	array_key_exists('config',$params)			?	$params['config']			:	NULL;
			$name				=	array_key_exists('name',$params)				?	$params['name']			:	NULL;
			$description	=	array_key_exists('description',$params)	?	$params['description']	:	NULL;
			$value			=	array_key_exists('value',$params)			?	$params['value']			:	NULL;
			$validate		=	array_key_exists('validate',$params)		?	$params['validate']		:	NULL;

			return new static($config,$name,$description,$value,$validate);

		}

		public function setName($name){

			$this->name	=	$name;
			return $this;

		}

		public function getName(){

			return $this->name;

		}

		public function setDescription($description){

			$this->description	=	$description;
			return $this;

		}

		public function getDescription(){

			return $this->description;

		}

		public function onSetValue(Callable $callback){

			$this->onSetValue	=	$callback;
			return $this;

		}

		public function setValue($value){

			if($this->onSetValue !== NULL){

				$this->onSetValue($value);
				return $this;

			}

			if($this->validate){

				if(!$this->config->hasValidator($this->name)){

					throw new \LogicException("Attribute {$this->name} has to be validated, but no validator named $method has been found");
				}

				$method			=	$this->config->getValidator($this->name);
				$this->value	=	$this->config->$method($value);

				return $this;

			}

			$this->value	=	$value;

			return $this;

		}

		public function getValue(){

			return $this->value;

		}

	}

	abstract class Config{

		private	$attributes	=	NULL;

		public function __construct(){

			$this->configureAttributes();
			$this->attributes	=	new \ArrayObject();

		}

		private function makeValidatorName($name){

			return sprintf('validate%s',ucwords($name));

		}

		public function hasValidator($name){

			return method_exists($this->makeValidatorName($name,$this);

		}

		public function getValidator($name){

			if(!$this->hasValidator($name)){

				throw new \InvalidArgumentException("No validator found for attribute $name");		

			}

			return $this->makeValidatorName($name);

		}

		public function validateAttribute($name,$value){

			$validator	=	$this->getValidator($name);
			return $this->$validator($value);

		}

		protected function addAttribute($attribute){

			$this->attributes->append(ConfigAttribute::factory($attribute));

		}

		//Returns setters and getters according to the values returned by 
		//__getAttributes.

		public function getMethods(){
		}

		public function hasAttribute($attributeName){

			return array_key_exists($attributeName,$this->getAttributes());

		}

		public function getAttribute($attributeName){

			

			if(!$this->hasAttribute($attributeName)){

				throw new \InvalidArgumentException("Unknown configuration parameter $attributeName");

			}

			$attributes	=	$this->getAttributes();

			return $attributes[$attributeName];

		}
	
		public function __set($name,$value){

			$attribute	=	$this->getAttribute($name);

			if(array_key_exists$attribute['validate']){

				$method	=	"validate$name";

				if(!method_exists($this,$method)){

					throw new \Exception("No validator named \"$method\" exists for attribute $name");

				}

			}

		}

		public function __get($name){
				
		}

		public function __call($method,$args){

			$isSetterOrGetter	=	strtolower(substr($method,0,3));
			$isSetter			=	$isSetterOrGetter === 'set';
			$isGetter			=	$isSetterOrGetter === 'get';

		}

		abstract public function configureAttributes();

	}

	class PersonConfig extends Config{

		public function validateName($value){

			$value	=	trim($value);

			if(empty($value)){

				throw new \Exception("The person name can not be empty");

			}

			return $value;

		}

		public function configureAttributes(){

			parent::addAttribute(
										new ConfigAttribute(
																	'name',
																	'Person name'
										);
			);

		}

	}

	abstract class Configurable{

		public function __construct(Config $config){

			$this->setConfig($config);

		}

	}

	class Person extends Configurable{

		public function setConfig(PersonConfig $config){

			$this->config	=	$config;
			return $this;

		}

	}
