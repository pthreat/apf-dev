<?php 

	namespace apf\core{

		use \apf\iface\Log	as	LogInterface;

		abstract class Configurable{

			private 	$config			=	NULL;
			private	$isValidated	=	FALSE;

			final public function __construct(Config $config=NULL){

				$this->configure($config);

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

			final public function configure(Config $config){

				$childClass		=	strtolower(get_class($this));
				$configClass	=	sprintf('%s\\Config',$childClass);

				if(!($config instanceof $configClass)){

					$instanceClass	=	get_class($config);
					throw new \LogicException("Configuration object must be an instance of $configClass, instance of $instanceClass given");

				}	
			
				$this->config	=	$config;
				$this->validateConfig();

				return $this;

			}

			public static function interactiveConfig(Array $arguments){

				if(!array_key_exists('log',$arguments)){

					throw new \InvalidArgumentException('Must pass a log object');

				}

				if(!($arguments['log'] instanceof LogInterface)){

					throw new \InvalidArgumentException('Log argument must comply with interface \\apf\\iface\\Log');

				}

				if(array_key_exists('config',$arguments) && !($arguments['config'] instanceof Config)){

					throw new \InvalidArgumentException('The configuration argument must be an instance of \\apf\\core\\Config');

				}

				static::__interactiveConfig($arguments);

			}

			abstract protected static function __interactiveConfig(Array $arguments);

			public function isConfigured(){

				return !is_null($this->config);

			}

			abstract protected function __validateConfig();

			public function validateConfig(){

				return $this->isValidated	=	(boolean)$this->__validateConfig();

			}

			public function isValidated(){

				return $this->isValidated;

			}

			public function getConfig(){

				return $this->config;

			}

		}

	}
