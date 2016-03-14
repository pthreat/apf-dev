<?php

	namespace apf\ui\form{

		use \apf\core\Configurable;
		use \apf\iface\ui\form\Element	as	ElementInterface;

		abstract class Element extends Configurable implements ElementInterface{

			/**
			 * According to different element states, the element is rendered in different ways,
			 * If the element has no value, i.e: no state has been set for the given element, it will use the NoValueLayout
			 * If the element has a value and this value is correct, the ValueLayout will be used
			 * If the element has been assigned with an incorrect value, the ErrorLayout will be used.
          *
			 * All mentioned layouts can be found in the LayoutContainer assigned to said element.
			 *
			 */

			public function render(){

				switch($this->getConfig()->getValueState()){

					case 'noval':
						return $this->getConfig()->getLayoutContainer()->getNoValueLayout()->render();
					break;

					case 'success':
						return $this->getConfig()->getLayoutContainer()->getValueLayout()->render();
					break;

					case 'error':
						return $this->getConfig()->getLayoutContainer()->getErrorLayout()->render();
					break;

				}

			}

			public function __toString(){

				try{

					return $this->render();

				}catch(\Exception $e){

					return "Error: {$e->getMessage()}";

				}

			}

		}

	}

