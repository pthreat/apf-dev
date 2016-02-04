<?php

	namespace apf\traits\web{

		use \apf\web\Asset;

		trait Assetable{

			public function setAssets(Array $assets){

				if(!parent::hasKey('assets')){

					$this->assets	=	new \stdClass();

				}

				foreach($assets as $key=>$asset){

					if(!is_a($asset,'\\apf\\web\\Asset')){

						throw new \InvalidArgumentException("Given array element ($key) is not an Asset");

					}

					$this->assets->{$asset->getConfig()->getName()}	=	$asset;

				}

			}

			public function addAsset(Asset $asset){

				return $this->setAssets(Array($asset));

			}

			public function getAssets(){

			}

			public function getAsset($type,$name){
			}

			public function hasAsset($type,$name){

			}

			public function getAssetsOrderedByType(){

				$assets			=	parent::getAssets();

				if(!sizeof($assets)){

					return Array();

				}

				$orderedAssets	=	Array();

				foreach($assets as $asset){

					$orderedAssets[$asset->getConfig()->getType()][]	=	$asset;

				}

				return $orderedAssets;

			}


		}

	}
