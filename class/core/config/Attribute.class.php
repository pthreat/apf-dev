<?php

	namespace apf\core\config{

		use \apf\core\Config;

		class Attribute implements \ArrayAccess{

			private	$container		=	Array(
														'name'			=>	NULL,
														'description'	=>	NULL,
														'value'			=>	NULL,
														'validate'		=>	TRUE,
														'onSetValue'	=>	NULL,
														'config'			=>	NULL,
														'exportable'	=>	TRUE,
														'traversable'	=>	TRUE,
														'readOnly'		=>	FALSE
			);

			public function __construct(Array $parameters){

				if(!is_array($parameters)){

					throw new \InvalidArgumentException("Factory parameter must be an array or an attribute");

				}

				$config			=	array_key_exists('config',$parameters)			?	$parameters['config']		:	NULL;
				$name				=	array_key_exists('name',$parameters)			?	$parameters['name']			:	NULL;
				$description	=	array_key_exists('description',$parameters)	?	$parameters['description']	:	NULL;
				$value			=	array_key_exists('value',$parameters)			?	$parameters['value']			:	NULL;
				$onSetValue		=	array_key_exists('onSetValue',$parameters)	?	$parameters['onSetValue']	:	NULL;
				$validate		=	array_key_exists('validate',$parameters)		?	$parameters['validate']		:	TRUE;
				$exportable		=	array_key_exists('exportable',$parameters)	?	$parameters['exportable']	:	TRUE;
				$traversable	=	array_key_exists('traversable',$parameters)	?	$parameters['traversable']	:	TRUE;
				$readOnly		=	array_key_exists('readOnly',$parameters)		?	$parameters['readOnly']		:	TRUE;

				$this->setConfig($config);
				$this->setName($name);
				$this->setDescription($description);
				$this->setValidate($validate);

				if($value!==NULL){

					$this->setValue($value);	

				}

				if($onSetValue !== NULL){

					$this->onSetValue($onSetValue);	

				}


				$this->setExportable($exportable);
				$this->setTraversable($traversable);
				$this->setReadOnly($readOnly);

			}

			public function setTraversable($boolean){

				$this->container['traversable']	=	(boolean)$boolean;
				return $this;

			}

			public function isTraversable(){

				return $this->container['traversable'];

			}

			public function setReadOnly($boolean){

				$this->container['readOnly']	=	(boolean)$boolean;
				return $this;

			}

			public function isReadOnly(){

				return $this->container['readOnly'];

			}

			public static function factory($parameters){

				if($parameters instanceof Attribute){

					return $parameters;

				}

				if(!is_array($parameters)){

					throw new \InvalidArgumentException('Factory parameter must be an array or an attribute');

				}

				return new static($parameters);

			}

			public function setExportable($boolean){

				$this->container['exportable']	=	(boolean)$boolean;
				return $this;

			}

			public function isExportable(){

				return (boolean)$this->container['exportable'];

			}

			public function getExportable(){

				return $this->container['exportable'];

			}

			public function setConfig(Config $config){

				$this->container['config']	=	$config;
				return $this;

			}

			public function getConfig(){

				return $this->container['config'];

			}

			public function setValidate($validate){

				$this->container['validate']	=	(boolean)$validate;
				return $this;

			}

			public function needsValidation(){

				return $this->container['validate'];

			}

			public function getValidate(){

				return $this->container['validate'];

			}

			public function setName($name){

				$this->container['name']	=	$name;
				return $this;

			}

			public function getName(){

				return $this->container['name'];

			}

			public function setDescription($description){

				$this->container['description']	=	$description;
				return $this;

			}

			public function getDescription(){

				return $this->container['description'];

			}

			public function isMultiple(){

				$value	=	$this->container['value'];
				return is_array($value) || (is_object($value) && ($value instanceof \Iterator));

			}

			public function onSetValue(Callable $callback){

				$this->container['onSetValue']	=	$callback;
				return $this;

			}

			public function setValue($value){

				if($this->isReadOnly()){

					throw new \LogicException('This attribute is read only');

				}

				if($this->onSetValue !== NULL){

					$this->onSetValue($value);
					return $this;

				}

				if($this->container['validate']){

					$attrName	=	$this->container['name'];
					$method		=	$this->config->getValidator($attrName);

					if(!$this->config->hasValidator($attrName)){

						throw new \LogicException("Attribute $name has to be validated, but no validator named $method has been found");
					}

					$this->container['value']	=	$this->config->$method($value);

					return $this;

				}

				$this->container['value']	=	$value;

				return $this;

			}

			public function getValue(){

				return $this->container['value'];

			}

			/**
			 * Array Access interface methods
			 */

			private function validateOffset($offset){

				if(!array_key_exists($offset,$this->container)){

					throw new \InvalidArgumentException("Unknown attribute property \"$offset\"");

				}

				return $offset;

			}

			public function offsetExists($offset){

				$this->validateOffset($offset);				

			}

			public function offsetGet($offset){

				return $this->container[$this->validateOffset($offset)];

			}

			public function offsetSet($offset,$value){

				$this->container[$this->validateOffset($offset)]	=	$value;

			}

			public function offsetUnset($offset){

				$this->container[$this->validateOffset($offset)]	=	NULL;

			}

			/**
			 * Magic methods
			 */

			public function __set($offset,$value){

				$this->validateOffset($offset);

				$method	=	sprintf('set%s',ucwords($offset));

				$this->$method($value);

			}

			public function __get($offset){

				return $this->container[$this->validateOffset($offset)];

			}

		}

	}

