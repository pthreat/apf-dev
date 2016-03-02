<?php

	namespace apf\ui\form\element{

		use \apf\iface\ui\form\Element			as	ElementInterface;
		use \apf\iface\ui\form\element\Layout	as	LayoutInterface;

		abstract class Layout implements LayoutInterface{

			private	$element			=	NULL;

			public function __construct(ElementInterface &$element,$format=NULL){

				$this->element	=	$element;

				if($format !== NULL){

					$this->setFormat($format);

				}

				$this->setElement($element);

			}

			public function setElement(ElementInterface &$element){

				$this->element	=	$element;
				return $this;

			}

			public function getElement(){

				return $this->element;

			}

			public function setFormat($format){

				$format			=	trim($format);

				if(empty($format)){

					throw new \InvalidArgumentException("Invalid layout format for element {$this->element->getName()}");

				}

				$this->format	=	$format;

				return $this;

			}

			public function getFormat(){

				return $this->format;

			}

		}

	}
