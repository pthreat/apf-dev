<?php

	namespace apf\generator{

		use apf\generator\code\Block;

		class Code{

				protected	$indent	=	NULL;
				protected	$tabChar	=	"\t";
				protected	$retChar	=	"\n";
				protected	$lines	=	Array();
				private		$amount	=	0;

				public function __construct($parameters=NULL){

					$isArray	=	is_array($parameters);

					if($isArray && isset($parameters['indent'])){

							$this->setIndent($parameters['indent']);

					}

					if($isArray && isset($parameters['tabChar'])){

						$this->setTabChar($tabChar);

					}

					if($isArray && isset($parameters['retChar'])){

						$this->setReturnCharacter($retChar);

					}

				}

				public function setReturnCharacter($char){

						$this->retChar	=	$char;

						return $this;

				}

				public function getReturnCharacter(){

					return $this->retChar;

				}

				public function setTabChar($char){

					$this->tabChar	=	$char;
					return $this;

				}

				public function getTabChar(){

					return $this->tabChar;

				}

				public function setIndent($indent){

						$indent	=	(int)$indent;

						if($indent<0){
								throw new \InvalidArgumentException("Indent must be a number greater or equal to 0");
						}

						$this->indent	=	$indent;

						return $this;

				}

				public function getIndent(){

					return $this->indent;

				}

				public function addLine($line){
					$this->lines[]	=	$l;

					return $this;

				}

				public function l($line){

					if($line instanceof Block){

						$this->lines[]	=	$line===$this ? clone($line) : $line;
						return $this;

					}

					$this->lines[]	=	$line;

					return $this;

				}

				public function getLines(){

					return $this->lines;

				}

				public function render($indent=NULL){

					if(!sizeof($this->lines)){

						return '';

					}

					$indent		=	is_null($indent)	?	$this->indent	:	(int)$indent;
					$lines		=	Array();

					foreach($this->lines as $key=>$line){

						if($line instanceof Block){

							$line->setIndent($indent);
							$line->setBlockIndent($indent+1);
							$lines[]	=	sprintf('%s',$line);
							continue;

						}

						$lines[]	=	sprintf('%s%s',$this->indent($indent),$line);

					}

					return implode($this->retChar,$lines);

				}

				public function reset(){

					$this->lines	=	Array();
					return $this;

				}

				protected function indent($amount){

					$amount	=	(int)$amount;

					if($amount<=0){
							return '';
					}

					return str_repeat($this->tabChar,$amount);

				}

				public function __toString(){

					return $this->render();

				}

		}

	}

