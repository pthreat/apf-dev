<?php 

	namespace apf\core{

		use \apf\iface\Log	as	LogInterface;
		use \apf\core\Config;

		abstract class Configurable{

			private 	$config				=	NULL;

			private	$isValidatedSoft	=	FALSE;
			private	$isValidatedHard	=	FALSE;
			private	$isValidatedExtra	=	FALSE;

			final public function __construct(Config $config=NULL,$validateMode='hard'){

				$this->configure($config,$validateMode);

			}

			protected static function getConfigurationInstance(){

				$childClass		=	strtolower(get_called_class());
				$configClass	=	sprintf('%s\\Config',$childClass);

				$config			=	new $configClass();

				if(!($config instanceof $configClass)){

					$instanceClass	=	get_class($config);
					throw new \LogicException("Configuration object must be an instance of $configClass, instance of $instanceClass given");

				}

				return $config;

			}

			/**
			*
			*Validates that the passed configuration instance responds to the proper configuration class
			*
			*@example The \apf\core\Project class extends to the Configurable class. This will validate
			*that the passed instance is of class \apf\core\project\Config.
			*This enforces proper namespace naming for native framework classes and also acts as a sort of "type hint".
			*/

			protected static function validateConfigurationInstance($config){

				$childClass		=	strtolower(get_called_class());
				$configClass	=	sprintf('%s\\Config',$childClass);

				if(!($config instanceof $configClass)){

					$instanceClass	=	get_class($config);
					throw new \LogicException("Configuration object must be an instance of $configClass, instance of $instanceClass given");

				}

				return $config;

			}

			final public function configure(Config $config,$validateMode='hard'){

				//Validate that the passed configuration instance responds to the proper class
				$this->config	=	self::validateConfigurationInstance($config);

				//Validate the configuration instance with a mode (soft or hard).
				if(!$this->validateConfig($validateMode)){

					$configClass	=	get_called_class();
					throw new \InvalidArgumentException("The \"$configClass\" configuration is not valid.");

				}

				return $this;

			}

			public static function interactiveConfig(Config $config=NULL,LogInterface $log=NULL){

				static::__interactiveConfig($config,$log);

			}

			abstract protected static function __interactiveConfig($config,$log);

			public function isConfigured(){

				return !is_null($this->config);

			}

			//There are three types of validations
			//Soft: This type of validation is related to properties/attributes being set on the Configurable object.
			//For instance, in the case of a directory, you could soft validate that the directory has been set in the Configurable object.

			//Hard: The hard type of validation is also related to properties and attributes but it also validates
			//that these properties and attributes set on the configurable object are also valid per se.
			//For instance, in the case of a directory you could hard validate that the directory indeed exists.

			//Extra: The extra type of validation is pretty much like the hard validation but it is more strict.
			//For instance, in the case of a directory you could hard validate other directory attributes, such as if the directory is writable

			abstract protected static function __softConfigValidation($config);
			abstract protected static function __hardConfigValidation($config);
			abstract protected static function __extraConfigValidation($config);

			/**
			*The soft type validation is related to properties/attributes being set on the Configurable object.
			*For instance, in the case of a directory, you could soft validate that the directory has been set in the Configurable object.
			*/

			public static function softConfigValidation(Config $config){

				self::validateConfigurationInstance($config);
				return static::__softConfigValidation();

			}

			/**
			*The hard type validation is also related to properties and attributes but it also validates
			*that these properties and attributes set on the configurable object are also valid per se.
			*For instance, in the case of a directory you could hard validate that the directory indeed exists.
			*/

			public static function hardConfigValidation(Config $config){

				return static::__softConfigValidation($config) && static::__hardConfigValidation($config);

			}

			/**
			* The extra type of validation is pretty much like the hard validation but it is more strict.
			* For instance, in the case of a directory you could hard validate other directory attributes, such as if the directory is writable.
			*
			* While not needed in most cases, this method is provided for this and other possible scenarios.
			*/

			public static function extraConfigValidation(Config $config){

				return	static::__softConfigValidation($config) && 
							static::__hardConfigValidation($config) &&
							static::__extraConfigValidation($config);

			}

			public function validateConfig($mode=NULL){

				$mode	=	strtolower(trim($mode));

				switch($mode){

					case 'soft':
						return $this->isValidatedSoft		=	self::softConfigValidation($this->getConfig());
					break;

					case 'hard':
						return $this->isValidatedHard		=	self::hardConfigValidation($this->getConfig());
					break;

					case 'extra':
						return $this->isValidatedExtra	=	self::extraConfigValidation($this->getConfig());
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

			public function getConfig(){

				return $this->config;

			}

		}

	}
