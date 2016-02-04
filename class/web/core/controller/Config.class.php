<?php

	namespace apf\web\core\controller{

		use apf\core\Cmd;
		use apf\web\core\controller\Action;
		use apf\core\Directory					as	Dir;
		use apf\core\Config						as BaseConfig;
		use \apf\iface\web\Assetable			as	AssetableInterface;

		class Config extends BaseConfig implements AssetableInterface{

			//Adds assets method such as addAsset, getAsset, addJavascript, addCss, etc
			use \apf\traits\web\Assetable;

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

