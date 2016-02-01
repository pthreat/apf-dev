<?php

	namespace apf\web\core{

		use \apf\core\route\Config	as	RouteConfig;

		class Route extends apf\core\Configurable{

			public function __interactiveConfig($config,$log){

				$log->info('[ Route configuration ]');

				$config	=	new RouteConfig($config);

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				do{

					try{

						$config->setDescription(Cmd::readInput('description>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getDescription());

				do{

					$log->info('Enter this route path, for instance /user/:id/profile');

					try{
						
						$config->setPath(Cmd::readInput('path>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getPath());

				return new static($config);

			}

		}

	}
