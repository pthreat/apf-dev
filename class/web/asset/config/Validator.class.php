<?php

namespace apf\web\asset\config{

		use apf\core\config\Validator	as	BaseValidator;

		abstract class Validator extends BaseValidator{

			abstract protected static function validateAssetConfigSoft($config);
			abstract protected static function validateAssetConfigHard($config);
			abstract protected static function validateAssetConfigExtra($config);

			protected static function __softConfigValidation($config){

				$config->setName($config->getName());
				$config->setURI($config->getURI());

				return static::validateAssetConfigSoft($config);

			}

			protected static function __hardConfigValidation($config){

				return static::validateAssetConfigHard($config);

			}

			protected static function __extraConfigValidation($config){

				return static::validateAssetConfigExtra($config);

			}


		}

	}
