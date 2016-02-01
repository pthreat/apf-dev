<?php

	namespace apf\web{

		use \apf\core\Configurable;
		use \apf\core\Cmd;
		use \apf\core\Directory		as	Dir;
		use \apf\util\String			as	StringUtil;
		use \apf\web\asset\Config	as	AssetConfig;

		abstract class Asset extends Configurable{

			public function isLocal(){

				return is_file($this->config->getURI());

			}

			public function isRemote(){

				return !$this->isLocal();

			}

			public function getType(){

				return strtolower(basename(get_class($this)));

			}

			public function minify(){

				return StringUtil::minify(file_get_contents($this->getContents()));

			}

			public function downloadIn(Dir $dir){

				if($this->isLocal()){

					throw new \LogicException("Can not download a local asset");

				}
				
			}

			public function copyTo(Dir $dir){
			}

			/**
			 *
			 * The basic asset configuration method will be called by the child classes
			 * This method provides a common way of configure common asset configuration
			 * properties allowing the child classes to add more specific configurations.
			 *
			 */

			protected static function basicAssetConfiguration($config,$log){

				do{

					$config->setUri(Cmd::readInput('uri>',$log));

				}while(!$config->getURI());

				do{

					$config->setName(Cmd::readInput('name>',$log));

				}while(!$config->getName());

				return $config;

			}

			public function __toString(){

				return sprintf('%s',$this->config->getURI());

			}

		}

	}
