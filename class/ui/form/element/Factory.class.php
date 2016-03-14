<?php

	namespace apf\ui\form\element{

		use \apf\core\Kernel;
		use \apf\iface\ui\form\element\Config	as	ElementConfigInterface;

		class Factory{

			/**
			 *Gets a form element instance according to the context obtained from the within the Kernel::getSAPI method
			 *
			 *@param string $type									The *type* of the element, input, select, etc>
			 *@param ElementConfigInterface						The configuration for said element.
			 *@param string $ui										User Interface for the given element type, web, cli, etc
			 */

			public static function getInstanceFromUIContext($type,$ui=NULL){

				$type	=	trim($type);

				/**
				 *An element type is mandatory. This "type" is the type of element one wishes to create
				 *an input element, a select element, etc.
             */

				if(empty($type)){

					throw new \InvalidArgumentException("Must specify element type!");

				}

				/**
				 *	If no $ui is specified, autodetect the user interface through the Kernel::getSAPI object
				 */

				if($ui===NULL){

					$ui	=	Kernel::getSAPI()->getName();

				}

				$class	=	self::getElementClassByTypeAndUI($type,$ui);

				/**
				 *	Return a new element type instance, configured with a name, a value and a layout container.
				 */

				return new $class();

			}

			public static function getElementClassByTypeAndUI($type,$ui){

				/**
				 * Make the element class name
             */

				$class	=	sprintf('\apf\ui\form\%s\element\%s',strtolower($ui),ucwords($type));

				/**
				 *	Corroborate that the given form element does in fact exists
				 */

				if(!class_exists($class)){

					throw new \InvalidArgumentException("No UI element of type: $type could be found for UI: $ui");

				}

				return $class;

			}

			public static function getElementConfigClassByTypeAndUI($type,$ui){

				/**
				 * Make the element config class name
             */

				$class = printf('%s\Config',strtolower(self::getElementClassByTypeAndUI($type,$ui)));

				/**
				 *	Corroborate that the given form element does in fact exists
				 */

				if(!class_exists($class)){

					throw new \InvalidArgumentException("No UI element Config class of type: $type could be found for UI: $ui");

				}

				return $class;

			}

		}

	}
