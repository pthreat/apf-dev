<?php

	namespace apf\ui\form\cli\element{

		use \apf\console\Ansi;
		use \apf\ui\form\element\Layout	as	BaseLayout;
		use \apf\iface\ui\form\Element	as	ElementInterface;

		class Layout extends BaseLayout{

			public function __construct(ElementInterface &$element,$format=NULL){

				if($format===NULL){

					$format	=	'[name:{"color":"light_cyan","format":"%s>"}] [description:{"color":"green"}] [value:{"color":"yellow","format":"(%s)"}]';

				}

				parent::__construct($element,$format);

			}


		}

	}
