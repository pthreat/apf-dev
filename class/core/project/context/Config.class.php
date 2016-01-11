<?php

	namespace apf\core\project\context{

		use apf\core\Config	as	BaseConfig;

		class Config extends BaseConfig{

			private	$name					=	NULL;
			private	$plugins				=	Array();
			private	$outputDecorator	=	NULL;

			public function setName($name){

				$this->name	=	$name;
				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function setOutputDecorator(OutputDecorator $decorator){

				$this->outputDecorator	=	$decorator;
				return $this;

			}

			public function getOutputDecorator(){

				return $this->outputDecorator;

			}

			public function addPlugin(Plugin $plugin){

				if($this->hasPlugin($plugin->getName())){

					throw new \InvalidArgumentException("Duplicated plugin name \"{$plugin->getName()}\"");

				}

				$this->plugins[$plugin->getName()]	=	$plugin;

				return $this;

			}

			public function hasPlugin($name){

				return array_key_exists($name,$this->plugins);

			}

			public function getPlugins(){

				return $this->plugins;

			}

		}

	}

