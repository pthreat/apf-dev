<?php

	namespace apf\core\project{

		use \apf\core\Config									as BaseConfig;

		use \apf\iface\config\Nameable;
		use \apf\iface\config\Moduleable;
		use \apf\iface\config\Templateable;
		use \apf\iface\config\Fragmentable;
		use \apf\iface\config\Networkable;
		use \apf\iface\config\project\Directories;

		use \apf\iface\config\web\Assetable;

		use \apf\iface\config\DocumentRootable;

		class Config extends BaseConfig implements Nameable,Moduleable,Templateable,Fragmentable,Networkable,Assetable,DocumentRootable{

			use \apf\traits\config\Nameable;
			use \apf\traits\config\project\Directories;
			use \apf\traits\config\Moduleable;
			use \apf\traits\config\Templateable;
			use \apf\traits\config\Fragmentable;
			use \apf\traits\config\DocumentRootable;
			use \apf\traits\config\Networkable;
			use \apf\traits\config\web\Assetable;


			public function getNonExportableAttributes(){

				return Array();

			}

		}

	}

