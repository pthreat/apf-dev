<?php

	namespace apf\ui\form\element\layout{

		use \apf\iface\ui\form\element\Layout				as	ElementLayoutInterface;
		use \apf\iface\ui\form\element\layout\Container	as	ElementLayoutContainerInterface;

		class Container implements ElementLayoutContainerInterface{

			/**
			 * Each element ->state<- has a different layout
			 * For no value, there a layout 
			 * For success, there is a layout
			 * and for error, there is a layout
			 * A layout is just the "way" how an element is rendered, 
			 * i.e where should the name be placed?, where should the value be placed?
			 *
			 * Example layouts: 
			 * name:separator:value:error
			 * [name]:separator :<value>:error!
			 */

			private	$noValueLayout	=	NULL;
			private	$successLayout	=	NULL;
			private	$errorLayout	=	NULL;

			public function setNoValueLayout(ElementLayoutInterface $layout){

				$this->noValueLayout	=	$layout;
				return $this;

			}

			public function getNoValueLayout(){

				return $this->noValueLayout;

			}

			public function setSuccessLayout(ElementLayoutInterface $layout){

				$this->successLayout	=	$layout;
				return $this;

			}

			public function getSuccessLayout(){

				return $this->successLayout;

			}

			public function setErrorLayout(ElementLayoutInterface $layout){

				$this->errorLayout	=	$layout;
				return $this;

			}

			public function getErrorLayout(){

				return $this->errorLayout;

			}

		}

	}

