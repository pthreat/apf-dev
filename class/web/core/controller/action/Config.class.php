<?php

	namespace apf\web\core\controller\action{

		use apf\core\Cmd;
		use apf\core\Directory			as	Dir;
		use apf\core\Config				as BaseConfig;
		use apf\web\core\Router;
		use apf\web\core\Asset;
		use apf\web\asset\Javascript	as	JavascriptAsset;
		use apf\web\asset\CSS			as	CSSAsset;

		class Config extends BaseConfig{

			//Adds asset methods such as addAsset, getAsset, addJavascript, addCss, etc
			use \apf\trait\web\Assetable;

			//Adds route methods such as setRoute, getRoute
			use \apf\trait\web\Routeable;

			public static function getDefaultInstance(){
			}

			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Action name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return parent::getName();

			}

			public function getNonExportableAttributes(){

				return Array(
									'router',
									'name'
				);

			}

			public function hasAction($name){

				return array_key_exists($name,$this->actions);

			}

		}

	}

