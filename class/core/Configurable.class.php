<?php 

	namespace apf\core{

		use \apf\iface\Log			as	LogInterface;
		use \apf\core\Config;
		use \apf\ui\form\Factory	as	FormFactory;

		abstract class Configurable{

			private	$config;

			final public function __construct(Config $config=NULL){


				$this->setConfig($config === NULL	?	self::getConfigurationInstance()	:	$config);

			}

			protected function getConfigurationInstance(){

				$childClass		=	strtolower(get_called_class());
				$configClass	=	sprintf('%s\\Config',$childClass);

				if(!class_exists($configClass)){

					throw new \InvalidArgumentException("Configuration class ->$childClass<- was not found");

				}

				$config	=	new $configClass($this);

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
			 *
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

			final public function setConfig(Config $config){

				/**
				 * Validate that the passed configuration instance responds to the proper class.
				 * In this way, we enforce namespace naming conventions.
				 */

				$this->config	=	self::validateConfigurationInstance($config);

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

				$object	=	$object === NULL ? new static()	:	$object;

				return FormFactory::createFromConfigurableObject($object,$ui);

			}

			public function __set($name,$value){

				return $this->config
				->getAttributes()
				->get($name)
				->setValue($value);

			}

			public function __get($name){

				return $this->config
				->getAttributes()
				->get($name)
				->getValue();

			}

			public function __call($method,$args){

				return call_user_func_array(Array($this->config,$method),$args);

			}

			public function __toString(){

				return sprintf('%s',$this->config);

			}

		}

	}
