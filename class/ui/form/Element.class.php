<?php

	namespace apf\ui\form{

		use \apf\core\Configurable;

		abstract class Element{

			private	$name				=	NULL;
			private	$description	=	NULL;
			private	$attributes		=	NULL;
			private	$value			=	NULL;
			private	$onSetValue		=	NULL;

			public function __construct($attrName=NULL,$description=NULL){

				if($attrName){

					$this->setName($attrName);

				}

				if($description){

					$this->setDescription($description);

				}

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

					$callback	=	&$this->onSetValue;
					$callback($value);

				}

				$this->value	=	$value;
				return $this;

			}

			public function getValue(){

				return $this->value;

			}

			public function addAttribute(Attribute $attribute){

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

			public function __toString(){

				return $this->render();

			}

		}

	}

