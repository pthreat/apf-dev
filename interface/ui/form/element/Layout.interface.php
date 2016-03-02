<?php

	namespace apf\iface\ui\form\element{

		use \apf\iface\ui\form\Element	as	ElementInterface;

		interface Layout{

			public function __construct(ElementInterface &$element,$format=NULL);
			public function setElement(ElementInterface &$element);
			public function getElement();
			public function setFormat($format);
			public function getFormat();
			public function render();

		}

	}
