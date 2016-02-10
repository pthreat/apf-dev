<?php

	namespace apf\web\asset\javascript\config{

		use \apf\web\asset\config\Cli				as	BaseAssetCliConfig;
		use \apf\web\asset\javascript\Config	as	JSAssetConfig;
		use \apf\iface\Log							as	LogInterface;
		use \apf\web\asset\Javascript;

		class Cli extends BaseAssetCliConfig{

			public static function configure(&$config=NULL, LogInterface &$log){

				$config	=	new JSAssetConfig($config);
				$config	=	parent::configure($config,$log);

				return new Javascript($config,$validate='soft');

			}

		}	

	}
