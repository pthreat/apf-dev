<?php

	namespace apf\core\project\module{

		use \apf\core\Cmd;
		use \apf\core\Config							as	BaeConfig;
		use \apf\core\project\Module;
		use \apf\core\project\module\Config		as	ModuleConfig;
		use \apf\iface\Log							as	LogInterface

		class Config extends BaseConfig{

			public function getExportableAttributes(){

				return Array();

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

				return $this->directory;

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

		}

	}

