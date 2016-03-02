<?php

	namespace apf\iface\ui\form\element{

		interface Attribute{

			public function __construct($name,$value=NULL);
			public function setName($name);
			public function getName();
			public function setValue($value);
			public function getValue();
			public function setValueWrappingCharacter($char);
			public function getValueWrappingCharacter();
			public function setNameValueSeparator($separator);
			public function getNameValueSeparator();
			public function render();

		}

	}
