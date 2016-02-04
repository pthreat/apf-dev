<?php

	namespace apf\web\asset\javascript\config{

		use apf\web\asset\config\Validator	as	BaseValidator;

		class Validator extends BaseValidator{

			protected static function validateAssetConfigSoft($config){

				return TRUE;

			}

			protected static function validateAssetConfigHard($config){

				return TRUE;

			}

			protected static function validateAssetConfigExtra($config){

				return TRUE;

			}

		}

	}

