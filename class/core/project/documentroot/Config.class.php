<?php

	namespace apf\core\project\documentroot{

		use \apf\core\Config						as BaseConfig;
		use \apf\iface\config\RootDirectory	as	RootDirectoryInterface;

		class Config extends BaseConfig implements RootDirectoryInterface{

			use \apf\traits\config\RootDirectory;

			public function getNonExportableAttributes(){

				return Array();

			}

		}

	}

