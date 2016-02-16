<?php

	namespace apf\core\project\directories{

		use \apf\core\Config									as BaseConfig;

		use \apf\iface\config\RootDirectory;
		use \apf\iface\config\Projectable;
		use \apf\iface\config\template\Directories	as	TemplateDirectories;
		use \apf\iface\config\fragment\Directories	as	FragmentDirectories;
		use \apf\iface\config\module\Directories		as	ModuleDirectories;

		class Config extends BaseConfig implements RootDirectory,TemplateDirectories,FragmentDirectories,ModuleDirectories{

			use \apf\traits\config\Projectable;
			use \apf\traits\config\RootDirectory;
			use \apf\traits\config\template\Directories;
			use \apf\traits\config\fragment\Directories;
			use \apf\traits\config\module\Directories;

			public function getNonExportableAttributes(){

				return Array();

			}

		}

	}

