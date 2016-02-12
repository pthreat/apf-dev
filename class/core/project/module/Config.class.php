<?php

	namespace apf\core\project\module{

		use \apf\core\Cmd;
		use \apf\core\Project;
		use \apf\core\project\module\Sub;
		use \apf\core\Directory							as	Dir;
		use \apf\core\Config 							as BaseConfig;

		use \apf\iface\config\Nameable				as	NameableInterface;
		use \apf\iface\config\RootDirectory			as	RootDirectoryInterface;
		use \apf\iface\config\Projectable			as	ProjectableInterface;
		use \apf\iface\config\Subable					as	SubableInterface;
		use \apf\iface\config\Templateable			as	TemplateableInterface;
		use \apf\iface\config\web\Assetable			as	AssetableInterface;

		class Config extends BaseConfig implements NameableInterface,RootDirectoryInterface,ProjectableInterface,SubableInterface,TemplateableInterface,AssetableInterface{

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

