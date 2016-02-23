<?php

	namespace apf\core\kernel{

		use \apf\core\Config									as BaseConfig;

		class Config extends BaseConfig{

			use \apf\traits\config\Projectable;

			public function getNonExportableAttributes(){

				return Array();

			}

		}

	}

