<?php

	namespace apf\core\project\module{

		use \apf\core\Cmd;
		use \apf\core\project\module\Config			as ModuleConfig;
		use \apf\core\project\Module;
		use \apf\core\project\module\sub\Config	as	SubConfig;
		use \apf\iface\Log								as	LogInterface;
		use \apf\iface\Crud								as	CrudInterface;
		use \apf\web\core\Controller					as	WebController;

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

			public function addController(Controller $controller){

				$this->controllers[$controller->getName()]	=	$controller;
				return $this;

			}

			public function getController($name){

				if(!array_key_exists($name,$this->controllers)){

					throw new \InvalidArgumentException("Controllers \"$name\" does not exists in this sub");

				}

				return $this->controllers[$name];

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

				do{

					$log->info('Specify which controllers shall be created.');
					$opt	=	Cmd::selectWithKeys(Array('N'=>'New controller','E'=>'End adding controllers'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$config->addController(WebController::interactiveConfig(Array('log'=>$log,'sub'=>$sub)));

				}while(TRUE);

				return new Sub($config);

			}

		}

	}

