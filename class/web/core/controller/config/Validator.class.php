<?php

	namespace apf\web\core\controller\config{

		use \apf\core\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				$config->setName($config->getName());

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

