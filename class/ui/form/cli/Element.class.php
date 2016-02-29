<?php

	namespace apf\ui\form\cli{

		use \apf\console\Ansi;
		use \apf\ui\form\Element					as	BaseElement;
		use \apf\ui\form\cli\element\Prompt;

		abstract class Element extends BaseElement{

			private	$prompt	=	NULL;

			private	$configuredColor		=	NULL;
			private	$notConfiguredColor	=	NULL;
			private	$errorColor				=	NULL;

			public function setConfiguredColor($color){

				$this->configuredColor	=	$color;
				return $this;

			}

			public function getConfiguredColor(){

				return $this->configuredColor;

			}

			public function setNotConfiguredColor($color){

				$this->notConfiguredColor	=	$color;
				return $this;

			}

			public function getNotConfiguredColor(){

				return $this->notConfiguredColor;

			}

			public function setErrorColor($color){

				$this->errorColor	=	$color;
				return $this;

			}

			public function getErrorColor(){

				return $this->errorColor;

			}

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

			public function setValue($value=NULL){

				do{

					try{

						parent::setValue($this->getPrompt()->read($value));
						return;

					}catch(\Exception $e){

						echo $e->getMessage();

						(new Prompt())
						->setText('Press enter to continue ...')
						->read();

					}

				}while(TRUE);

			}

		}		

	}

