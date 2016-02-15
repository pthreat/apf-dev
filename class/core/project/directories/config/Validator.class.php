<?php

	namespace apf\core\project\config{

		use \apf\core\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				if(!$config->getProject()){

					throw new \LogicException("Project has not been set for project directories configuration object.");

				}

				//Validate project directory

				if(!$config->getDirectory()){

					throw new \LogicException("The root directory has not been set");

				}

				//Validate modules directory

				if(!$config->getModulesDirectory()){

					throw new \LogicException("The modules directory has not been set");

				}


				//Validate templates directory

				if(!$config->getTemplatesDirectory()){

					throw new \LogicException("The common templates directory has not been set");

				}

				//Validate common fragments directory

				if(!$config->getFragmentsDirectory()){

					throw new \LogicException("The common fragments directory has not been set");

				}

				return TRUE;

			}

			protected static function __hardConfigValidation($config){

				$directories	=	Array(
												'root'		=>	$config->getDirectory(),
												'modules'	=>	$config->getModulesDirectory(),
												'templates'	=>	$config->getTemplatesDirectory()
												'fragments'	=>	$config->getFragmentsDirectory(),
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

