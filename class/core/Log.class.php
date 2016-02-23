<?php

	namespace apf\core {

		use \apf\iface\Log		as	LogInterface;
		use \apf\console\Ansi;

		class Log implements LogInterface{

			/**
			 * @var $colors
			 * @see Log::enableColors($boolean)
			 */

			private $colors	=	TRUE;

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

				$this->enableColors(array_key_exists('colors',$parameters)	?	$parameters['colors']	:	$this->colors);
				$this->enableStdout(array_key_exists('stdout',$parameters)	?	$parameters['stdout']	:	$this->stdout);
				$this->useLogDate(array_key_exists('logDate',$parameters)	?	$parameters['logDate']	:	$this->useLogDate);

			}

			public function hasColoring(){

				return (boolean)$this->colors;

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

			public function repeat($string,$times,$type=0,$color=NULL){

				return $this->log(str_repeat($string,$times),$type,$color);

			}

			public function logArray(Array $array,$separator=',',$color=NULL,$type=0){

				return $this->log(implode($separator,$array),$type,$color);

			}

			public function log($msg=NULL,$type=0,$color=NULL){

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

				$color	=	trim($color);

				if(!empty($color) && $this->colors) {

					$msg	=	$this->colors ? Ansi::colorize("$code$msg\033[37m{$this->lineCharacter}",$color)	:	"$code$msg{$this->lineCharacter}";

				} else {

					$msg	=	"$msg{$this->lineCharacter}";

				}

				if($this->stdout){

					echo $msg;	

				}

				return $msg;
			
			}

			public function reset(){

				echo Ansi::getColor('light_gray');

			}
	
			/**
			*Returns an X11 debug like tag according to the given number
			*/
	
			private function infoType($type=NULL) {
	
				switch($type) {
					case 1:
						return "[EE]";
					case 2:
						return "[WW]";
					case 3:
						return "[DD]";
					case 4:
						return "[SS]";
					case 0:
					default:
						return "[II]";
				}
	
			}

			public function debug($text=NULL){

				$this->log($text,3,"light_purple");
				return $this;

			}

			public function info($text=NULL){

				$this->log($text,0,"light_cyan");
				return $this;

			}

			public function warning($text=NULL){

				$this->log($text,2,"yellow");
				return $this;

			}

			public function error($text=NULL){

				$this->log($text,1,"light_red");
				return $this;

			}

			public function emergency($text=NULL){

				$this->log($text,1,"red");
				return $this;

			}

			public function success($text=NULL){

				$this->log($text,0,"light_green");
				return $this;
				
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
	
			public function getPrepend(){
	
				return $this->prepend;
	
			}
	
	
			/**
			* @method enableColors()  Color output (Console only)
			* @param bool $bool TRUE  Activate output coloring
			* @param bool $bool FALSE Disable output coloring
			*/
	
			public function enableColors($bool=TRUE) {

				$this->colors	=	$bool;
				return $this;

			}
	
		}

	}
