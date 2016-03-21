<?php

	namespace apf\ui{

		use \apf\core\Configurable;

		abstract class Form extends Configurable{

			abstract public function render();

			public function getElementByName($name){

				$name	=	strtolower($name);

				//elements -> element -> attribute

				foreach($this->getElements() as $element){

					if(strtolower($element->getValue()->getName()) === $name){

						return $element->getValue();

					}

				}

				throw new \InvalidArgumentException("Could not find an element named ->$name<-");

			}

			public function __toString(){

				return $this->render();

			}

		}

	}
