<?php

	namespace apf\core{

		use \apf\core\config\attribute\Container	as	AttributeContainer;
		use \apf\core\config\Attribute;
		use \apf\core\config\Adapter;

		abstract class Config implements \ArrayAccess,\Iterator{

			private	$attributes	=	NULL;

			private	$isValidatedSoft		=	FALSE;
			private	$isValidatedHard		=	FALSE;
			private	$isValidatedExtra		=	FALSE;

			final public function __construct($parentObject,Config $config=NULL){

				$this->validateConfigurableObject($parentObject);

				$this->attributes	=	new AttributeContainer($this);

				$this->attributes->add(
															Array(
																	'name'			=>	'configurableObject',
																	'description'	=>	'The configurable object for which this configuration is meant.',
																	'value'			=>	$parentObject,
																	'exportable'	=>	FALSE,
																	'traversable'	=>	FALSE,
																	'readOnly'		=>	TRUE,
																	'validate'		=>	FALSE
															)
				);

				$this->configure();

				if($config !== NULL){

					$this->merge($config);

				}

			}

			public function getAttributes(){

				return $this->attributes;

			}

			/**
			 * Alias for getAttributes, depending on the context where this will be called 
			 * the syntactic sense for the code reader may add clarity.
			 */

			public function getAttributeContainer(){

				return $this->attributes;

			}

			abstract protected function configure();

			private function validateConfigurableObject($object){
					
				$parentObjectClass	=	strtolower(get_class($object));
				$configClass			=	strtolower(get_class($this));

				$configClass			=	substr($configClass,0,strrpos($configClass,'\\'));

				if($configClass !== $parentObjectClass){
			
					throw new \InvalidArgumentException("Invalid parent object, expected $configClass, got $parentObjectClass instead");
	
				}

				return $object;

			}

			public function validate($mode=NULL,$reValidate=FALSE){

				$mode		=	strtolower(trim($mode));

				$modes	=	Array(
										'soft',
										'hard',
										'extra',
										'none'
				);

				if(!in_array($mode,$modes)){

					throw new \InvalidArgumentException("Invalid validation mode specified: \"$validateMode\"");

				}

				if($validateMode=='none'){

					return;

				}

				$class	=	self::getValidatorClass();

				if(!class_exists($class)){

					throw new \LogicException("No validator class found for this configurable object");

				}

				switch($mode){

					case 'soft':

							if($this->isValidatedSoft){

								return TRUE;

							}

							return $this->isValidatedSoft		=	$class::softConfigValidation($this->getConfig());

					break;

					case 'hard':

						if($this->isValidatedHard && !$reValidate){

							return TRUE;

						}

						return $this->isValidatedHard		=	$class::hardConfigValidation($this->getConfig());
					break;

					case 'extra':

						if($this->isValidatedExtra && !$reValidate){

							return TRUE;

						}

						return $this->isValidatedExtra	=	$class::extraConfigValidation($this->getConfig());

					break;

					default:
						throw new \InvalidArgumentException("Unknown validation method");
					break;

				}

			}

			//The is validated method is a shortcut to check if the object has been validated in any way, soft or hard
			public function isValidated(){

				return $this->isValidatedSoft || $this->isValidatedHard || $this->isValidatedExtra;

			}

			//The isValidatedSoft method will tell you if the configurable object has been validated soft.
			public function isValidatedSoft(){

				return $this->isValidatedSoft;

			}

			//The isValidatedHard method will tell you if the configurable object has been validated the hard way.
			public function isValidatedHard(){

				return $this->isValidatedHard;

			}

			//The isValidatedHard method will tell you if the configurable object has passed extra validations
			public function isValidatedExtra(){

				return $this->isValidatedExtra;

			}

			public function getConfigurableObject(){

				return $this->attributes->get('configurableObject')->getValue();

			}

			private function makeValidatorName($name){

				return sprintf('validate%s',ucwords($name));

			}

			public function hasValidator($name){

				return method_exists($this->config,$this->makeValidatorName($name));

			}

			public function merge(Config $config){

			}

			/***********************************************************************
			 *Iterator interface [ PROXY ]
			 *----------------------------------------------------------------------
			 *All of the methods applied here are proxies to the attribute container
			 ***********************************************************************/

			public function current(){

				return $this->attributes->current();

			}

			public function key(){

				return $this->attributes->key();

			}

			public function next(){

				return $this->attributes->next();

			}

			public function rewind(){

				return $this->attributes->rewind();

			}

			public function valid(){

				return $this->attributes->valid();

			}

			/*******************************************************
			 *Array Access interface [PROXY]
			 *---------------------------------------
			 *Proxy all array like calls to the attribute container
			 *******************************************************/

			public function offsetExists($offset){

				return $this->attributes->offsetExists();

			}

			public function offsetGet($offset){

				return $this->attributes->offsetGet();

			}

			public function offsetSet($offset,$value){

				$this->attributes->offsetSet($offset,$value);

			}

			public function offsetUnset($offset){

				$this->attributes->offsetUnset($offset);

			}

			/************************************************************
			 *Magic methods [ PROXY ]
			 *------------------------------------
			 *Proxy calls to __set,__get and __call through the attribute container
			 ************************************************************/

			public function __set($name,$value){

				return $this->attributes->get($name)->setValue($name);

			}

			public function __get($name){

				return $this->attributes->get($name);

			}

			public function __call($method,$arguments){

				return call_user_func_array(Array($this->attributes,$method),$arguments);

			}

			/**
			 * This method returns a configuration adapter internally to be able to save, export, import
			 * a configuration object in different formats.
			 *
			 * A configuration adapter is basically a configuration format in which a Configuration object 
			 * can be exported, said formats can be: JSON, XML, INI. More formats will be supported in the future.
			 */

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

				return $exportClass::export($this->attributes);

			}

			public function import($file){

				$config		=	Adapter::factory($file);

				foreach($config->parse() as $key=>$value){

					$this->attributes()->get($key)->setValue($value);

				}

			}

			public function save($format="ini"){
			}

			public function __toString(){

				return $this->export("ini");

			}

		}

	}
