<?php

	namespace apf\ui\form{

		use \apf\core\Config					as	BaseConfig;
		use \apf\ui\form\Layout				as	FormLayout;
		use \apf\iface\ui\form\Element	as	ElementInterface;

		abstract class Config extends BaseConfig{

			public function validateTitle($title){

				$title	=	trim($title);
				return $title;
				
			}

			public function validateElements($elements){

				return $elements;

			}

			public function __configure(){

				parent::getAttributeContainer()
				->add(
						Array(
								'name'			=>	'title',
								'description'	=>	'Form title'
						)
				)
				->add(
						Array(
								'name'			=>	'action',
								'description'	=>	'Form action',
								'validate'		=>	FALSE
						)
				)
				->add(
						Array(
								'name'			=>	'layout',
								'description'	=>	'Form layout'
						)
				)
				->add(
						Array(
								'name'			=>	'elements',
								'item'			=> 'element',
								'description'	=>	'Elements composing this form',
								'multiple'		=>	TRUE
						)
				);

			}

		}

	}
