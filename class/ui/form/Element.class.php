<?php

	namespace apf\ui\form{

		use \apf\core\Configurable;

		abstract class Element extends Configurable{

			public function render(){

				switch($this->getConfig()->getValueState()){

					case 'noval':
						return $this->getConfig()->getLayoutContainer()->getNoValueLayout()->render();
					break;

					case 'success':
						return $this->getConfig()->getLayoutContainer()->getValueLayout()->render();
					break;

					case 'error':
						return $this->getConfig()->getLayoutContainer()->getErrorLayout()->render();
					break;

				}

			}

			public function __toString(){

				try{

					return $this->render();

				}catch(\Exception $e){

					return "Error: {$e->getMessage()}";

				}

			}

		}

	}

