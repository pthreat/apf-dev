<?php

	namespace apf\ui\form{

		use \apf\core\Kernel;
		use \apf\core\Configurable;
		use \apf\ui\Form;
		use \apf\ui\form\element\Factory	as	ElementFactory;

		class Factory{

			/**
			 * The createFromConfigurableObject factory method creates a form through a Configurable object.
			 *
			 * For achieveing this purpose, it does the following operations: 
			 *
			 *	Gets a form instance through the self::getInstanceFromUIContext method.
			 *	if no $ui argument is given, ($ui=NULL) it will autodetermine which is the pertinent UI 
			 * where it's been called from (cli, web, etc).
			 * 
			 * If a $ui argument is given (i.e not left to default NULL), it will use that $ui.			 
			 *
			 * Takes in a Configurable object as the first parameter (mandatory) and obtains it's attributes,
			 * this configurable object is used to obtain the configuration attributes/properties of said configurable object.
			 *
			 * For each configurable property of the configurable object, it will create an element inside the form.
			 *
			 * @params	\apf\core\Configurable	$object , An object that extends to \apf\core\Configurable class.
			 * @params	string						$ui A valid User Interface (cli, web, etc)
			 *
			 * @return	\apf\ui\form\Cli			If the autodetected context is cli or if the $ui argument is equal to cli
			 * @return	\apf\ui\form\Web			If the autodetected context is web or if the $ui argument is equal to web
			 *
			 */

			public static function createFromConfigurableObject(Configurable &$object,$ui=NULL){

				if($ui===NULL){

					$ui	=	Kernel::getSAPI()->getName();

				}

				/**
				 * Get a form instance according to the passed ui:
				 *
				 * if the $ui argument is NULL, the UI will be autodetermined by the getInstanceFromUIContext method
             */

				$form				=	self::getInstanceFromUIContext($ui);

				/**
				 * Get all the attributes from the configuration of the configurable object
				 */

				$attributes		=	$object->getConfig()->getAttributes();

				/**
				 * If the configurable object's configuration has no attributes then throw an exception
				 */

				if(!sizeof($attributes)){

					$msg	=	"No attributes were found in this object's configuration, if it has no attributes, this means I can't";
					$msg	=	sprintf('%s create any form elements!',$msg);

					throw new \InvalidArgumentException($msg);

				}

				/**
             * If attributes are found, then begin creating the corresponding form elements.
				 * Add each element to the $form we created before.
				 */

				foreach($attributes as $attribute){

					$type				=	'input'; //To be determined later

					$element			=	ElementFactory::getInstanceFromUIContext('input',$ui);

					$element->getConfig()
					->setName($attribute['name'])
					->setDescription($attribute['description']);

					if($attribute['value'] !== NULL){

						$element->setValue($attribute['value']);

					}

					/**
					 * Add the created element to the form
					 */

					$form->addElement($element);

				}

				/**
				 * Return the created form
				 */

				return $form;

			}

			/**
			 * Gets a form instance according to the context obtained from the within the Kernel::getSAPI method
          *
			 * @return	\apf\ui\form\Cli			If the autodetected context is cli or if the $ui argument is equal to cli
			 * @return	\apf\ui\form\Web			If the autodetected context is web or if the $ui argument is equal to web
			 */

			public static function getInstanceFromUIContext($ui=NULL){

				/**
             * If no $ui is specified, autodetect it through the Kernel::getSAPI object
             */

				if($ui===NULL){

					$ui	=	Kernel::getSAPI()->getName();

				}

				/**
             * Compose the class name
             */

				$class	=	sprintf('\apf\ui\form\%s',ucwords($ui));

				/**
				 * Corroborate that the given form UI class does in fact exists
             */

				if(!class_exists($class)){

					throw new \InvalidArgumentException("No Form UI named \"$ui\" was found, perhaps you'd like to help and create it?");

				}

				/**
				 * Create a new form instance of the corresponding form considering the context 
				 * where PHP is being called, i.e cli, web, etc
				 */

				return new $class();

			}

		}

	}

