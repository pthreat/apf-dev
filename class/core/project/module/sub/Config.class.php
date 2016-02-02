<?php

	namespace apf\core\project\module\sub{

		use \apf\core\Cmd;
		use \apf\core\Config							as	BaseConfig;
		use \apf\core\Directory						as	Dir;
		use \apf\core\project\Module;
		use \apf\core\project\module\Config		as	ModuleConfig;
		use \apf\iface\Log							as	LogInterface;

		class Config extends BaseConfig{

			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Sub (module) name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return parent::getName();

			}

			public function setModule(Module $module){

				$this->module	=	$module;
				return $this;

			}

			public function getModule(){

				return $this->module;

			}

			public function setDirectory(Dir $dir){

				$this->directory	=	$dir;

				return $this;

			}

			public function getDirectory(){

				return parent::getDirectory();

			}

			public function setControllersDirectory(Dir $dir){

				$this->controllersDirectory	=	$dir;
				return $this;

			}

			public function getControllersDirectory(){

				return parent::getControllersDirectory();

			}

			public function setTemplatesDirectory(Dir $dir){

				$this->directory	=	$dir;

				return $this;

			}

			public function getTemplatesDirectory(){

				return parent::getTemplatesDirectory();

			}

			public function setFragmentsDirectory(Dir $dir){

				$this->fragmentsDirectory	=	$dir;
				return $this;

			}

			public function getFragmentsDirectory(){

				return parent::getFragmentsDirectory();

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

			public function getNonExportableAttributes(){

				return Array(
									'controllers'
				);

			}

		}

	}

