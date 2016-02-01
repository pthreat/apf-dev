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

			public function addRouter(Router $router){

				$this->router	=	$router;
				return $this;

			}

			public function getRouter(){

				return parent::getRouter();

			}

			public function addAsset(Asset $asset){

				$this->assets[]	=	$asset;
				return $this;

			}

			public function addJavascript(JavascriptAsset $asset){

				return $this->addAsset($asset);

			}

			public function addCSS(CSSAsset $asset){

				return $this->addAsset($asset);

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

