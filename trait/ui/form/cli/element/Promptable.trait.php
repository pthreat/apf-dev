<?php

	namespace apf\traits\ui\form\cli\element{

		use \apf\ui\form\cli\element\Prompt;

		trait Promptable{

			private $prompt	=	NULL;

			public function setPrompt(Prompt $prompt){

				$this->prompt	=	$prompt;
				return $this;

			}

			public function getPrompt(){

				//If a prompt has not been set, use a default Prompt
				if(!$this->prompt){

					$this->prompt	=	new Prompt();

				}

				return $this->prompt;

			}

		}

	}
