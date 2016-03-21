<?php

	namespace apf\ui\form\cli\layout{

		use \apf\ui\layout\Parser			as	BaseParser;

		class Parser extends BaseParser{

			public function parse(){

				$format	=	$this->getFormat();

				$open		=	0;
				$close	=	0;
				$len		=	strlen($format);

				//Syntax check
				for($i=0;$i<$len;$i++){

					if($format[$i]==$this->getVarOpeningCharacter()){

						$open++;

					}

					if($format[$i]==$this->getVarClosingCharacter()){

						$close++;

					}

				}

				if($open!==$close){

					throw new \Exception("Syntax error: The layout format {$format} is invalid");

				}

				$parse	=	Array();
				$temp		=	'';
				$format	=	'';

				for($i=0;$i<$len;$i++){

					$v	=	$format[$i];

					if($v==$this->varOpeningCharacter){

						$onBracket	=	TRUE;
						continue;

					}

					if($v==$this->varClosingCharacter){

						$attributes	=	Array();
						$hasColon	=	strpos($temp,':');

						if($hasColon!==FALSE){

							$attributes	=	sprintf('%s',substr($temp,$hasColon+1));
							$attributes	=	json_decode($attributes,$assoc=TRUE);

							if(!$attributes){

								throw new \InvalidArgumentException("Error parsing attributes");

							}

							if(array_key_exists('color',$attributes)){

								$format	=	sprintf('%s%s',$format,'%s');
								$parse[]	=	Ansi::getColor($attributes['color']);

							}

							$temp		=	substr($temp,0,$hasColon);

						}

						$onBracket	=	FALSE;
						$format		=	sprintf('%s%s',$format,array_key_exists('format',$attributes) ? $attributes['format'] : '%s');
						$method		=	sprintf('get%s',trim($temp));

						$parse[]		=	parent::getConfigurableObject()->getConfig()->$method();
						$temp			=	'';
						continue;

					}

					if($onBracket){

						$temp	=	sprintf('%s%s',$temp,$v);
						continue;

					}
				
					$format	=	sprintf('%s%s',$format,$v);

				}

				return vsprintf($format,$parse);

			}

		}

	}

