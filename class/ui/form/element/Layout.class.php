<?php

	namespace apf\ui\form\element{

		use \apf\iface\ui\form\Element			as	ElementInterface;
		use \apf\iface\ui\form\element\Layout	as	ElementLayoutInterface;
		use \apf\ui\Layout							as	BaseLayout;

		abstract class Layout extends BaseLayout implements ElementLayoutInterface{

			private	$element			=	NULL;

			public function __construct(ElementInterface &$element,$format=NULL){

				$this->setElement($element);

				if($format !== NULL){

					parent::setFormat($format);

				}

			}

			public function setElement(ElementInterface &$element){

				$this->element	=	$element;

				return $this;

			}

			public function getElement(){

				return $this->element;

			}

		}

	}
