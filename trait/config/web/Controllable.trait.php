<?php

	namespace apf\traits\config\web{

		use \apf\web\core\Controller;
		use \apf\core\Directory	as	Dir;

		trait Controllable{

			public function setControllersDirectory(Dir $dir){

				$this->controllersDirectory	=	$dir;
				return $this;

			}

			public function getControllersDirectory(){

				return parent::getControllersDirectory();

			}

			public function addController(Controller $controller){

				$this->controllers[$controller->getname()]	=	$controller;
				return $this;

			}

			public function hasController($name){

				if(!parent::hasControllers()){

					return NULL;

				}

				$controllers	=	parent::getControllers();

				return array_key_exists($name,$controllers);

			}

			public function hasControllers(){

				return parent::getControllers() ? TRUE	:	FALSE;

			}

			public function getController($name){

				if(!$this->hasController($name)){

					throw new \InvalidArgumentException("Controller \"$name\" could not be found.");

				}

				return $this->controllers[$name];

			}

		}

	}

