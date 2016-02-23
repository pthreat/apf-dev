<?php

	namespace apf\core\project\module\directories{

		use \apf\core\Config									as BaseConfig;

		use \apf\core\project\Module;
		use \apf\iface\config\RootDirectory;
		use \apf\iface\config\template\Directories	as	TemplateDirectories;
		use \apf\iface\config\fragment\Directories	as	FragmentDirectories;
		use \apf\iface\config\sub\Directories			as	SubDirectories;

		class Config extends BaseConfig implements RootDirectory,TemplateDirectories,FragmentDirectories,SubDirectories{

			use \apf\traits\config\RootDirectory;
			use \apf\traits\config\template\Directories;
			use \apf\traits\config\fragment\Directories;
			use \apf\traits\config\sub\Directories;

			public function setModule(Module $module){

				$this->module	=	$module;
				return $this;

			}

			public function getModule(){

				return parent::getModule();

			}

			public function getNonExportableAttributes(){

				return Array(
								'module'
				);

			}

		}

	}

