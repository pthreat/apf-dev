<?php

	namespace apf\traits\config{

		use \apf\core\Directory	as	Dir;
		use \apf\core\project\Module;

		trait Moduleable{

			public function addModule(Module $module){

				$this->modules[$module->getName()]	=	$module;
				return $this;

			}

			public function getModule($name){

				if(!$this->hasModule($name)){

					throw new \InvalidArgumentException("No module named \"$name\" could be found in this project");

				}

				return $this->modules[$name];

			}

			public function hasModule($name){

				$modules	=	parent::getModules();

				if(!is_array($modules)){

					return FALSE;

				}

				return array_key_exists($name,$modules);

			}

			public function hasModules(){

				return parent::hasKey('modules');
	
			}

		}

	}

