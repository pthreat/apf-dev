<?php

	namespace apf\iface\ui\form\element{

		use \apf\iface\ui\form\Element				as	ElementInterface;
		use \apf\iface\ui\element\layout\Parser	as	ElementLayoutParserInterface;
		use \apf\iface\ui\Layout						as	BaseLayoutInterface;

		interface Layout extends BaseLayoutInterface{

			public function __construct(ElementInterface &$element,ElementLayoutParserInterface $parser=NULL);
			public function setElement(ElementInterface &$element);
			public function getElement();

		}

	}
