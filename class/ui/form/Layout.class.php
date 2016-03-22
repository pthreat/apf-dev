<?php

	namespace apf\ui\form{

		use \apf\iface\ui\Form			as	FormInterface;
		use \apf\iface\ui\form\Layout	as	FormLayoutInterface;
		use \apf\ui\Layout				as	BaseLayout;

		abstract class Layout extends BaseLayout implements FormLayoutInterface{

			private $form	=	NULL;

			public function __construct(FormInterface &$form,LayoutParser $parser=NULL){

				$this->setForm($form);

				if($format !== NULL){

					parent::setFormat($format);

				}

			}

			public function setForm(FormInterface &$form){

				$this->form	=	$form;
				return $this;

			}

			public function getForm(){

				return $this->form;

			}

		}

	}
