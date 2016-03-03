<?php

	namespace apf\ui\form{

		use \apf\core\Configurable;
		use \apf\iface\ui\form\Element						as	ElementInterface;
		use \apf\iface\ui\form\element\Attribute			as	ElementAttributeInterface;
		use \apf\validate\Vector								as	VectorValidate;
		use \apf\iface\ui\form\element\layout\Container	as	ElementLayoutContainerInterface;

		abstract class Element implements ElementInterface{

			private	$name					=	NULL;
			private	$description		=	NULL;
			private	$attributes			=	Array();
			private	$value				=	NULL;
			private	$onSetValue			=	NULL;
			private	$valueState			=	'noval';	//Valid value states are: noval, success, and error

			private	$layoutContainer	=	NULL;

			public function __construct($attrName,$description,ElementLayoutContainerInterface $container){

				if($attrName){

					$this->setName($attrName);

				}

				if($description){

					$this->setDescription($description);

				}

				$this->setLayoutContainer($layoutContainer);

			}

			public function setLayoutContainer(ElementLayoutContainerInterface $container){

				$this->layoutContainer	=	$container;
				return $this;

			}

			public function getLayoutContainer(){

				return $this->layoutContainer;

			}

			public function setName($name){

				$this->name	=	$name;
				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function setDescription($description){

				$this->description	=	$description;
				return $this;

			}

			public function getDescription(){

				return $this->description;

			}

			public function onSetValue(Callable $callback){

				$this->onSetValue	=	$callback;
				return $this;

			}

			public function setValue($value){

				if($this->onSetValue !== NULL){

					try{

						$callback	=	&$this->onSetValue;
						$callback($value);

					}catch(\Exception $e){

						$this->setValueState('error');
						throw new \Exception($e->getMessage());

					}

				}

				$this->value	=	$value;
				$this->setValueState('success');

				return $this;

			}

			public function setValueState($state){

				$this->valueState	=	$state;
				return $this;

			}

			public function getValueState(){

				return $this->valueState;

			}

			public function getValue(){

				return $this->value;

			}

			public function addAttribute(ElementAttributeInterface $attribute){

				$this->attributes[]	=	$attribute;
				return $this;

			}

			public function getAttributes(){

				return $this->attributes;

			}

			public function setAttributes(Array $attributes){

				foreach($attributes as $attribute){

					$this->addAttribute($attribute);

				}

				return $this;

			}

			public function render(){

				switch($this->valueState){

					case 'noval':
						return $this->layoutContainer->getNoValueLayout()->render();
					break;

					case 'success':
						return $this->layoutContainer->getValueLayout()->render();
					break;

					case 'error':
						return $this->layoutContainer->getErrorLayout()->render();
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

