<?php

	namespace apf\web\core\route\config{

		use \apf\web\core\Route;
		use \apf\web\core\route\Config	as	RouteConfig;
		use \apf\iface\web\Routeable		as	RouteableInterface;
		use \apf\iface\config\Cli			as	CliConfigInterface;
		use \apf\iface\Log					as	LogInterface;

		use \apf\core\Cmd;

		class Cli implements CliConfigInterface{

			public static function addRoutesToObject(RouteableInterface &$object,LogInterface $log,$title=NULL,$help=NULL){

				do{

					$currentRoutes	=	$object->getRoutes();
					$options			=	Array(
													'N'	=>	'New route',
					);

					if(sizeof($currentRoutes)){
	
						$log->repeat('-',80,'white');
						$log->debug('Current routes');
						$log->repeat('-',80,'white');

						foreach($currentRoutes as $type=>$routes){

							$log->debug("[ Routes ]");

							foreach($routes as $route){

								$log->success("> $route");

							}


						}

						$options['E']	=	'Edit routes';
						$options['D']	=	'Delete routes';

					}

					$options['F']	=	'Finish adding routes';
					$options['H']	=	'Help';

					$log->warning($title);

					$opt	=	Cmd::selectWithKeys($options,'route>',$log);

					switch(strtolower($opt)){

						case 'n':
							$eouteConfig	=	new RouteConfig();
							$object->addRoute(Route::cliConfig($routeConfig,$log));
						break;

						case 'e':
							$log->debug('Edit routes');
						break;

						case 'd':
							$log->debug('Delete routes');
						break;

						case 'f':
							break 2;
						break;

						case 'h':

							$log->debug($help);

						break;

					}

				}while(TRUE);

			}


			public static function configure(&$config=NULL, LogInterface &$log){

				$config	=	new RouteConfig($config);

				$log->info('[ Route configuration ]');

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				$log->info('Add a nice description to this route, in this way, debugging will be a lot easier for you!');

				do{

					try{

						$config->setDescription(Cmd::readInput('description>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getDescription());

				$log->info(sprintf('Enter the route path, for instance: %s',$config->getPath() ? $config->getPath() : '/users/:id/profile'));

				do{

					try{

						$config->setPath(Cmd::readInput('path>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getPath());

				$route	=	new Route($config,$validate='soft');

				return $route;

			}

		}

	}
