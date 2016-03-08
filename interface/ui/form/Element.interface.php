<?php

	namespace apf\iface\ui\form{

		use \apf\iface\ui\form\element\Layout					as	ElementLayoutInterface;
		use \apf\iface\ui\form\element\Attribute				as	ElementAttributeInterface;
		use \apf\iface\ui\form\element\attribute\Container	as	ElementAttributeContainerInterface;
		use \apf\iface\ui\form\element\layout\Container		as	ElementLayoutContainerInterface;

		interface Element{

			public function setLayoutContainer(ElementLayoutContainerInterface $container);
			public function getLayoutContainer();

			public function setAttributeContainer(ElementAttributeContainerInterface $container);
			public function getAttributeContainer();

			public function setName($name);
			public function getName();

			public function setDescription($description);
			public function getDescription();

			public function onSetValue(Callable $callback);
			public function getOnSetValue();

			public function setValue($value);
			public function getValue();

		}

	}
