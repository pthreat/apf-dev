<?php

	namespace apf\core\project\module{

		use \apf\core\Cmd;
		use \apf\core\Project;
		use \apf\core\project\module\Sub;
		use \apf\core\Directory								as	Dir;
		use \apf\core\Config 								as BaseConfig;

		use \apf\iface\config\Nameable;
		use \apf\iface\config\RootDirectory;

		use \apf\iface\config\Projectable;

		use \apf\iface\config\Subable;
		use \apf\iface\config\sub\Directories			as	SubDirectories;

		use \apf\iface\config\Templateable;
		use \apf\iface\config\template\Directories	as	TemplateDirectories;

		use \apf\iface\config\web\Assetable;

		class Config extends BaseConfig implements Nameable,RootDirectory,Projectable,Subable,SubDirectories,Templateable,Assetable{

			use \apf\traits\config\Nameable;
			use \apf\traits\config\RootDirectory;
			use \apf\traits\config\Projectable;
			use \apf\traits\config\Subable;
			use \apf\traits\config\Templateable;
			use \apf\traits\config\web\Assetable;

			public function getNonExportableAttributes(){

				return Array(
								'project'
				);

			}


		}

	}

