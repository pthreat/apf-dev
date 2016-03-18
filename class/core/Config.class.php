<?php

	namespace apf\core{

		use \apf\core\config\attribute\Container	as	AttributeContainer;
		use \apf\core\config\Attribute;
		use \apf\core\config\Adapter;

		abstract class Config implements \ArrayAccess,\Iterator,\Countable{

			/**
			 * @var \apf\core\config\attribute\Container $attributes initialized when the object is constructed.
			 * this object holds all attributes belonging to this configuration.
			 */

			private	$attributes				=	NULL;

			/**
			 * Set of flags to know in which state this configuration object is validated in.
			 */

			/**
			 * @var boolean $isValidatedSoft flag to know if this configuration is "softly" validated
			 * @see self::isValidatedSoft()
			 */

			private	$isValidatedSoft		=	FALSE;

			/**
			 * @var boolean $isValidatedHard flag to know if this configuration is "hard" validated
			 * @see self::isValidatedHard()
			 */

			private	$isValidatedHard		=	FALSE;

			/**
			 * @var boolean $isValidatedExtra flag to know if this configuration is "extra" validated
			 * @see self::isValidatedExtra()
			 */

			private	$isValidatedExtra		=	FALSE;

			/**
			 * Configuration constructor, the first argument is meant to be an object extending to the Configurable class 
			 * we will call this class the parent object. 
			 * The parent object must respect a namespace naming scheme. This scheme is best explained by an example:
			 *
			 * Suppose we have a configuration class in namespace \myProject\person\Config
			 * The parent class must be in namespace \myProject\Person for it to be considered valid.
			 *  
			 * The second argument must be of type \apf\core\Config and it's optional, meant for initializing a configuration class
			 * with values.
			 *
			 * The constructor is final to provide consistency, i.e no "hacks" (rewrites by the user extending to this class)
			 * 
			 * @param \apf\core\Configurable $parentObject	a Configurable object respecting the namespace naming scheme mentioned before
			 * @param \apf\core\Config			$config			a Config object (optional) for initializing this class with values.
			 */

			final public function __construct($parentObject,Config $config=NULL){

				$this->validateConfigurableObject($parentObject);

				/**
				 * Initialize attribute container
				 */

				$this->attributes	=	new AttributeContainer($this);

				/**
				 * Add the parent configurable object like just another attribute
				 * Specify that said attribute will not be traversable (i.e visible when we foreach this configuration)
				 * Will be readOnly (i.e it's value wont be changed)
				 * Will not be exportable (i.e when we export/save this configuration it will not be exported/saved)
				 * Requires no validation
				 */

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

				/**
				 * Configure the attributes which this configuration class will be composed of
				 */

				$this->configure();

				/**
				 * If a configuration object is passed, merge said configuration object (i.e assign the passed attribute configuration values
				 * to the attributes composing this class
				 */ 

				if($config !== NULL){

					$this->merge($config);

				}

			}

			/**
			 * Return attributes belonging to this configuration 
			 * @return \apf\core\config\attribute\Container	The attribute container.
			 */

			public function getAttributes(){

				return $this->attributes;

			}

			/**
			 * Alias for getAttributes, depending on the context where this will be called 
			 * the syntactic sense for the code reader may add clarity.
			 * @return \apf\core\config\attribute\Container	The attribute container.
			 */

			public function getAttributeContainer(){

				return $this->attributes;

			}

			/**
			 * Configure child class, check if the child class has added any attributes for it to be considered valid.
			 * @throw \LogicException In case the child class hasn't added any attributes to itself.
			 * @return int Amount of attributes configured for this class.
			 */

			final public function configure(){

				/**
				 * Configure/Add attributes to this configuration
				 */

				$this->__configure();	

				/**
				 * If the child class has defined no attributes, throw an exception. A configuration class must have at least one attribute
				 */ 

				$amountOfAttributes	=	$this->attributes->count();

				//Compare for == 1 because we already add at least ONE attribute (the parent object) in the constructor to the container.

				if($amountOfAttributes == 1){

					$msg = sprintf('Class ->%s<- has not defined any attributes',get_called_class());

					throw new \LogicException($msg);

				}

				/**
				 * If everything is correct, return the amount of attributes configured
				 */

				return $amountOfAttributes;

			}

			/**
			 * The child class extending to this class must define a __configure method.
			 * This method will be in charge of configuring attributes for said child class.
			 */

			abstract protected function __configure();

			/**
			 * Validate that the configurable object passed belongs to the same namespace.
			 * Meant to enforce namespace naming conventions.
			 *
			 * @throw \InvalidArgumentException In case the given configurable object does not belongs to the same namespace
			 * this configuration belongs to.
			 */

			private function validateConfigurableObject($object){
					
				$parentObjectClass	=	strtolower(get_class($object));
				$configClass			=	strtolower(get_class($this));

				$configClass			=	substr($configClass,0,strrpos($configClass,'\\'));

				if($configClass !== $parentObjectClass){
			
					throw new \InvalidArgumentException("Invalid parent object, expected $configClass, got $parentObjectClass instead");
	
				}

				return $object;

			}

			/**
			 * Validate this configuration object entirely in different modes.
			 * One thing, is to validate each element value belonging to this configuration.
			 * A different approach is to validate a configuration ENTIRELY after all attributes have been set.
			 *
			 * Example:
			 *----------------------------------------------------------------------------------------------------------------
			 * For instance, suppose we have a person form with a name attribute.
			 * Validating the name of said person is just one part of the entire process. (validate attribute)
			 * Checking if the person has been saved correctly is the final step of the whole process (validate configuration).
			 */

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

			/**
			 * This method returns a configuration adapter internally to be able to save, export, import
			 * a configuration object in different formats.
			 *
			 *
			 * A configuration adapter is basically a configuration format in which a Configuration object 
			 * can be exported, said formats can be: JSON, XML, INI. More formats will be supported in the future.
			 *
			 * NOTE: The following method will be replaced by "setSaveAdapter" or similar.
			 * 
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

			/**
			 * Import a configuration from a configuration file
			 */

			public function import($file){

				$config		=	Adapter::factory($file);

				foreach($config->parse() as $key=>$value){

					$this->attributes->get($key)->setValue($value);

				}

			}

			public function save($format="ini"){
			}

			/**
			 * Gets the parent object for this configuration class
			 */

			public function getConfigurableObject(){

				return $this->attributes->get('configurableObject')->getValue();

			}

			/**
			 * Each attribute has a validation method.
			 * This method returns a string adding validate in front.
			 * The whole point/idea in a not-so-distant future is to be able to change the validate word for whatever 
			 * the user wants.
			 */

			private function makeValidatorName($name){

				return sprintf('validate%s',ucwords($name));

			}

			/**
			 *Checks if the child class has a validator named $name
			 *
			 *@param  string $name Validator name, example $name='test'
			 *@return boolean TRUE Validator exists.
			 *@return boolean FALSE Validator does not exists.
			 */

			public function hasValidator($name){

				return method_exists($this->config,$this->makeValidatorName($name));

			}

			/**
			 * Merge configuration parameters 
			 */

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
			 *Countable interface [PROXY]
			 *---------------------------------------
			 *Proxy count on Configuration object through attribute container
			 *******************************************************/
			public function count(){

				return $this->attributes->count();

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

			public function __toString(){

				return $this->export("ini");

			}

		}

	}
