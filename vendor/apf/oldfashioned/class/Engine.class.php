<?php

	namespace oldfashioned{
	
		use \apf\core\Cmd;
		use \apf\core\Log;
		use \apf\core\File;
		use \apf\iface\template\Engine	as	EngineInterface;

		class Engine implements EngineInterface{

			private $_templates	=	Array();

			use \apf\traits\Logable;

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

					$closingBracePosition	=	strrpos($line,'}');

					if($closingBracePosition===FALSE){

						throw new \Exception("Unmatched closing brace found at line $number");

					}

					$match		=	trim(substr($line,$openBracePosition,$closingBracePosition+1));

					$line			=	trim(substr($line,$closingBracePosition+1));

					if(empty($match)){

						continue;

					}

					$match	=	json_decode($match,$assoc=TRUE);

					if(empty($match)){
						continue;
					}

					$type		=	key($match);
					$matches[]	=	array_merge(Array('type'=>$type),$match[$type]);

				}while($line);

				return $matches;

			}

			public function parse(File $template){

				$template->setReadFunction('fgets');

				$matches		=	Array();	
			
				foreach($template as $number=>$line){

					$match	=	$this->parseLine($line,$number);

					if(!$match){

						$this->log->log($line);
						continue;

					}

					$matches[]	=	$match;

				}

				return $matches;

			}

			public function render(){

				if($this->log === NULL){

					$this->setLog(new Log());

				}

				foreach($this->_templates as $template){

					$template			=	new File($template);

					$varsInTemplate	=	$this->parse($template);

					if(empty($varsInTemplate)){

						foreach($template as $line){

							$this->log->log($line);

						}

						continue;

					}

					foreach($varsInTemplate as $vars){

						foreach($vars as $var){

							$arguments				=	is_array($var['arguments']) ? $var['arguments']	:	Array($var['arguments']);

							$name	=	$var['name'];
							$type	=	$var['type'];

							unset($var['name']);
							unset($var['type']);

							$arguments[]			=	$this->getLog();

							switch($type){

								case 'input':

									if(sizeof($arguments)>2){

										throw new \Exception("Extra arguments found at line $var[line]");

									}

									$this->$name	= call_user_func_array('\apf\core\Cmd::readInput',$arguments);

								break;

								case 'select':

									$this->$var['name']	= call_user_func_array('\apf\core\Cmd::select',$arguments);

								break;

								case 'decision':
								break;
									  
					 		}

						}

					}

				}

			}

		}

	}
