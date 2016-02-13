<?php

	namespace apf\core\project{

		use \apf\core\Config							as BaseConfig;

		use \apf\iface\config\Nameable;
		use \apf\iface\config\RootDirectory;
		use \apf\iface\config\Moduleable;
		use \apf\iface\config\Networkable;
		use \apf\iface\config\web\Assetable;
		use \apf\iface\config\Templateable;
		use \apf\iface\config\DocumentRootable;

		class Config extends BaseConfig implements Nameable,RootDirectory,DocumentRootable,Moduleable,Networkable,Assetable,Templateable{

			use \apf\traits\config\Nameable;
			use \apf\traits\config\RootDirectory;
			use \apf\traits\config\DocumentRootable;
			use \apf\traits\config\Moduleable;
			use \apf\traits\config\Networkable;
			use \apf\traits\config\web\Assetable;
			use \apf\traits\config\Templateable;

			public function getNonExportableAttributes(){

				return Array();

			}

		}

	}

