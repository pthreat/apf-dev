<?php 

	namespace apf\core{

		use \apf\iface\Log			as	LogInterface;
		use \apf\core\Config;
		use \apf\ui\form\Factory	as	FormFactory;

		abstract class Configurable{

			/**
			 *@var $config Contains the configuration object for this configurable class.
			 */

			private	$config	=	NULL;

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

			/**
			 * Set the configuration for this configurable object.
			 * Given object must be of type \apf\core\Config.
			 * @TODO when the configuration object is able to import, replace this argument 
			 * in order to use the import feature from \apf\core\Config, so, for instance:
			 * $obj->setConfig('form.json'); OR $obj->setConfig($configObject);
			 */

			final public function setConfig(Config $config){

				/**
				 * Validate that the passed configuration instance responds to the proper class.
				 * In this way, we enforce namespace naming conventions.
				 */

				$this->config	=	self::validateConfigurationInstance($config);

				return $this;

			}

			/**
			 * Returns the configuration object from this configurable object.
			 */

			final public function &getConfig(){

				return $this->config;

			}

			/**
			 * This method will make up a Form element out of a configurable object's configuration attributes.
			 *	The configure method will choose an appropriate UI to show said form to the end user. 
			 *	The following is accomplished by consultation of the SAPI object (obtained from the Kernel).
			 *-------------------------------------------------------------------------------------------------
			 * Example:
			 *-------------------------------------------------------------------------------------------------
			 * If I'm invoking a configurable object's configure method on a linux terminal, a CLI form will be shown.
			 * If I'm invoking a configurable object's configure method on a web server, a web form will be shown.
			 * If I'm invoking a configurable object's configure method on a graphical enviroment a GTK form will be presented (far from this today)
			 *-------------------------------------------------------------------------------------------------
			 *
			 *	@return \apf\ui\Form returns a form, containing each attribute from this object's configuration
			 * for the user to be able to interactively configure this configurable object.
			 *
			 * NOTE: The developer must echo the returned value from this method, or call to the render method.
			 */

			public static function configure($ui=NULL,Configurable &$object=NULL){

				$object	=	$object === NULL ? new static()	:	$object;

				return FormFactory::createFromConfigurableObject($object,$ui);

			}

			/***********************************
			 * Magic methods 
			 ***********************************/

			/**
			 * Proxy every __set call to the corrrsponding attribute from this configurable object's configuration
			 * Example: $person->name = 'test'; 
			 */

			public function __set($name,$value){

				return $this->config
				->getAttributes()
				->get($name)
				->setValue($value);

			}

			/**
			 * Proxy every __set call to the corrrsponding attribute from this configurable object's configuration
			 * Example: echo $person->name;
			 */

			public function __get($name){

				return $this->config
				->getAttributes()
				->get($name)
				->getValue();

			}

			/**
			 *Proxy every undefined method through the configuration object from this configurable class.
			 *Example: $person->getName(); $person->setName('kate');
			 */

			public function __call($method,$args){

				return call_user_func_array(Array($this->config,$method),$args);

			}

			/**
			 * The equivalent of exporting the configuration from this configurable object
			 */ 

			public function __toString(){

				return sprintf('%s',$this->config);

			}

		}

	}
