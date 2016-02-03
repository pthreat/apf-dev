<?php

	namespace apf\traits\web{

		use \apf\web\Asset;

		trait Assetable{

			public function setAsset(Asset $asset){

				if(empty($this->asset)){
					$this->asset	=	Array();
				}

				$this->asset[]	=	$asset;
				return $this;

			}

			public function addAsset(Asset $asset){

				return $this->setAsset($asset);

			}

			public function getAsset($type,$name){

				return parent::getAsset();

			}

			public function hasAsset($type,$name){

			}


		}


	}
