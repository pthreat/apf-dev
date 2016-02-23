<?php

	namespace apf\traits\config{
	
		use \apf\core\Route;
		use \apf\core\Config;

		trait Routeable{

			public function setRoutes(Array $routes){

				if(!parent::hasKey('routes')){

					$this->routes	=	new Config();

				}

				foreach($routes as $key=>$route){

					if(!is_a($route,'\\apf\\core\\Route')){

						throw new \InvalidArgumentException("Given array element ($key) is not an Route");

					}

					$this->routes->{$route->getConfig()->getName()}	=	$route;

				}

			}

			public function addRoute(Route $route){

				return $this->setRoutes(Array($route));

			}

			public function getRoutes(){

				return parent::getRoutes();

			}

			public function getRoute($name){
			}

			public function hasRoute($name){

			}

		}

	}

