<?php

	namespace apf\ui\form\cli\element{

		use \apf\core\Config										as	BaseConfig;
		use \apf\ui\form\cli\element\Layout;
		use \apf\ui\form\element\layout\Container			as	LayoutContainer;
		use \apf\ui\form\cli\element\Prompt;
		use \apf\iface\ui\form\cli\element\Promptable	as	PromptableInterface;

		class Config extends BaseConfig implements PromptableInterface{

			use \apf\traits\ui\form\cli\element\Promptable;

			public function validatePrompt(Prompt $prompt){

				return $prompt;

			}

			public function validateLayoutContainer(LayoutContainer $container){
				return $container;
			}

			public function configure(){

				/**
				 * Make a default cli layout
				 */

				$layoutContainer	=	new LayoutContainer();

				$layoutContainer->setErrorLayout(
															new Layout(
																			$element=$this->getConfigurableObject(),
																			$format='[name:{"color":"red"}]> [description] [value:{"format":"<%s>"}]'
															)
				)->setNoValueLayout(
											new Layout(
															$element=$this->getConfigurableObject(),
															$format='[name:{"color":"light_cyan"}]> [description]'
											)
				)->setSuccessLayout(
											new Layout(
															$element=$this->getConfigurableObject(),
															$format='[name:{"color":"light_green"}]> [description] [value:{"format":"(%s)"}]'
											)
				);

				$this->getAttributeContainer()
				->add(
						Array(
								'name'			=>	'layoutContainer',
								'description'	=>	'Layout container',
								'value'			=>	$layoutContainer
						)
				)
				->add(
						Array(
								'name'			=>	'prompt',
								'description'	=>	'Element prompt'
						)
				);

			}

		}


	}
