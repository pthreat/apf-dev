<?php

	namespace apf\ui\form\cli\element{

		use \apf\console\Ansi;
		use \apf\ui\form\element\Layout	as	BaseLayout;
		use \apf\iface\ui\form\Element	as	ElementInterface;

		class Layout extends BaseLayout{

			public function __construct(ElementInterface &$element,$format=NULL){

				if($format===NULL){

					$format	=	'[name:{"color":"light_cyan","format":"%s>"}] [description:{"color":"green"}] [value:{"color":"yellow","format":"(%s)"}]';

				}

				parent::__construct($element,$format);

			}

			protected function parseFormat(){

				$open		=	0;
				$close	=	0;
				$len		=	strlen($this->format);

				//Syntax check
				for($i=0;$i<$len;$i++){

					if($this->format[$i]=='['){

						$open++;

					}

					if($this->format[$i]==']'){

						$close++;

					}

				}

				if($open!==$close){

					throw new \Exception("Syntax error: The layout format {$this->format} is invalid");

				}

				$parse	=	Array();
				$temp		=	'';
				$format	=	'';

				for($i=0;$i<$len;$i++){

					$v	=	$this->format[$i];

					if($v=='['){

						$onBracket	=	TRUE;
						continue;

					}

					if($v==']'){

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

						if(!method_exists($this->getElement(),$method)){

							throw new \InvalidArgumentException("Unknown property \"$temp\"");

						}


						$parse[]		=	$this->getElement()->$method();
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

			public function setElement(ElementInterface &$element){

				$this->element	=	$element;
				return $this;

			}

			public function getElement(){

				return $this->element;

			}

			public function render(){

				return $this->parseFormat();

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
