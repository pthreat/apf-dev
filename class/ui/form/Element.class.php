<?php

	namespace apf\ui\form{

		use \apf\core\Configurable;
		use \apf\iface\ui\form\Element	as	ElementInterface;

		abstract class Element extends Configurable implements ElementInterface{

			/**
			 * According to different element states, the element is rendered in different ways,
			 *
			 * If the element has no value, i.e: no state has been set for the given element, it will use the NoValueLayout
			 * If the element has a value and this value is correct, the ValueLayout will be used
			 * If the element has been assigned with an incorrect value, the ErrorLayout will be used.
          *
			 * All mentioned layouts can be found in the LayoutContainer object assigned to said element.
			 *
			 */

			public function render(){

				switch($this->getConfig()->getValueState()){

					case 'noval':		//When the form element has not been assigned a value

						return $this->getConfig()->getLayoutContainer()->getNoValueLayout()->render();

					break;

					case 'success':	//When the form element has been assigned a correct value

						return $this->getConfig()->getLayoutContainer()->getValueLayout()->render();

					break;

					case 'error':		//When the form element has been assigned an incorrect value

						return $this->getConfig()->getLayoutContainer()->getErrorLayout()->render();

					break;

				}

				/**
				 * This should not happen due to the valueState validator in the element configuration class
				 */

				throw new \InvalidArgumentException(sprintf('Invalid value state ->%s<-',$this->getConfig()->getValueState()));

			}

			public function __toString(){

				try{

					/**
					 * Attempt to render the element
					 */

					return $this->render();	

				}catch(\Exception $e){

					/**
					 * If any error is found during rendering, output the error, __toString must not throw exceptions
					 */

					return "Error: {$e->getMessage()}";

				}

			}

		}

	}

