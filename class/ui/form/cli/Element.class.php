<?php

	namespace apf\ui\form\cli{

		use \apf\console\Ansi;
		use \apf\ui\form\Element								as	BaseElement;
		use \apf\ui\form\cli\element\Layout					as	ElementLayout;
		use \apf\iface\ui\form\cli\element\Promptable	as	PromptableInterface;

		abstract class Element extends BaseElement implements PromptableInterface{

			use \apf\traits\ui\form\cli\element\Promptable;

			public function __construct($attrName,$description,Array $layouts=Array()){

				if(!array_key_exists('noval',$layouts)){

					$layouts['noval']	=	new ElementLayout($this,'[name:{"color":"light_cyan"}]> [description]');

				}

				if(!array_key_exists('success',$layouts)){

					$layouts['success']	=	new ElementLayout($this,'[name:{"color":"light_green"}]> [description] [value:{"format":"(%s)"}]');

				}

				if(!array_key_exists('error',$layouts)){

					$layouts['error']	=	new ElementLayout($this,'[name:{"color":"red"}]> [description] [value:{"format":"<%s>"}]');

				}

				parent::__construct($attrName,$description,$layouts);

			}

			public function setValue($value=NULL){

				do{

					parent::setValue($this->getPrompt()->read($value));

					if($this->getValueState()=='error'){

						echo $e->getMessage();

						(new Prompt())
						->setText('Press enter to continue ...')
						->read();

					}


				}while($this->getValueState()!=='success');

			}

		}		

	}

