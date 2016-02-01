<?php

	namespace apf\core\config{

		use \apf\core\Config;

		abstract class Validator{

			//There are three types of validations for a configurable object.
			//---------------------------------------------------------------

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
				return static::__softConfigValidation($config);

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

			protected static function validateConfigurationInstance($config){

				$childClass		=	strtolower(get_called_class());
				$configClass	=	sprintf('%s\\Config',$childClass);
				$configClass	=	substr($configClass,0,strrpos($configClass,'validator')-1);

				if(!($config instanceof $configClass)){

					$instanceClass	=	get_class($config);
					throw new \LogicException("Configuration object must be an instance of $configClass, instance of $instanceClass given");

				}

				return $config;

			}

		}

	}
