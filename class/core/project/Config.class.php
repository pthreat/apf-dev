<?php

	namespace apf\core\project{

		use apf\core\Cmd;
		use apf\core\Directory	as	Dir;
		use apf\core\Config		as BaseConfig;

		class Config extends BaseConfig{

			public static function getDefaultInstance(){
			}

			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Project name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return parent::getName();

			}

			public function setFragmentsDirectory(Dir $dir){

				$this->fragmentsDirectory	=	$dir;

				return $this;

			}

			public function getFragmentsDirectory(){

				return parent::getFragmentsDirectory();

			}

			public function setTemplatesDirectory(Dir $dir){

				$this->templatesDirectory	=	$dir;

				return $this;

			}

			public function getTemplatesDirectory(){

				return parent::getTemplatesDirectory();

			}

			public function getNonExportableAttributes(){

				return Array();

			}

			public function setDirectory(Dir $dir){

				$this->directory	=	$dir;

				return $this;

			}

			public function getDirectory(){

				return parent::getDirectory();

			}

			public function setModulesDirectory(Dir $dir){

				$this->modulesDirectory	=	$dir;
				return $this;

			}

			public function getModulesDirectory(){

				return parent::getModulesDirectory();

			}

			public function addModule(Module $module){

				$this->modules[$module->getName()]	=	$module;
				return $this;

			}

			public function getModule($name){

				if(!$this->hasModule($name)){

					throw new \InvalidArgumentException("No module named \"$name\" could be found in this project");

				}

				return $this->module[$name];

			}

			public function hasModule($name){

				return array_key_exists($name,$this->modules);

			}

		}

	}

