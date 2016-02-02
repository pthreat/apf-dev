<?php

	namespace apf\web\core{

		use \apf\web\core\Request;
		use \apf\core\Configurable;
		use \apf\web\core\controller\Config				as	ControllerConfig;
		use \apf\web\core\controller\Action;
		use \apf\web\core\controller\action\Config	as	ActionConfig;

		use \apf\web\asset\Css								as	CssAsset;
		use \apf\web\asset\Javascript						as	JSAsset;
		use \apf\web\asset\css\Config						as	CssAsset;
		use \apf\web\asset\javascript\Config			as	JSAsset;

		use \apf\core\Cmd;

		class Controller extends Configurable{

			public function setRequest(Request $request){

				$this->request	=	$request;

			}

			public function getRequest(){

				return $this->request;

			}

			//Deberiamos obtener los templates dentro del directorio que se llame como el controlador

			/**
			 * Devuelve una vista por defecto que el nombre coincide con controller/accion.tpl
			 * por parametro el archivo deseado
			 *
			 * @example getViewInstance(['directory/foo.tpl'])
			 * @param array $templates
			 * @return View
			 * @throws \Exception
			 */
			public function getViewInstance(Array $templates=Array(),$useActionNameAsTpl=TRUE){

			}

			public function __interactiveConfig($config,$log){

				$config	=	new ControllerConfig($config);

				$log->info('[ Controller configuration ]');

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				$controller	=	new Controller($config,$validate='soft')

				Asset::addAssetsToObject($controller,'Add controller assets',

				do{

					$log->info('Add actions to your controller.');

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New Action','E'=>'End adding actions'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$actionConfig	=	new ActionConfig();
					$actionConfig->setController($controller);

					$config->addAction(Action::interactiveConfig($actionConfig,$log));

				}while(TRUE);

				return $controller;

			}

		}	

	}

