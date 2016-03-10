<?php

	namespace apf\ui\form\element{

		use \apf\iface\ui\form\element\Attribute;

		class Container{

			private	$attributes	=	NULL;

			public function __construct(){

				$this->attributes	=	new \ArrayObject();

			}

			public function addAttribute(Attribute $attribute){

				$this->attributes->append($attribute);
				return $this;

			}

			public function getAttributes(){

				return $this->attributes;

			}

			public function getAttribute($name){
			}

		}

	}
