<?php

	namespace apf\ui{

		use \apf\ui\form\Element;
		use \apf\core\Configurable;

		abstract class Form{

			private	$action					=	NULL;
			private	$title					=	NULL;
			private	$elements				=	Array();
			private	$configurableObject	=	NULL;

			public function __construct(){

				$this->configure();

			}

			public function setConfigurableObject(Configurable &$object){

				$this->configurableObject	=	$object;
				return $this;

			}

			public function getConfigurableObject(){

				return $this->configurableObject;

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

			public function createElements(){

				$elementNamespace	=	strtolower(get_called_class());
				$attributes			=	$this->configurableObject->getConfig()->getAttributes();

				if(!sizeof($attributes)){

					throw new \InvalidArgumentException("No attributes where found");

				}

				foreach($attributes as $attribute){

					$attribute['type']	=	'input';

					$element	=	sprintf('%s\element\%s',$elementNamespace,ucwords($attribute['type']));
					$this->addElement(new $element($attribute['name'],$attribute['description'],$this->configurableObject));

				}

				return $this;

			}

			abstract public function render();
			abstract public function configure();

			public function __toString(){

				return $this->render();

			}

		}

	}
