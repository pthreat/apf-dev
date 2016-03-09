<?php 

	namespace apf\core{

		use \apf\iface\Log			as	LogInterface;
		use \apf\core\Config;
		use \apf\ui\form\Factory	as	FormFactory;

		abstract class Configurable{

			private	$config;

			private	$isValidatedSoft	=	FALSE;
			private	$isValidatedHard	=	FALSE;
			private	$isValidatedExtra	=	FALSE;

			final public function __construct(Config $config,$validateMode='hard',$reValidate=FALSE){

				$this->setConfig($config,$validateMode,$reValidate);

			}

			public static function factory(){

				$config	=	self::getConfigurationInstance();
				$class	=	get_called_class();

				return new $class($config,$validate='none');

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

			protected static function validateConfigurationInstance(&$config){

				$childClass			=	strtolower(get_called_class());
				$configClass		=	sprintf('%s\\Config',$childClass);
				$altConfigClass	=	sprintf('%sConfig',ucwords($childClass));

				if(!($config instanceof $configClass) && !($config instanceof $altConfigClass)){

					$instanceClass	=	get_class($config);
					throw new \LogicException("Configuration object must be an instance of $configClass, instance of $instanceClass given");

				}

				return $config;

			}

			private static function getValidatorClass(){

				return sprintf('%s\\config\\Validator',strtolower(get_called_class()));

			}

			public function isConfigured(){

				return !is_null($this->config);

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

			final public function setConfig(Config $config,$validateMode='hard',$reValidate=FALSE){

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

			final public function &getConfig(){

				return $this->config;

			}

			/**
			 *	The configure method, chooses an appropriate UI to show to the end user, according to the context
			 *	defined by the SAPI object obtained from the Kernel and presents said configuration interface to the user
			 *	for him/her to be able to configure this configurable object.
			 *
			 *	@return Configurable a user configured object.
			 *
			 */

			public static function configure($ui=NULL,Configurable &$object=NULL){

				if($object===NULL){

					$object	=	self::factory();

				}

				return FormFactory::createFromConfigurableObject($object,$ui);

			}

			public function __set($name,$value){

				$this->config->getAttribute($name)->setValue($value);

			}

			public function __get($name){

				return $this->config->getAttribute($name)->getValue();

			}

			public function __call($method,$args){

				return call_user_func_array(Array($this->config,$method),$args);

			}

			public function __toString(){

				return sprintf('%s',$this->config);

			}

		}

	}
