<?php

	namespace apf\ui\form\cli{

		use \apf\ui\form\Config					as	BaseFormConfig;
		use \apf\iface\ui\form\cli\Element	as	FormCliElementInterface;
		use \apf\ui\form\cli\Layout			as	CliFormLayout;

		class Config extends BaseFormConfig{

			public function validateElement(FormCliElementInterface $element){

				return $element;

			}

			public function validateLayout(CliFormLayout $layout){

				return $layout;

			}

			public function __configure(){

				$this->getAttributeContainer()
				->add(
						Array(
								'name'			=>	'layoutContainer',
								'description'	=>	'Layout container',
								'value'			=>	$layoutContainer
						)
				);

			}

		}

	}

