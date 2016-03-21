<?php

/**
 * Layout inheritance distribution mini reference ASCII diagaram
 *-----------------------------------------------------------------
 *												 
 *												 o	CliElementLayout 
 *												/
 *						BaseElementLayout	-o	WebElementLayout
 *					 o 						\
 *					/							 o GTKElementLayout
 *				   
 * BaseLayout ·
 *											 o CliFormLayout
 *					\						/
 *					 o BaseFormLayout	-o WebElementLayout
 *											\ 
 *											 o GTKElementLayout
 *					 
 *
 */


	namespace apf\ui{

		use \apf\iface\ui\Layout			as	LayoutInterface;
		use \apf\iface\ui\layout\Parser	as	LayoutParserInterface;

		abstract class Layout implements LayoutInterface{

			private	$parser	=	NULL;

			public function setParser(LayoutParserInterface $parser){

				$this->parser	=	$parser;
				return $this;

			}

			public function getParser(){

				return $this->parser;

			}

			public function render(){

				if($this->parser === NULL){

					throw new \InvalidArgumentException('No layout parser has been set, cannot render layout.');


				}

				return $this->parser->parse();

			}

			public function __toString(){

				try{

					return $this->render();

				}catch(\Exception $e){

					return sprintf('Error: %s',$e->getMessage());

				}

			}

		}

	}

