<?php

	namespace apf\web\core\controller\action{

		use \apf\core\Cmd;
		use \apf\web\core\Router;
		use \apf\web\Asset;
		use \apf\web\core\Controller;

		use \apf\core\Directory			as	Dir;
		use \apf\core\Config				as BaseConfig;
		use \apf\iface\web\Assetable	as	AssetableInterface;
		use \apf\iface\web\Routeable	as	RouteableInterface;

		class Config extends BaseConfig implements RouteableInterface,AssetableInterface{

			//Adds asset methods such as addAsset, getAsset, addJavascript, addCss, etc
			use \apf\traits\web\Assetable;

			//Adds route methods such as addRoute, getRoute, hasRoute and getRoutes
			use \apf\traits\web\Routeable;

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

			public function setController(Controller $controller){

				$this->controller	=	$controller;
				return $this;

			}

			public function getController(){

				return parent::getController();

			}

			public function getNonExportableAttributes(){

				return Array(
									'name'
				);

			}

		}

	}

