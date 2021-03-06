<?php

	namespace apf\core\project\config{

		use \apf\core\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				//Validate project name

				if(!$config->getName()){

					throw new \LogicException("The project name is invalid");

				}

				//Validate project directory

				if(!$config->getDirectories()){

					throw new \LogicException("Project directories have not been set");

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

