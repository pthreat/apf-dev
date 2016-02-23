<?php

	namespace apf\core\route{

		use \apf\core\Project;
		use \apf\core\project\Module;
		use \apf\core\project\module\Sub;
		use \apf\web\core\controller\Action;

		use \apf\core\Config	as	BaseConfig;

		class Config extends BaseConfig{

			use \apf\traits\config\Nameable;
			use \apf\traits\config\Describable;

			use \apf\traits\config\Moduleable;
			use \apf\traits\config\Subable;

			/**
			* Route path: Note that this is only the route path.
			* This path will later be parsed by a router/dispatcher
			* Route paths will admit wildcards for holding parameters
			* such as /user/:id/profile
			*/

			public function setPath($path){

				$path	=	trim($path);

				if(empty($path)){

					throw new \InvalidArgumentException("Route path can not be empty");

				}

				$this->path	=	$path;
				return $this;

			}

			public function getPath(){

				return parent::getPath();

			}

			/*
			* A route is always associated with an action
			* an action is associated to a controller
			* a controller is associated to a sub
			* a sub is associated with a module 
			* and a module is associated to a project.
			*/

			public function setAction(Action $action){

				$this->action	=	$action;
				return $this;

			}

			public function getAction(){

				return parent::getAction();

			}

		}

	}
