<?php

	namespace apf\ui\layout{

		use \apf\iface\ui\layout\Parser	as	LayoutParserInterface;

		abstract class Parser implements LayoutParserInterface{

			private	$configurableObject	=	NULL;
			private	$format					=	NULL;
			private 	$varOpeningCharacter	=	NULL;
			private	$varClosingCharacter	=	NULL;

			public function setConfigurableObject($object){

				if(!is_a('\apf\core\Configurable',$object)){

					throw new \InvalidArgumentException('Given object for layout parser is not a configurable object');

				}

				$this->configurableObject	=	$object;

				return $this;

			}

			public function getConfigurableObject(){

				return $this->configurableObject;

			}

			public function setFormat($format){

				$tmpFmt	=	trim($format);

				if(empty($tmpFmt)){

					throw new \InvalidArgumentException("Layout format can not be empty");

				}

				$this->format	=	$format;

				return $this;

			}

			public function getFormat(){

				return $this->format;

			}

			public function setVarOpeningCharacter($char){

				$this->varOpeningCharacter	=	$char;
				return $this;

			}

			public function getVarOpeningCharacter(){

				return $this->varOpeningCharacter;

			}

			public function setVarClosingCharacter($char){

				$this->varClosingCharacter	=	$char;
				return $this;

			}

			public function getVarClosingCharacter(){

				return $this->varClosingCharacter;

			}

		}

	}
