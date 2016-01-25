<?php

	namespace apf\core\project\module{

		use \apf\core\Cmd;
		use \apf\core\Config							as	BaeConfig;
		use \apf\core\project\Module;
		use \apf\core\project\module\Config		as	ModuleConfig;
		use \apf\iface\Log							as	LogInterface

		class Config extends BaseConfig{

			private	$module	=	NULL;

			public function setModule(Module $module){

				$this->module	=	$module;
				return $this;

			}

			public function getModule(){

				return $this->module;

			}

			public function setDirectory(Dir $dir){

				if($dir->exists() && !$dir->isWritable()){

					throw new \InvalidArgumentException("Directory \"$dir\" is not writable");

				}

				$this->directory	=	$dir;

				return $this;

			}

			public function getDirectory(){

				return $this->directory;

			}

		}

	}

