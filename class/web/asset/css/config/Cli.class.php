<?php

	namespace apf\web\asset\css\config{

		use \apf\web\asset\config\Cli	as	BaseAssetCliConfig;
		use \apf\web\asset\Css;
		use \apf\iface\Log				as	LogInterface;
		use \apf\web\asset\css\Config	as	CssAssetConfig;

		class Cli extends BaseAssetCliConfig{

			public static function configure($config=NULL,LogInterface $log){

				$config	=	new CssAssetConfig($config);
				$config	=	parent::configure($config,$log);

				return new Css($config,$validate='soft');

			}

		}	

	}
