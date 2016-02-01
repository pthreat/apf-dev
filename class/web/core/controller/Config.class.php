<?php

	namespace apf\web\core\controller{

		use apf\core\Cmd;
		use apf\core\Directory	as	Dir;
		use apf\core\Config		as BaseConfig;
		use apf\web\core\controller\Action;

		class Config extends BaseConfig{

			public static function getDefaultInstance(){
			}

			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Controller name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return parent::getName();

			}

			public function addAction(Action $action){

				$this->actions[$action->getName()]	=	$action;
				return $this;

			}

			public function getAction($name){

				if(!$this->hasAction($name)){

					throw new \InvalidArgumentException("No action named \"$name\" could be found in this controller");

				}

				return $this->actions[$name];

			}

			public function addAsset($type,$uri,$name=NULL){

				if(is_null($name)){

					$name	=	basename($uri);

				}

				$this->assets[$type][]	=	Array(
													'name'	=>	$assetName,
													'uri'		=>	$uri
				);

				return $this;

			}

			public function addJavascript($uri,$name=NULL){

				return $this->addAsset('javascript',$uri,$name);

			}

			public function addCSS($uri,$name=NULL){

				return $this->addAsset('css',$uri,$name);

			}

			public function getNonExportableAttributes(){

				return Array(
								'actions'
				);

			}

			public function hasAction($name){

				return array_key_exists($name,$this->actions);

			}

		}

	}

