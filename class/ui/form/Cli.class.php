<?php

	namespace apf\ui\form{

		use \apf\ui\Form;
		use \apf\ui\form\cli\element\Prompt;

		class Cli extends Form{

			public function configure(){
			}

			public function render(){

				do{

					try{

						echo $this->getTitle();

						foreach($this->getElements() as $element){

							echo sprintf('%s) %s (%s)',$element->getName(),$element->getDescription(),$element->getValue());

						}

						echo "\n";
						$prompt	=	new Prompt();
						$this->getElementByName($prompt->read())->setValue();

					}catch(\Exception $e){

						echo $e->getMessage();
						(new Prompt())->setText('Press enter to continue ...')->read();

					}



				}while(TRUE);

			}

		}

	}

