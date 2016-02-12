<?php

	namespace apf\db\adapter\config{

		use \apf\net\adapter\config\Validator	as	BaseValidator;
		use \apf\net\Host;
		use \apf\net\Port;

		abstract class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				return parent::__softConfigValidation($config);

			}

			protected static function __hardConfigValidation($config){

				return TRUE;

			}

			protected static function __extraConfigValidation($config){

				return TRUE;

			}

		}

	}

