<?php

	namespace apf\iface\ui{

		use \apf\iface\ui\layout\Parser	as	LayoutParserInterface;

		interface Layout{

			public function setParser(LayoutParserInterface $parser);
			public function getParser();
			public function render();
			public function __toString();

		}

	}
