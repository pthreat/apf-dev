<?php

	namespace apf\core\project\module{

		use \apf\core\Cmd;
		use \apf\core\Config;
		use \apf\core\project\Module;
		use \apf\core\project\module\sub\Config	as	SubConfig;

		class Sub extends Configurable{

			public function create(){

				$this->validateConfig();

			}

			public function validateConfig(){
			}

			/**
			*Returns an interactively configured Sub (Module) class
			*/

			protected static function __interactiveConfig(Array $arguments){

				if(!array_key_exists('module',$arguments)){

					throw new \LogicException('Must pass a Module object through a key named module');

				}

				if(!($arguments['module'] instanceof Module)){

					throw new \InvalidArgumentException('Passed module must be an instance of \\apf\\core\\project\\Module');

				}

				if(!$arguments['module']->isConfigured()){

					throw new \InvalidArgumentException('Passed module object is not configured');

				}

				$log->success('[Sub configuration]');

				$config	=	new SubConfig();

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(empty($config->getName()));

				do{

					$log->info('Please specify the directory for this sub');

					$dir	=	$module->getSubsDirectory()->addPath($config->getName());

					$config->setDirectory(
													new Dir(
																Cmd::readWithDefault(
																							'directory>',
																							$dir,
																							$log
																)
													)
					);

				}while(!$config->getDirectory());

				do{

					$log->info('Please specify the directory for this sub');

					$dir	=	$config->getDirectory()->addPath($config->getName());

					$config->setDirectory(
													new Dir(
																Cmd::readWithDefault(
																							'directory>',
																							$dir,
																							$log
																)
													)
					);


				}while(!$config->getDirectory());

				return new Sub($config);

			}

		}

	}

