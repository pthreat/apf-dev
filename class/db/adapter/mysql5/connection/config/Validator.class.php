<?php

	namespace apf\db\adapter\mysql5\connection\config{

		use \apf\net\connection\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				parent::__softConfigValidation($config);
				$config->setCharset($config->getCharset());

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

