<?php

	namespace apf\iface\ui\form\element\layout{

		use \apf\iface\ui\form\element\Layout		as	ElementLayoutInterface;

		interface Container{

			public function setNoValueLayout(ElementLayoutInterface $layout);
			public function getNoValueLayout();
			public function setValueLayout(ElementLayoutInterface $layout);
			public function getValueLayout();
			public function setErrorLayout(ElementLayoutInterface $layout);
			public function getErrorLayout();

		}

	}
