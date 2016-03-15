<?php

	namespace apf\ui\form{

		use \apf\ui\Form;
		use \apf\ui\form\cli\element\Prompt;

		class Cli extends Form{

			public function render(){

				do{

					try{

						echo $this->getTitle();

						echo get_class($this->getElement());
						die();

						foreach($this->getElement() as $element){

							echo $element->render();

						}

						$prompt	=	new Prompt();
						$this->getConfig()->getAttributeContainer()->get($prompt->read())->setValue();

					}catch(\Exception $e){

						echo $e->getMessage();
						(new Prompt())->setText('Press enter to continue ...')->read();

					}

				}while(TRUE);

			}

		}

	}

