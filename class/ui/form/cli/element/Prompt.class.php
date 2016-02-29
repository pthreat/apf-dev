<?php

	namespace apf\ui\form\cli\element{

		class Prompt{

			private	$buffer	=	NULL;
			private	$text		=	NULL;
			private	$trim		=	TRUE;
			private	$color	=	NULL;

			public function __construct($text='>',$buffer=1024,$color=NULL){

				$this->setText($text);
				$this->setBuffer($buffer);
	

			}

			public function setText($text){

				$this->text	=	$text;
				return $this;

			}

			public function getText(){

				return $this->text;

			}

			public function setColor($color){

				$this->color	=	$color;
				return $this;

			}

			public function getColor(){

				return $this->color;

			}


			public function setBuffer($buffer){

				$this->buffer	=	(int)$buffer;
				return $this;

			}

			public function getBuffer(){

				return $this->buffer;

			}

			public function render(){

				return sprintf('%s',$this->text);


			}

			public function read($default=NULL){

				if($default !== NULL){

					echo $default;	

				}

				echo $this->render();

				$fp	=	fopen("php://stdin",'r');
				$ret	=	fgets($fp,$this->buffer);
				fclose($fp);

				return $this->trim	?	trim($ret)	:	$ret;

			}

			public function __toString(){

				return $this->render();

			}

		}

	}
