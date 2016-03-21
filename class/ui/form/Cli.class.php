<?php

	namespace apf\ui\form{

		use \apf\ui\Form;
		use \apf\ui\form\cli\element\Prompt;

		class Cli extends Form{

			public function render(){

				do{

					try{

						echo $this->getTitle();

						foreach($this->getElements() as $element){

							echo sprintf('%s%s',$element->getValue()->render(),"\n");

						}

						//The prompt is part of the form, we should be able to get it
						//$this->getPrompt->read();

						$prompt	=	new Prompt();
						$element	=	$this->getElementByName($prompt->read());



					}catch(\Exception $e){

						echo $e->getMessage();
						echo $e->getTraceAsString()."\n";
						(new Prompt())->setText('Press enter to continue ...')->read();

					}

				}while(TRUE);

			}

		}

	}

