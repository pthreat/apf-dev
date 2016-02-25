<?php

	namespace apf\core {

		use \apf\iface\Log		as	LogInterface;
		use \apf\console\Ansi;

		class Log implements LogInterface{

			const INFO			=	0;
			const ERROR			=	1;
			const WARNING		=	2;
			const DEBUG			=	3;
			const SUCCESS		=	4;
			const EMERGENCY	=	5;


			/**
			 * @var $uselogDate
			 * @see Log::useLogDate($boolean)
			 */
		
			private	$useLogDate = FALSE;


			/**
			 * @var $dateFormat
			 * @see Log::setDateFormat($format)
			 */

			private	$dateFormat	=	'[Y-m-d H:i:s]';
	
			/**
			*
			* @var $stdout print to stdout or not
			* @see self::enableStdout()
			*
			*/
	
			private $stdout	=	TRUE;
	
			/**
			*
			* @var $usePrefix 
			* @see self::setX11Info()
			*
			*/
	
			private $usePrefix = TRUE;
	
			/**
			* @var $prepend Adds a static string to every message *before* the message
			* @see Log::setPrepend()
			*/
	
			private $prepend = NULL;
	
			/**
			* @var $append Adds a static string to every message *after* the message
			* @see Log::setAppend()
			*/
	
			private	$append = NULL;

			/**
			* @var $lineCharacter it's the character that outputs at the end of a message, by default it's \n
			* @see Log::setCarriageReturnChar
			*/

			private	$lineCharacter	=	"\n";	

			public function __construct(Array $parameters=Array()){

				$this->enableStdout(array_key_exists('stdout',$parameters)	?	$parameters['stdout']	:	$this->stdout);
				$this->useLogDate(array_key_exists('logDate',$parameters)	?	$parameters['logDate']	:	$this->useLogDate);

			}

			/**
			*Specifies if date should be prepended in the log
			*@param boolean $boolean TRUE prepend date
			*@param boolean $boolean FALSE do NOT prepend date
		   */

			public function useLogDate($boolean=TRUE){

					$this->useLogDate = $boolean;

			}

			public function setNoLf(){

				$this->lineCharacter	=	'';

			}

			public function setLf(){

				$this->lineCharacter	=	"\n";

			}

			public function usePrefix(){

				$this->usePrefix	=	TRUE;

			}

			public function setNoPrefix(){

				$this->usePrefix	=	'';

			}

			public function repeat($string,$times,$type=0){

				return $this->log(str_repeat($string,$times),$type);

			}

			public function logArray(Array $array,$separator=',',$type=0){

				return $this->log(implode($separator,$array),$type);

			}

			public function log($msg=NULL,$type=0){

				if(is_null($msg)){

					throw(new \Exception("Message to be logged cant be empty"));

				}

				if(is_array($msg)){

					$msg	=	var_export($msg,TRUE);

				}
	
				$date = $this->useLogDate	?	@date($this->dateFormat)	:	NULL;
			
				$code = NULL;
	
				$type = $this->usePrefix ? sprintf('%s ',$this->infoType($type)) : '';

				$origMsg	=	$msg;	
				$msg		=	sprintf('%s%s%s%s%s',$this->prepend,$type,$date,$msg,$this->append);

				$msg		=	"$code$msg{$this->lineCharacter}";

				if($this->stdout){

					echo $msg;	

				}

				return $msg;
			
			}

			/**
			*Returns an X11 debug like tag according to the given number
			*/
	
			private function infoType($type=NULL) {
	
				switch($type) {

					case self::ERROR:
						return "[EE]";
					break;

					case self::WARNING:
						return "[WW]";
					break;

					case self::DEBUG:
						return "[DD]";
					break;

					case self::SUCCESS:
						return "[SS]";
					break;

					case self::INFO:
					default:
						return "[II]";
					break;

				}
	
			}

			public function debug($text=NULL){

				return $this->log($text,self::DEBUG);

			}

			public function info($text=NULL){

				return $this->log($text,self::INFO);

			}

			public function warning($text=NULL){

				return $this->log($text,self::WARNING);

			}

			public function error($text=NULL){

				return $this->log($text,self::ERROR);

			}

			public function success($text=NULL){

				return $this->log($text,self::SUCCESS);
				return $this;
				
			}

			public function emergency($text=NULL){

				return $this->log($text,self::EMERGENCY);

			}
	
			/**
			*@method enableStdout() 
			*@param $stdout bool TRUE output to stdout
			*@param $stdout bool FALSE Do NOT output to stdout
			*/
	
			public function enableStdout($stdout=TRUE) {
	
				$this->stdout = $stdout;
				return $this;
	
			}

			public function stdoutIsEnabled(){

				return (boolean)$this->stdout;

			}
	
			/**
			 *@method setPrepend() Prepends a string to every log message
			 *@param String The string to be prepend
			 */
	
			public function setPrepend($prepend=NULL) {
	
				$this->prepend = $prepend;
				return $this;
	
			}

			public function getPrepend(){
	
				return $this->prepend;
	
			}
	
			/**
			*@method setAppend() Adds 
			*@param string El string a posponer en el mensaje log
			*
			*/
	
			public function setAppend($append=NULL) {
	
				$this->append = $append;
				return $this;
	
			}
	
			public function getAppend(){
	
				return $this->append;
		
			}
	
		}

	}
