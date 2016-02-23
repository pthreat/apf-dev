<?php 

	namespace apf\core{

		use \apf\iface\Log			as	LogInterface;
		use \apf\iface\config\Cli	as	CliConfigInterface;
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
			 * The factory method takes as the first parameter any of the following arguments:
			 * An Array
			 * A File instance
			 * A string: In the case of a string, this will assume that the given string is the path to a configuration file.
			 *
			 * The purpose of this method is to return a properly configured configurable object. No redundancy or pun intended.
			 *
			 *	@param	Array							An array containing corresponding configuration entries for this configurable object
			 *	@param	String						A path to a file containing configuration entries for this configurable object
			 *	@param	\apf\core\File				A File object that points to a file.
			 *
			 * @return	\apf\core\Configurable	the pertinent configurable object, i.e an instance of the child class that extends this class.
			 */

			public static function factory($config){

				$configObject	=	self::getConfigurationInstance();
				$configObject->import($config);
				
				$returnClass	=	get_called_class();

				return new $returnClass($configObject);

			}

			/**
			*
			*Validates that the passed configuration instance responds to the proper configuration class

			*@example The \apf\core\Project class extends to the Configurable class. This will validate
			*that the passed instance is of class \apf\core\project\Config.
			*This enforces proper namespace naming for native framework classes and also acts as a sort of "type hint".
			*/

			protected static function validateConfigurationInstance(&$config){

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

				$validationModes	=	Array(
													'soft',
													'hard',
													'extra',
													'none'
				);

				if(!in_array($validateMode,$validationModes)){

					throw new \InvalidArgumentException("Invalid validation mode specified: \"$validateMode\"");

				}

				if($validateMode=='none'){

					return;

				}

				//Validate the configuration instance with a mode (soft,hard or extra).
				if(!$this->validateConfig($validateMode,$reValidate)){

					$configClass	=	get_called_class();
					throw new \InvalidArgumentException("The \"$configClass\" configuration is not valid.");

				}

				return $this;

			}

			public static function cliConfig(Config &$config=NULL,LogInterface $log=NULL){

				if(!is_null($config)){

					self::validateConfigurationInstance($config);

				}

				$calledClass		=	get_called_class();
				$childClass			=	strtolower($calledClass);
				$cliConfigClass	=	sprintf('%s\\config\\Cli',$childClass);

				if(!class_exists($cliConfigClass)){

					$msg	=	"$childClass class has no CLI configuration class \"$cliConfigClass\"";
					sprintf("%s. If you really meant to interactively configure this class, please create \"$cliConfigClass\"",$msg);
					throw new \BadMethodCallException($msg);

				}

				if(!in_array('apf\iface\config\Cli',class_implements($cliConfigClass))){

					throw new \LogicException("$cliConfigClass must implement \\apf\\iface\\config\\Cli interface");

				}

				$return	=	$cliConfigClass::configure($config,$log);

				if($return === FALSE){

					return FALSE;

				}

				if(!is_a($return,$calledClass)){

					throw new \InvalidArgumentException("Returned value by cli configuration must be an instance of $calledClass or a boolean false value in case of user abort.");

				}

				return $return;

			}

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

			public function &getConfig(){

				return $this->config;

			}

		}

	}
