<?php

	namespace apf\traits\web{

		use \apf\web\Asset;

		trait Assetable{

			public function addAsset(Asset $asset){

				$this->assets[$asset->getType()][$asset->getName()]	=	$asset;

				return $this;

			}

			public function getAsset($type,$name){

				$hasAsset	=	$this->hasAsset($type,$name);

				if($hasAsset===NULL){

					throw new \LogicException("No \"$name\" assets have been found");

				}

				if(!$hasAsset){

					throw new \InvalidArgumentException("No asset with name \"$name\" could be found");

				}

				return $this->assets[$type][$name];

			}

			public function hasAsset($type,$name){

				if(!array_key_exists($type,$this->assets)){

					return NULL;

				}

				if(!array_key_exists($name,$this->assets[$type])){

					return FALSE;

				}

				return TRUE;

			}

			public function addJavascript($uri,$name=NULL){

				return $this->addAsset('javascript',$uri,$name);

			}

			public function getJavascript($name){

				return $this->getAsset('javascript',$name);

			}

			public function addCSS($uri,$name=NULL){

				return $this->addAsset('css',$uri,$name);

			}

			public function getCSS($name){

				return $this->getAsset('css',$name);

			}


		}

	}
