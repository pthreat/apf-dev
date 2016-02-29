<?php

	namespace apf\ui\form{

		use \apf\ui\Form;
		use \apf\ui\form\cli\element\Prompt;

		class Cli extends Form{

			private	$titleColor	=	NULL;

			public function configure(){
			}

			public function setTitleColor($color){

				$this->titleColor	=	$color;
				return $this;

			}

			public function getTitleColor(){

				return $this->titleColor;

			}

			public function render(){

				do{

					try{

						echo $this->getTitle();

						foreach($this->getElements() as $element){

							echo sprintf('%s) %s (%s)%s',$element->getName(),$element->getDescription(),$element->getValue(),"\n");

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

