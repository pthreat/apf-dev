<?php

	namespace apf\ui\form\cli{

		use \apf\ui\form\Layout;
		use \apf\ui\form\cli\base\Layout		as	BaseCliLayout;
		use \apf\ui\form\cli\layout\Parser	as	CliLayoutParser;
		use \apf\ui\layout\Parser				as	LayoutParser;

		class Layout extends BaseCliLayout{

			public function __construct(FormInterface	&$form,LayoutParser $parser=NULL){

				if($parser===NULL){

					$parser	=	new CliLayoutParser($form,"[title]\n[elements]\n[prompt]");

				}

				parent::setParser($parser);

			}

		}

	}

