<?php

	namespace apf\core\project\config{

		use \apf\core\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				//Validate project name

				if(!$config->getName()){

					throw new \LogicException("The controller name is invalid");

				}

				//Validate project directory

				if(!$config->getSub()){

					throw new \LogicException("No sub (module) has been specified for this controller");

				}

				return TRUE;

			}

			protected static function __hardConfigValidation($config){

				return TRUE;

			}

			protected static function __extraConfigValidation($config){

				return TRUE;

			}

		}

	}

