<?php

	namespace apf\core\project{

		use apf\core\Cmd;
		use apf\core\Directory	as	Dir;
		use apf\core\Config		as BaseConfig;

		class Config extends BaseConfig{

			public static function getDefaultInstance(){
			}

			public function setCommonFragmentsDirectory(Dir $dir){

				if($dir->exists() && !$dir->isWritable()){

					throw new \InvalidArgumentException("Directory \"$dir\" is not writable");

				}

				$this->commonFragmentsDirectory	=	$dir;

				return $this;

			}

			public function getCommonFragmentsDirectory(){

				return parent::getCommonFragmentsDirectory();

			}

			public function setCommonTemplatesDirectory(Dir $dir){

				if($dir->exists() && !$dir->isWritable()){

					throw new \InvalidArgumentException("Directory \"$dir\" is not writable");

				}

				$this->commonTemplatesDirectory	=	$dir;

				return $this;

			}

			public function getCommonTemplatesDirectory(){

				return parent::getCommonTemplatesDirectory();

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

			public function setDirectory(Dir $dir){

				if($dir->exists() && !$dir->isWritable()){

					throw new \InvalidArgumentException("Directory \"$dir\" is not writable");

				}

				$this->directory	=	$dir;

				return $this;

			}

			public function getDirectory(){

				return parent::getDirectory();

			}

			public function setModulesDirectory(Dir $dir){

				if($dir->exists() && !$dir->isWritable()){

					throw new \InvalidArgumentException("Modules directory \"$dir\" is not writable");

				}

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

