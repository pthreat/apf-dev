<?php

	namespace apf\ui\form\element{

		use \apf\iface\ui\form\element\Attribute	as	ElementAttributeInterface;

		class Attribute implements ElementAttributeInterface{

			private	$name							=	NULL;
			private	$value						=	NULL;
			private	$nameValueSeparator		=	'=';
			private	$valueWrappingCharacter	=	'"';

			public function __construct($name,$value=NULL){

				$this->name	=	$name;

				if($value!==NULL){

					$this->setValue($value);

				}

			}

			public function setName($name){

				$this->name	=	$name;
				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function setValue($value){

				$this->value	=	$value;
				return $this;

			}

			public function getValue(){

				return $this->value;

			}


			public function setValueWrappingCharacter($char){

				$this->valueWrappingCharacter	=	$char;
				return $this;

			}

			public function getValueWrappingCharacter(){

				return $this->valueWrappingCharacter;

			}

			public function setNameValueSeparator($separator){

				$this->nameValueSeparator	=	$separator;
				return $this;

			}

			public function getNameValueSeparator(){

				return $this->nameValueSeparator;

			}

			public function render(){

				$nvs	=	$this->nameValueSeparator;
				$wrap	=	$this->valueWrappingCharacter;

				return sprintf('%s%s%s%s%s',$this->name,$nvs,$wrap,$this->value,$wrap);

			}

			public function __toString(){

				return $this->render();

			}

		}

	}
