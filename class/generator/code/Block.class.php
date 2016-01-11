<?php

	namespace apf\generator\code{

		use apf\generator\Code;

		class Block extends Code{

			private		$header			=	NULL;
			private		$blockIndent	=	NULL;

			public function __construct($parameters=NULL){

				if(is_string($parameters)){

					$parameters	=	Array('header'=>$parameters);
					
				}

				if(isset($parameters[0])){

					$parameters['header']	=	$parameters;

				}

				if(is_array($parameters) && isset($parameters['header'])){

					$this->setHeader($parameters['header']);

				}

				parent::__construct($parameters);

			}

			public function setBlockIndent($indent){

					$this->blockIndent = (int)$indent;
					return $this;

			}

			public function getBlockIndent(){

				return $this->blockIndent;

			}

			public function setHeader($header,$implodeChar=''){

				$this->header	=	is_array($header) ? $header : trim($header);
				return $this;

			}

			public function getHeader(){

				return $this->header;

			}

			public function reset(){

				$this->header	=	NULL;
				return parent::reset();

			}

			public function render($indent=NULL){

				$indent			=	$indent					?	(int)$indent	:	(int)$this->indent;
				$blockIndent	=	!$this->blockIndent	?	$indent+1		:	$this->blockIndent;

				return sprintf('%s%s%s{%s%s%s%s}%s',
													"\n",
													$this->indent($indent),
													$this->header,
													"\n\n",
													parent::render($blockIndent),
													"\n\n",
													$this->indent($indent),
													"\n"
				);


			}


		}

	}
