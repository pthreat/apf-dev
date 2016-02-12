<?php

	namespace apf\core\project\module\sub{

		use \apf\core\Cmd;
		use \apf\core\Config								as	BaseConfig;
		use \apf\core\Directory							as	Dir;
		use \apf\core\project\Module;
		use \apf\core\project\module\Config			as	ModuleConfig;
		use \apf\iface\Log								as	LogInterface;

		use \apf\iface\config\Nameable				as	NameableInterface;
		use \apf\iface\config\RootDirectory			as	RootDirectoryInterface;
		use \apf\iface\config\web\Controllable		as	ControllableInterface;
		use \apf\iface\config\Templateable			as	TemplateableInterface;
		use \apf\iface\config\web\Assetable			as	AssetableInterface;
		use \apf\iface\config\web\Controllable		as	ControllableInterface;

		class Config extends BaseConfig implements NameableInterface,RootDirectoryInterface,ControllableInterface,TemplateableInterface,AssetableInterface{

			use \apf\traits\config\Nameable;
			use \apf\traits\config\RootDirectory;
			use \apf\traits\config\web\Controllable;
			use \apf\traits\config\Templateable;
			use \apf\traits\config\web\Assetable;

			public function setModule(Module $module){

				$this->module	=	$module;
				return $this;

			}

			public function getModule(){

				return $this->module;

			}

			public function getNonExportableAttributes(){

				return Array(
									'controllers'
				);

			}

		}

	}

