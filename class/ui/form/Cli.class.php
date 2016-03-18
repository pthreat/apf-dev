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

							var_dump(get_class($element->getValue()));

						}
						die();

						$prompt	=	new Prompt();
						$this->getConfig()->getAttributeContainer()->get($prompt->read())->setValue();

					}catch(\Exception $e){

						echo $e->getMessage();
						echo $e->getTraceAsString()."\n";
						(new Prompt())->setText('Press enter to continue ...')->read();

					}

				}while(TRUE);

			}

		}

	}

