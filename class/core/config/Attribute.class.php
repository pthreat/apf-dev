<?php

	namespace apf\core\config{

		use \apf\core\Config;

		class Attribute{

			private	$name				=	NULL;
			private	$description	=	NULL;
			private	$value			=	NULL;
			private	$validate		=	TRUE;
			private	$onSetValue		=	NULL;
			private	$config			=	NULL;

			public function __construct(Config $config,$name,$description,$validate=TRUE,$value=NULL){

				$this->setConfig($config);
				$this->setName($name);
				$this->setDescription($description);

				if($value !== NULL){

					$this->setValue($value);

				}

				$this->setValidate($validate);

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

				if($params instanceof Attribute){

					return $params;

				}

				if(!is_array($params)){

					throw new \InvalidArgumentException("Factory parameter must be an array or an attribute");

				}


				$config			=	array_key_exists('config',$params)			?	$params['config']			:	NULL;
				$name				=	array_key_exists('name',$params)				?	$params['name']			:	NULL;
				$description	=	array_key_exists('description',$params)	?	$params['description']	:	NULL;
				$value			=	array_key_exists('value',$params)			?	$params['value']			:	NULL;
				$validate		=	array_key_exists('validate',$params)		?	$params['validate']		:	TRUE;

				return new static($config,$name,$description,$validate,$value);

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

			public function isMultiple(){

				return is_array($this->value);

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

					$method			=	$this->config->getValidator($this->name);

					if(!$this->config->hasValidator($this->name)){

						throw new \LogicException("Attribute {$this->name} has to be validated, but no validator named $method has been found");
					}

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

	}

