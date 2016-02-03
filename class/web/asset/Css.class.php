<?php

	namespace apf\web\asset{

		use apf\web\Asset 				as BaseAsset;
		use apf\web\asset\css\Config	as	CssAssetConfig;

		class Css extends BaseAsset{

			protected static function __interactiveConfig($config,$log){

				$config	=	new CssAssetConfig($config);
				$config	=	parent::baseAssetConfiguration($config,$log);

				return new static($config,$validate='soft');

			}

		}

	}

