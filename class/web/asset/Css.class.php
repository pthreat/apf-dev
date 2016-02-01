<?php

	namespace apf\web\asset{

		use apf\core\Asset 				as BaseAsset;
		use apf\web\asset\css\Config	as	CssAssetConfig;

		class Css extends BaseAsset{

			protected static function __interactiveConfig($config,$log){

				$config	=	new CssAssetConfig($config);
				return parent::baseAssetConfiguration($config);

			}

		}

	}

