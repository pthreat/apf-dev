<?php

	namespace apf\core\config{

		use \apf\core\Config;
		use \apf\core\config\attribute\Container	as	AttributeContainer;

		class Attribute implements \ArrayAccess{

			private	$container		=	Array(
														'name'			=>	NULL,
														'description'	=>	NULL,
														'multiple'		=>	FALSE,
														'value'			=>	NULL,
														'validate'		=>	TRUE,
														'config'			=>	NULL,
														'exportable'	=>	TRUE,
														'traversable'	=>	TRUE,
														'itemName'		=>	NULL,
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
				$validate		=	array_key_exists('validate',$parameters)		?	$parameters['validate']		:	TRUE;
				$exportable		=	array_key_exists('exportable',$parameters)	?	$parameters['exportable']	:	TRUE;
				$traversable	=	array_key_exists('traversable',$parameters)	?	$parameters['traversable']	:	TRUE;
				$readOnly		=	array_key_exists('readOnly',$parameters)		?	$parameters['readOnly']		:	FALSE;
				$multiple		=	array_key_exists('multiple',$parameters)		?	$parameters['multiple']		:	FALSE;

				$this->setConfig($config);
				$this->setName($name);
				$this->setDescription($description);
				$this->setValidate($validate);
				$this->setExportable($exportable);
				$this->setTraversable($traversable);

				if($multiple){

					$this->container['itemName']	=	$parameters['item'];
					$this->container['value']		=	new AttributeContainer($config);

				}

				if($value!==NULL){

					$this->setValue($value);	

				}

				$this->setReadOnly($readOnly);

			}

			public function getItemName(){

				return $this->container['itemName'];

			}

			public function addValue($value){

				if(!$this->isMultiple()){

					throw new \InvalidArgumentException("Attribute ->{$this->name}<- is not a multiple attribute, if you really meant to add, please define it as multiple");

				}


				$value				=	is_array($value)	?	$value	:	Array('value'=>$value);
				$value['name']		=	sprintf('%s_%d',$this->container['name'],$this->container['value']->count());
				$value['config']	=	$this->container['config'];

				$this->container['value']->add($value);

				return $this;

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

				return $this->container['value'] instanceof AttributeContainer;

			}

			public function setValue($value){

				if($this->isReadOnly()){

					throw new \LogicException('This attribute is read only');

				}

				$this->container['value']	=	$this->container['validate']	?	$this->container['validate']	:	$value;

				return $this;

			}

			public function getValue(){

				return $this->container['value'];

			}

			public function validate($value){

				$attributeName	=	$this->container['name'];

				/**
				 *@TODO
				 *If property validator is found in a certain attribute, use the callback
				 *defined in the property rather than finding the validator in the configuration 
				 *object
				 */

				if(!$this->config->hasValidator($this->container['name'])){

					$configClass	=	get_class($this->config);

					throw new \InvalidArgumentException("Class: ->$configClass<- has NO validator for attribute ->$attributeName<-");

				}

				$validateMethod	=	sprintf('validate%s',ucwords($attributeName));

				return $this->config->$validateMethod($value);

			}

			private function validateOffset($offset){

				if(!array_key_exists($offset,$this->container)){

					throw new \InvalidArgumentException("Unknown attribute property \"$offset\"");

				}

				return $offset;

			}

			/**
			 * Array Access interface methods
			 */

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

