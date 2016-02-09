<?php

	namespace apf\net\connection\config{

		use \apf\core\config\Validator	as	BaseValidator;

		abstract class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				$config->setName($config->getName());
				$config->setHost($config->getHost());
				$config->setPort($config->getPort());
				$config->setUsername($config->getUsername());
				$config->setPassword($config->getPassword());

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

