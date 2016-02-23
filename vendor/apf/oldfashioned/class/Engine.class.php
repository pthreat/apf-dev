<?php

	namespace oldfashioned{
	
		use \apf\iface\template\Engine	as	EngineInterface;
		use \apf\core\Cmd;
		use \apf\core\File;

		class Engine implements EngineInterface{

			private $_templates	=	Array();

			public function setVar($name,$value){

				$this->$name	=	$value;
				return $this;

			}

			public function getVar(){

				return $this->vars[$name];

			}

			public function addTemplate($template){

				$this->_templates[]	=	$template;

			}

			private function parseLine($line,$number){

				$matches	=	Array();

				do{

					$openBracePosition	=	strpos($line,'{');

					if($openBracePosition===FALSE){

						return $matches;

					}

					$closingBracePosition	=	strpos($line,'}');

					if($closingBracePosition===FALSE){

						throw new \Exception("Unmatched closing brace found at line $number");

					}

					$match	=	substr($line,$openBracePosition+1,$closingBracePosition-1);

					$openBracketPosition		=	strpos($match,'[');
					$closingBracketPosition	=	strpos($match,']');

					if($openBracketPosition===FALSE){

						throw new \Exception("No opening bracket found for providing any arguments at line $number");

					}

					if($closingBracketPosition===FALSE){

						throw new \Exception("No closing bracket found at line $number");

					}

					$type			=	substr($match,0,$openBracketPosition);
					$arguments	=	substr($match,$openBracketPosition+1,-1);

					$matches[]	=	Array(
												'type'		=>	$type,
												'arguments'	=>	explode(',',$arguments)
					);

					$line			=	trim(substr($line,$closingBracePosition+1));

					if(empty($line)){

						break;

					}	

				}while(TRUE);

				return $matches;

			}

			public function parse($template){

				$template	=	new File($template);
				$template->setReadFunction('fgets');

				foreach($template as $number=>$line){

					$matches	=	$this->parseLine($line,$number);
					var_dump($matches);

				}

			}

			public function render(){

				foreach($this->_templates as $template){

					$this->parse($template);

				}

			}

		}

	}

