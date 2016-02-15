<?php

	namespace apf\web\asset\config{

		use \apf\core\Cmd;
		use \apf\iface\Log							as	LogInterface;
		use \apf\iface\config\Cli					as	CliConfigInterface;
		use \apf\iface\config\web\Assetable		as	AssetableInterface;

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

			public static function configure(&$config,LogInterface &$log){

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

			public static function assetConfiguration(AssetableInterface &$object,$title,$help,$log){

				do{

					Cmd::clear();

					$options	=	Array(
											'AC'	=>	'Add CSS Asset',
											'AJ'	=>	'Add Javascript Asset',
					);

					$currentAssets	=	$object->getAssetsOrderedByType();

					if(sizeof($currentAssets)){
	
						$options['EA']	=	'Edit assets';
						$options['DA']	=	'Delete assets';
						$options['LA']	=	'List assets';

					}

					$options['H']	=	'Help';
					$options['B']	=	'Back';

					$log->warning($title);

					$opt	=	Cmd::selectWithKeys($options,'asset>',$log);

					switch(strtolower($opt)){

						case 'ac':
							$cssAssetConfig	=	new CssAssetConfig();
							$object->addAsset(CSSAsset::cliConfig($cssAssetConfig,$log));
						break;

						case 'aj':
							$jsAssetConfig		=	new JSAssetConfig();
							$object->addAsset(JSAsset::cliConfig($jsAssetConfig,$log));
						break;

						case 'ea':
							$log->debug('Edit assets');
						break;

						case 'da':
							$log->debug('Delete assets');
						break;

						case 'la':
							self::listAssets($object,$log);
							Cmd::readInput('Press enter to continue ...',$log);
						break;

						case 'b':
							break 2;
						break;

						case 'h':

							$log->debug($help);
							Cmd::readInput('Press enter to continue ...',$log);

						break;

					}

				}while(TRUE);

			}

			public static function listAssets(AssetableInterface &$config,LogInterface $log){

				$assets	=	$config->getAssetsOrderedByType();

				if(!$assets){

					$log->warning('No assets available');

				}

				$log->repeat('-',80,'white');
				$log->debug('Current assets');
				$log->repeat('-',80,'white');

				foreach($assets as $type=>$assets){

					$log->debug("[ $type Assets ]");

					foreach($assets as $asset){

						$log->success("> $asset");

					}


				}

			}

		}

	}

