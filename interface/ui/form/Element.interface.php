<?php

	namespace apf\iface\ui\form{

		use \apf\iface\ui\form\element\Layout				as	ElementLayoutInterface;
		use \apf\iface\ui\form\element\Attribute			as	ElementAttributeInterface;
		use \apf\iface\ui\form\element\layout\Container	as	ElementLayoutContainerInterface;

		interface Element{

			public function __construct($attrName,$description,ElementLayoutContainerInterface $container);
			public function setLayoutContainer(ElementLayoutContainerInterface $container);
			public function getLayoutContainer();
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
