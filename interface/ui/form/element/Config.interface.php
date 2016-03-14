<?php

	namespace apf\iface\ui\form\element{

		use \apf\iface\ui\form\element\layout\Container	as	LayoutContainer;

		interface Config{

			public function validateName($name);
			public function validateDescription($description);
			public function validateLayoutContainer(LayoutContainer $container);

		}

	}
	
