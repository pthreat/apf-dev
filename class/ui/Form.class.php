<?php

	namespace apf\ui{

		use \apf\ui\form\Element;
		use \apf\iface\Decorator	as	DecoratorInterface;

		abstract class Form{

			private	$action		=	NULL;
			private	$title		=	NULL;
			private	$elements	=	Array();
			private	$decorator	=	NULL;

			public function __construct(){

				$this->configure();

			}

			public function setDecorator(DecoratorInterface $decorator){

				$this->decorator	=	$decorator;
				return $this;

			}

			public function getDecorator(){

				return $this->decorator;

			}

			public function setAction($action){

				$this->action	=	$action;
				return $this;

			}

			public function getAction(){

				return $this->action;

			}

			public function setTitle($title){

				$this->title	=	$title;
				return $this;

			}

			public function getTitle(){

				return $this->title;

			}

			public function addElement(Element $element){

				$this->elements[$element->getName()]	=	$element;
				return $this;

			}

			public function getElements(){

				return $this->elements;

			}

			public function getElementByName($name){

				if(!$this->hasElement($name)){

					throw new \InvalidArgumentException("Unknown element \"$name\"");

				}

				return $this->elements[$name];

			}

			public function hasElement($name){

				return array_key_exists($name,$this->elements);

			}

			abstract public function render();
			abstract public function configure();

			public function __toString(){

				return $this->render();

			}

		}

	}
