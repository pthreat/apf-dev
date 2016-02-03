<?php

	namespace apf\web{

		use \apf\core\Configurable;
		use \apf\core\Cmd;
		use \apf\core\Directory						as	Dir;
		use \apf\util\String							as	StringUtil;
		use \apf\web\asset\Config					as	AssetConfig;

		use \apf\iface\web\Assetable				as	AssetableInterface;

		use \apf\web\asset\Css						as	CSSAsset;
		use \apf\web\asset\Javascript				as	JavascriptAsset;

		use \apf\web\asset\css\Config				as	CSSAssetConfig;
		use \apf\web\asset\javascript\Config	as	JSAssetConfig;

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

				if($this->isLocal()){

					throw new \LogicException("Can not download a local asset");

				}
				
			}

			public function copyTo(Dir $dir){
			}

			public function requires(Asset $asset){

				$this->requires[$asset->getName()]	=	$asset;
				return $this;

			}

			public function getRequirements(){

				return $this->requires;

			}

			/**
			 *
			 * The basic asset configuration method will be called by the child classes
			 * This method provides a common way of configure common asset configuration
			 * properties allowing the child classes to add more specific configurations.
			 *
			 */

			protected static function baseAssetConfiguration($config,$log){

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				do{

					try{

						$config->setUri(Cmd::readInput('uri>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getURI());

				return $config;

			}

			public static function addAssetsToObject(AssetableInterface $object,$title,$help,$log){

				do{

					$log->info($title);

					$opt	=	Cmd::selectWithKeys(
															Array(
																		'NC'	=>	'New CSS Asset',
																		'NJ'	=>	'New Javascript Asset',
																		'H'	=>	'Help',
																		'E'	=>	'End adding assets'
															),
															'asset>',
					$log);

					switch(strtolower($opt)){

						case 'nc':
							$cssAssetConfig	=	new CssAssetConfig();
							$object->addAsset(CSSAsset::interactiveConfig($cssAssetConfig,$log));
						break;

						case 'nj':
							$cssAssetConfig	=	new JSAssetConfig();
							$object->addAsset(JSAsset::interactiveConfig($jsAssetConfig,$log));
						break;

						case 'e':
							break 2;
						break;

						case 'h':

							$log->debug($help);

						break;

					}

				}while(TRUE);

			}

			public function __toString(){

				return sprintf('%s',$this->config->getURI());

			}

		}

	}
