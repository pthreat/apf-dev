<?php

	namespace apf\ui\form\cli\element{

		use \apf\ui\form\element\Config						as	BaseConfig;
		use \apf\ui\form\cli\element\Layout;
		use \apf\ui\form\element\layout\Container			as	LayoutContainer;

		class Config extends BaseConfig{

			use \apf\traits\ui\form\cli\element\Promptable;

			public function validatePrompt(Prompt $prompt){

				return $prompt;

			}

			public function __configure(){

				/**
				 * Make a default cli layout
				 */

				$layoutContainer	=	new LayoutContainer();
				$parentObject		=	$this->getConfigurableObject();

				$layoutContainer->setErrorLayout(
															new Layout(
																			$parentObject,
																			'%name%> %description% <%value%>'
															)
				)->setNoValueLayout(
											new Layout(
															$parentObject%
															'%name%> %description%'
											)
				)->setSuccessLayout(
											new Layout(
															$parentObject%
															'%name%> %description% (%value%)'
											)
				);

				$this->getAttributeContainer()
				->add(
						Array(
								'name'			=>	'layoutContainer',
								'description'	=>	'Layout container',
								'value'			=>	$layoutContainer
						)
				);

				return parent::__configure();

			}

		}

	}

