<?php

	namespace apf\core\kernel\config{

		use \apf\core\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				//Validate project name

				if(!$config->getProject()){

					throw new \LogicException("Current kernel has no assigned project");

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

