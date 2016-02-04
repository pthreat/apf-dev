<?php

	namespace apf\web\asset\config{

		use \apf\core\Cmd;
		use \apf\iface\Log							as	LogInterface;
		use \apf\iface\config\Cli					as	CliConfigInterface;
		use \apf\iface\web\Assetable				as	AssetableInterface;

		use \apf\web\asset\Css						as	CSSAsset;
		use \apf\web\asset\Javascript				as	JSAsset;

		use \apf\web\asset\css\Config				as	CssAssetConfig;
		use \apf\web\asset\javascript\Config	as	JSAssetConfig;

		class Cli implements CliConfigInterface{

			/**
			 *
			 * The basic asset configuration method will be called by the child classes
			 * This method provides a common way of configure common asset configuration
			 * properties allowing the child classes to add more specific configurations.
			 *
			 */

			public static function configure($config,LogInterface $log){

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

			public static function addAssetsToObject(AssetableInterface &$object,$title,$help,$log){

				do{

					$currentAssets	=	$object->getAssetsOrderedByType();

					$options	=	Array(
											'C'	=>	'Add CSS Asset',
											'J'	=>	'Add Javascript Asset',
					);

					if(sizeof($currentAssets)){
	
						$log->repeat('-',80,'white');
						$log->debug('Current assets');
						$log->repeat('-',80,'white');

						foreach($currentAssets as $type=>$assets){

							$log->debug("[ $type Assets ]");

							foreach($assets as $asset){

								$log->success("> $asset");

							}


						}

						$options['E']	=	'Edit assets';
						$options['D']	=	'Delete assets';

					}

					$options['F']	=	'Finish adding assets';
					$options['H']	=	'Help';

					$log->info($title);

					$opt	=	Cmd::selectWithKeys($options,'asset>',$log);

					switch(strtolower($opt)){

						case 'c':
							$cssAssetConfig	=	new CssAssetConfig();
							$object->addAsset(CSSAsset::cliConfig($cssAssetConfig,$log));
						break;

						case 'j':
							$jsAssetConfig		=	new JSAssetConfig();
							$object->addAsset(JSAsset::cliConfig($jsAssetConfig,$log));
						break;

						case 'e':
							$log->debug('Edit assets');
						break;

						case 'd':
							$log->debug('Delete assets');
						break;

						case 'f':
							break 2;
						break;

						case 'h':

							$log->debug($help);

						break;

					}

				}while(TRUE);

			}

		}

	}

