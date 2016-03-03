<?php

	namespace apf\ui\form\element{

		use \apf\core\Kernel;

		class Factory{

			/**
			 *Gets a form instance according to the context obtained from the within the Kernel::getSAPI method
			 *@param string $elementType The *type* of the element, input, select, etc
			 *@param string $name The name of the element
			 *@param string $description Element Description
			 *@param string $ui The kind of User interface for the given element type, web, cli, etc
			 */

			public static function getInstanceFromUIContext($elementType,$ui=NULL){

				$elementType	=	trim($elementType);

				if(empty($elementType)){

					throw new \InvalidArgumentException("Must specify element name");

				}

				//If no $ui is specified, autodetect through the Kernel::getSAPI object, which is the UI context where this factory is being called from

				if($ui===NULL){

					$ui	=	Kernel::getSAPI()->getName();

				}

				$class	=	sprintf('\apf\ui\form\%s\element\%s',strtolower($ui),ucwords($elementType));

				//Corroborate that the given form element does in fact exists

				if(!class_exists($class)){

					throw new \InvalidArgumentException("No UI element named \"$class\" was found");

				}

				return new $class();

			}


		}

	}
