<?php

	namespace apf\net\adapter\config{

		use \apf\core\config\Validator	as	BaseValidator;
		use \apf\net\Host;
		use \apf\net\Port;

		abstract class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				$config->setConnection($config->getConnection());

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

