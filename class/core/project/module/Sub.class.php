<?php

	namespace apf\core\project\module{

		use \apf\core\Cmd;

		use \apf\core\Directory							as	Dir;
		use \apf\core\Configurable;
		use \apf\core\project\module\Config			as ModuleConfig;
		use \apf\core\project\Module;
		use \apf\core\project\module\sub\Config	as	SubConfig;
		use \apf\iface\Log								as	LogInterface;
		use \apf\iface\Crud								as	CrudInterface;
		use \apf\web\core\Controller					as	WebController;
		use \apf\web\core\controller\Config			as	ControllerConfig;

		class Sub extends Configurable{

			private	$controllers	=	Array();

			public function create(LogInterface $log){

				$this->validateConfig();

			}

			public function delete(LogInterface $log){
				
			}

			public function update(LogInterface $log){

			}

			public function validateConfig(){
			}


			public function listControllers(){

				return (new Dir($this->config->getControllersDirectory()))->getIterator();

			}

			/**
			*Returns an interactively configured Sub (Module) class
			*/

			protected static function __interactiveConfig($config,$log){

				$log->success('[Sub configuration]');

				$config	=	new SubConfig($config);

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(empty($config->getName()));

				$log->repeat('-',60,'white');

				do{

					$log->info('Please specify the directory for this sub');

					$dir	=	$config->getModule()
					->getConfig()
					->getDirectory()
					->addPath($config->getName());

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

					$log->info('Specify which controllers shall be created.');
					$opt	=	Cmd::selectWithKeys(Array('N'=>'New controller','E'=>'End adding controllers'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$controllerConfig	=	new ControllerConfig();
					$controllerConfig->setSub($config);

					$config->addController(WebController::interactiveConfig($controllerConfig,$log));

				}while(TRUE);

				return new Sub($config,$validate='soft');

			}

		}

	}

