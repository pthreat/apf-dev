<?php

	namespace apf\ui\form\element{

		use \apf\core\Kernel;

		class Factory{

			/**
			 *Gets a form element instance according to the context obtained from the within the Kernel::getSAPI method
			 *
			 *@param string $type			The *type* of the element, input, select, etc
			 *@param string $name			The name of the element
			 *@param string $description	Element Description
			 *@param string $ui				Optional argument. User Interface for the given element type, web, cli, etc
			 */

			public static function getInstanceFromUIContext($type,$name,$value,LayoutContainer $layoutContainer,$ui=NULL){

				$type	=	trim($type);

				if(empty($type)){

					throw new \InvalidArgumentException("Must specify element type!");

				}

				/**
				 *	If no $ui is specified, autodetect the user interface through the Kernel::getSAPI object
				 */

				if($ui===NULL){

					$ui	=	Kernel::getSAPI()->getName();

				}

				$class	=	sprintf('\apf\ui\form\%s\element\%s',strtolower($ui),ucwords($type));

				/**
				 *	Corroborate that the given form element does in fact exists
				 */

				if(!class_exists($class)){

					throw new \InvalidArgumentException("No UI element of type: $type could be found for UI: $ui");

				}

				
				/**
				 *	Return a new element type instance, configured with a name, a value and a layout container.
				 */

				return new $class($name,$value,$layoutContainer);

			}

		}

	}
