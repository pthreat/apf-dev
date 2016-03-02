<?php

	namespace apf\iface\ui\form{

		use \apf\iface\ui\form\element\Layout		as	ElementLayoutInterface;
		use \apf\iface\ui\form\element\Attribute	as	ElementAttributeInterface;

		interface Element{

			public function __construct($attrName,$description,Array $layouts=Array());
			public function setNoValueLayout(ElementLayoutInterface $layout);
			public function getNoValueLayout();
			public function setValueLayout(ElementLayoutInterface $layout);
			public function getValueLayout();
			public function setErrorLayout(ElementLayoutInterface $layout);
			public function getErrorLayout();
			public function setName($name);
			public function getName();
			public function setDescription($description);
			public function getDescription();
			public function onSetValue(Callable $callback);
			public function setValue($value);
			public function getValue();
			public function addAttribute(ElementAttributeInterface $attribute);
			public function getAttributes();
			public function setAttributes(Array $attributes);
			public function __toString();

		}

	}
