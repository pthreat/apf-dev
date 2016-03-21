<?php

	namespace apf\core\project{

		use \apf\core\Config								as BaseConfig;
		use \apf\core\project\Module					as	ProjectModule;
		use \apf\core\project\config\Directories	as	ProjectDirectories;
		use \apf\core\project\DocumentRoot			as	ProjectDocumentRoot;
		use \apf\iface\net\Connection					as	NetConnectionInterface;
		use \apf\web\Asset;

		class Config extends BaseConfig{

			public function validateName($name){

				return $name;

			}

			public function validateConnection(NetConnectionInterface $connection){

				return $connection;

			}

			public function validateModules($modules){

				return $modules;

			}

			public function validateAssets($assets){

				return $assets;

			}

			public function validateTemplate($template){

				return $template;

			}

			public function validateFragment($fragment){

				return $fragment;

			}

			public function validateConnections($connections){

				return $connections;

			}

			public function validateModule(ProjectModule $module){

				return $module;

			}

			public function validateDescription($description){

				return $description;

			}

			public function validateDirectories(ProjectDirectories $directories){

				return $directories;

			}

			public function validateAsset(Asset $asset){

				return $asset;

			}

			public function validateDocumentRoot(ProjectDocumentRoot $documentRoot){

				return $documentRoot;

			}

			public function validateTemplates($templates){

				return $templates;

			}

			public function validateFragments($fragments){

				return $fragments;

			}

			protected function __configure(){

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
								'name'			=>	'modules',
								'description'	=>	'Project modules',
								'multiple'		=>	TRUE,
								'item'			=>	'module'
						)
				)
				->add(
						Array(
								'name'			=>	'templates',
								'description'	=>	'Templates (at a project level)' 
						)
				)
				->add(
						Array(
								'name'			=>	'fragments',
								'description'	=>	'Fragments (at a project level)',
								'multiple'		=>	TRUE,
								'item'			=>	'fragment'
						)
				)
				->add(
						Array(
								'name'			=>	'assets',
								'description'	=>	'Project assets',
								'multiple'		=>	TRUE,
								'item'			=>	'asset'
						)
				)
				->add(
						Array(
								'name'			=>	'connections',
								'description'	=>	'Project connections',
								'multiple'		=>	TRUE,
								'item'			=>	'connection'

						)
				);

			}

		}

	}

