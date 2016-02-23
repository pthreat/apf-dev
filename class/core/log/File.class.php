<?php

	namespace apf\core\log{

		use \apf\console\Ansi;
		use \apf\core\Log;
		use \apf\core\File	as	FSFile;
		use \apf\iface\Log	as	LogInterface;

		class File extends Log{
	
			/**
			 * @var $file String name of log file
			 * @see Log::setFilename($filename)
			 */
	
			private  $file				=	NULL;
			private	$fileColoring	=	FALSE;
			private	$stdout			=	FALSE;

			public function __construct(Array $parameters=Array()){

				if(!array_key_exists('file',$parameters)){

					throw new \InvalidArgumentException("No file specified, can not log without a log file!");

				}

				$this->setFile(FSFile::getInstance($parameters['file']));
				$this->enableFileColoring(array_key_exists('fileColoring',$parameters) ? $parameters['fileColoring'] : $this->colors);
				$this->enableStdout(array_key_exists('stdout',$parameters) ? $parameters['stdout'] : $this->stdout);

				parent::__construct($parameters);

				parent::enableStdout(FALSE);

			}

			public function enableFileColoring($boolean){

				$this->fileColoring	=	(boolean)$boolean;
				return $this;

			}

			public function enableStdout($boolean){

				$this->stdout	=	(boolean)$boolean;
				return $this;

			}

			public function setFile(FSFile $file){

				$this->file	=	$file;
				return $this;

			}

			public function getFile(){

				return $this->file;

			}

			public function log($msg=NULL,$type=0,$color=NULL){

				//Pure message, no colors
				$msg	=	parent::log($msg,$type);

				if($this->stdout){

					echo (parent::hasColoring()&&!empty($color)) ? Ansi::colorize($msg,$color) : $msg;

				}

				$this->file->write( (!empty($color) && $this->fileColoring) ? Ansi::colorize($msg,$color) : $msg );

				return $msg;

			}

			//Truncate log file
			public function reset(){

				return $this->file->truncate();

			}

			/**
			* @method endLog() closes pointer to created file
			*/

			public function endLog() {

				if(!is_null($this->file)){

					$this->file->close();

				}
	
			}

			public function __destruct(){

				$this->repeat('-',120);
				$this->endLog();

			}

		}

	}
