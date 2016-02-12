<?php

	namespace apf\core\project{

		use \apf\core\Config						as BaseConfig;

		use \apf\iface\config\Nameable		as	NameableInterface;
		use \apf\iface\config\RootDirectory	as	RootDirectoryInterface;
		use \apf\iface\config\Moduleable		as	ModuleableInterface;
		use \apf\iface\config\Networkable	as	NetworkableInterface;
		use \apf\iface\config\web\Assetable	as	AssetableInterface;
		use \apf\iface\config\Templateable	as	TemplateableInterface;

		class Config extends BaseConfig implements NameableInterface,RootDirectoryInterface,ModuleableInterface,NetworkableInterface,AssetableInterface,TemplateableInterface{

			use \apf\traits\config\Nameable;
			use \apf\traits\config\RootDirectory;
			use \apf\traits\config\Moduleable;
			use \apf\traits\config\Networkable;
			use \apf\traits\config\web\Assetable;
			use \apf\traits\config\Templateable;

			public function getNonExportableAttributes(){

				return Array();

			}

		}

	}

