<?php

	namespace apf\ui{

		use \apf\core\Configurable;

		abstract class Form extends Configurable{

			abstract public function render();

			public function __toString(){

				return $this->render();

			}

		}

	}
