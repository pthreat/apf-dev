<?php

	namespace apf\iface\ui\form{

		use \apf\iface\ui\Form						as	FormInterface;
		use \apf\iface\ui\form\layout\Parser	as	FormLayoutParserInterface;
		use \apf\iface\ui\Layout					as	BaseLayoutInterface;

		interface Layout extends BaseLayoutInterface{

			public function __construct(FormInterface	&$form,FormLayoutParserInterface $parser=NULL);
			public function setForm(FormInterface &$form);
			public function getForm();

		}

	}
