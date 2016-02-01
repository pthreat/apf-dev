<?php

	namespace apf\web\core\route{

		use \apf\core\Cmd;
		use \apf\web\core\controller\Action;
		use \apf\core\Config	as	BaseConfig;

		class Config extends BaseConfig{

			/**
			*Route short name 
			*example userRegistration
			*/
			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Route name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return parent::getName();

			}

			/**
			* Brief description of a route for debugging purpouses
			* for instance: "This is the route that is triggered for user registration"
			*/

			public function setDescription($description){

				$description	=	trim($description);

				if(empty($description)){

					throw new \InvalidArgumentException("Route description can not be empty");

				}

				$this->description	=	$description;

				return $this;

			}

			public function getDescription(){

				return parent::getDescription();

			}

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

			}

			public function getPath(){

				return parent::getPath();

			}

			/**
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
