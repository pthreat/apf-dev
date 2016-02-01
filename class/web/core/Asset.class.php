<?php

	namespace apf\web\core{

		use \apf\core\Configurable;
		use \apf\core\Cmd;
		use \apf\core\Directory				as	Dir;
		use \apf\util\String					as	StringUtil;
		use \apf\web\core\asset\Config	as	AssetConfig;

		abstract class Asset extends Configurable{

			public function isLocal(){

				return is_file($this->config->getURI());

			}

			public function isRemote(){

				return !$this->isLocal();

			}

			public function minify(){

				return StringUtil::minify(file_get_contents($this->getContents()));

			}

			public function downloadIn(Dir $dir){
				
			}

			abstract public function __assetConfiguration($config,$log){
			}

			public static function __interactiveConfig($config,$log){

				$log->info('[ Javascript asset configuration ]');

				$config	=	new AssetConfig($config);

				do{

					$config->setUri(Cmd::readInput('uri>',$log));

				}while(!$config->getURI());

				do{

					$config->setName(Cmd::readInput('name>',$log));

				}while(!$config->getName());

			}

		}

	}
