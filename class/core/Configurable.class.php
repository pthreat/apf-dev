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

			abstract public static function interactiveConfig(LogInterface $log,Config $defaults=NULL);

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
