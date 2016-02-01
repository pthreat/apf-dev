<?php

	namespace apf\core\project\module\sub\config{

		use \apf\core\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				//Validate module name

				if(!$config->getName()){

					throw new \LogicException("Sub name is invalid");

				}

				if(!$config->getModule()){

					throw new \LogicException("Given sub has no module assigned");

				}

				//Validate module directory

				if(!$config->getDirectory()){

					throw new \LogicException("Sub directory has not been set");

				}


				//Validate common fragments directory

				if(!$config->getFragmentsDirectory()){

					throw new \LogicException("Sub fragments directory has not been set");

				}

				//Validate templates directory

				if(!$config->getTemplatesDirectory()){

					throw new \LogicException("Sub templates directory has not been set");

				}

				return TRUE;

			}

			protected static function __hardConfigValidation($config){

				$directories	=	Array(
												'root'		=>	$config->getDirectory(),
												'fragments'	=>	$config->getFragmentsDirectory(),
												'templates'	=>	$config->getTemplatesDirectory()
				);

				foreach($directories as $description=>$directory){

					if(!$directory->exists()){

						throw new \LogicException("The $description $directory directory does not exists");

					}

					if(!$directory->isDir()){

						throw new \LogicException("The $description $directory is a file, not a directory");

					}

					//if is cli and not writable
					if(!$directory->isWritable()){

						throw new \LogicException("The $description $directory directory is not writable");

					}

				}

				return TRUE;

			}

			protected static function __extraConfigValidation($config){

				return TRUE;

			}

		}

	}

