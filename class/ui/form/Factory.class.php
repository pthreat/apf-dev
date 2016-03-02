<?php

	namespace apf\ui\form{

		use \apf\core\Kernel;
		use \apf\core\Configurable;
		use \apf\ui\Form;
		use \apf\ui\form\element\Factory	as	ElementFactory;

		class Factory{

			/**
			 * The createFromConfigurableObject form factory does the following: 
			 *
			 *	Gets a form instance through the self::getInstanceFromUIContext method according to the passed UI, 
			 *	if no UI is given, it will autodetermine what is the UI where it's been called from (cli, web, etc).
			 *
			 * Takes in a Configurable object as the first parameter (mandatory) and obtains it's attributes,
			 * this configurable object is used to obtain the configuration attributes/properties of said configurable object.
			 *
			 * Adds the proper form elements according to the previously mentioned properties/attributes 
			 *
			 * @params	\apf\core\Configurable	$object , An object that extends to \apf\core\Configurable class.
			 * @params	string						$ui A valid User Interface (cli, web, etc)
			 *
			 * @return	\apf\ui\form\Cli			If the autodetected context is cli or if the $ui argument is equal to cli
			 * @return	\apf\ui\form\Web			If the autodetected context is web or if the $ui argument is equal to web
			 *
			 */

			public static function createFromConfigurableObject(Configurable &$object,$ui=NULL){

				//Get a form instance according to the passed ui,
				//if the ui is NULL the UI will be autodetermined by the getInstanceFromUIContext method
				//
				//Create a new form instance of the corresponding form considering the context where PHP is being called, i.e cli, web, etc
				$form				=	self::getInstanceFromUIContext($ui);

				//Get all the attributes from the configuration of the configurable object
				$attributes		=	$object->getConfig()->getAttributes();

				//If the configurable object's configuration has no attributes then throw an exception
				if(!sizeof($attributes)){

					throw new \InvalidArgumentException("No attributes were found, can not create from elements!");

				}

				//Else, foreach attribute, create a form element ...
				foreach($attributes as $attribute){

					///////////////////////////////////
					//IMPORTANT:
					//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					//How is the type determined?
					//Add ... multiple or singular characteristic to attributes?
					//For now, we just set everything to input (which is complete bullshit)
					///////////////////////////////////
				
					$element		=	ElementFactory::getInstanceFromUIContext('input')
					->setName($attribute['name'])
					->setDescription($attribute['description']);

					$name			=	$attribute['name'];

					//Set a nice callback so when a value is entered on a certain configuration
					//The internal validation of the setter inside the configuration is executed

					$callback	=	function($value) use ($name,&$object){

						return $object->getConfig()->$name	=	$value;

					};

					//Assign the callback
					$element->onSetValue($callback);

					//Add the element to the form
					$form->addElement($element);

				}

				//Return the created form
				return $form;

			}

			/**
			 *Gets a form instance according to the context obtained from the within the Kernel::getSAPI method
			 */

			public static function getInstanceFromUIContext($ui=NULL){

				//If no $ui is specified, autodetect through the Kernel::getSAPI object, which is the UI context where this factory is being called from

				if($ui===NULL){

					$ui	=	Kernel::getSAPI()->getName();

				}

				$class	=	sprintf('\apf\ui\form\%s',ucwords($ui));

				//Corroborate that the given form UI does in fact exists

				if(!class_exists($class)){

					throw new \InvalidArgumentException("No UI named \"$ui\" was found, perhaps you'd like to contribute and create it? :)");

				}

				return new $class();

			}

		}

	}

