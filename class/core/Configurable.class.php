<?php 

	namespace apf\core{

		use \apf\iface\Log	as	LogInterface;
		use \apf\core\Config;

		abstract class Configurable{

			private 	$config				=	NULL;

			private	$isValidatedSoft	=	FALSE;
			private	$isValidatedHard	=	FALSE;
			private	$isValidatedExtra	=	FALSE;

			final public function __construct(Config $config,$validateMode='hard',$reValidate=FALSE){

				$this->configure($config,$validateMode,$reValidate);

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

			final public function configure(Config $config,$validateMode='hard',$reValidate=FALSE){

				//Validate that the passed configuration instance responds to the proper class
				$this->config	=	self::validateConfigurationInstance($config);

				//Validate the configuration instance with a mode (soft or hard).
				if(!$this->validateConfig($validateMode,$reValidate)){

					$configClass	=	get_called_class();
					throw new \InvalidArgumentException("The \"$configClass\" configuration is not valid.");

				}

				return $this;

			}

			public static function interactiveConfig(Config $config=NULL,LogInterface $log=NULL){

				if(!is_null($config)){

					self::validateConfigurationInstance($config);

				}

				return static::__interactiveConfig($config,$log);

			}

			abstract protected static function __interactiveConfig($config,$log);

			public function isConfigured(){

				return !is_null($this->config);

			}

			private static function getValidatorClass(){

				return sprintf('%s\\config\\Validator',strtolower(get_called_class()));

			}

			public function validateConfig($mode=NULL,$reValidate=FALSE){

				$mode		=	strtolower(trim($mode));
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

						if($this->isValidatedHard){

							return TRUE;

						}

						return $this->isValidatedHard		=	$class::hardConfigValidation($this->getConfig());
					break;

					case 'extra':
						if($this->isValidatedExtra){

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

			public function getConfig(){

				return $this->config;

			}

		}

	}
