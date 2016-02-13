<?php

	namespace apf\core\project\documentroot\config{

		use \apf\core\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				$config->setDirectory($config->getDirectory());

				return TRUE;

			}

			protected static function __hardConfigValidation($config){

				$directory	=	$config->getDirectory();

				if(!$directory->exists()){

					throw new \LogicException("Document root directory \"$directory\" directory does not exists");

				}

				if(!$directory->isDir()){

					throw new \LogicException("The document root directory \"$directory\" is a file, not a directory");

				}

				//if is cli and not writable
				if(!$directory->isWritable()){

					throw new \LogicException("The document root directory \"$directory\" is not writable");

				}

				return TRUE;

			}

			protected static function __extraConfigValidation($config){

				return TRUE;

			}

		}

	}

