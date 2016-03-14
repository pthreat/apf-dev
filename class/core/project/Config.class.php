<?php

	namespace apf\core\project{

		use \apf\core\Config					as BaseConfig;

		use \apf\core\config\Attribute;

		use \apf\iface\config\Nameable;
		use \apf\iface\config\Describable;
		use \apf\iface\config\Moduleable;
		use \apf\iface\config\Templateable;
		use \apf\iface\config\Fragmentable;
		use \apf\iface\config\Networkable;
		use \apf\iface\config\project\Directories;

		use \apf\iface\config\web\Assetable;

		use \apf\iface\config\DocumentRootable;

		class Config extends BaseConfig{

			public function configure(){

				parent::getAttributeContainer()
				->add(
						Array(
								'name'			=>	'name',
								'description'	=>	'Project name'
						)
				)
				->add(
						Array(
								'name'			=>	'description',
								'description'	=>	'Project description' 
						)
				)
				->add(
						Array(
								'name'			=>	'directories',
								'description'	=>	'Project directories' 
						)
				)
				->add(
						Array(
								'name'			=>	'documentRoot',
								'description'	=>	'Project document root'
						)
				)
				->add(
						Array(
								'name'			=>	'module',
								'description'	=>	'Project modules' 
						)
				)
				->add(
						Array(
								'name'			=>	'templates',
								'description'	=>	'Project templates' 
						)
				)
				->add(
						Array(
								'name'			=>	'fragments',
								'description'	=>	'Project fragments' 
						)
				)
				->add(
						Array(
								'name'			=>	'assets',
								'description'	=>	'Project assets' 
						)
				)
				->add(
						Array(
								'name'			=>	'connections',
								'description'	=>	'Project connections'
						)
				);

			}

		}

	}

