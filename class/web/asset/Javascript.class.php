<?php

	namespace apf\web\asset{

		use apf\web\Asset 						as BaseAsset;
		use apf\web\asset\javascript\Config	as	JavascriptAssetConfig;

		class Javascript extends BaseAsset{

			protected static function __interactiveConfig($config,$log){

				$config	=	new JavascriptAssetConfig($config);
				$config	=	parent::baseAssetConfiguration($config,$log);

				$jsAsset	=	new static($config);

				return $jsAsset;

			}

		}

	}

