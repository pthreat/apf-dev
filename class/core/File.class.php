<?php

	namespace apf\core {


		use \apf\core\Directory	as	Dir;

		class File implements \Iterator{

			private	$_dirname				=	NULL;
			private	$_file					=	NULL;
			private	$_contents				=	NULL;

			private	$_fp						=	NULL;
			private	$_line					=	NULL;
			private	$_fetchTimes			=	NULL;
			private	$_readFn					=	"fread";
			private	$_isTemporary			=	NULL;
			private	$_directoryValidated	=	FALSE;
			private	$_writeMode				=	'w';

			public function __construct($file=NULL){

				if(!is_null($file)){

					$this->setFileName($file);

				}

			}

			public static function getInstance($file){

				if($file instanceof File){

					return $file;

				}

				return new static(sprintf('%s',$file));

			}

			public function getDirectory(){

				if(!$this->_dirname){

					throw new \Exception("File name has not been set, can not determine parent directory");

				}

				return new Dir($this->_dirname);

			}

			public function getExtension(){

				return strtolower(substr($this->_file,strrpos($this->_file,'.')+1));

			}

			public function requireIt(){

				return require $this;

			}

			public function exists(){

				return file_exists($this);

			}

			public static function makeTemporary($prefix="__apf_tmpfile",$dir=NULL){

				$dir	=	is_null($dir)||!is_dir($dir)	?	sys_get_temp_dir()	:	$dir;
				$file	=	tempnam($dir,$prefix);
				$file	=	new static($file);

				$file->setTemporary(TRUE);

				return $file;

			}

			public function chunkedOutput($chunkSize=1024){

				while($line=$this->read($chunkSize)){

					echo $line;

				}

			}


			public function setTemporary($boolean){

				$this->_isTemporary	=	(boolean)$boolean;

			}

			public function isTemporary(){

				return $this->_isTemporary;

			}

			public function rewind(){

				if(!is_null($this->_fp)){
					fseek($this->_fp,0);
				}
				$this->fetch();

			}

			public function current(){

				return $this->_line;

			}

			public function key(){

				return $this->_fetchTimes;

			}

			public function read($bytes=2048){

				return $this->fetch($bytes);

			}

			public function close(){

				if(is_null($this->_fp)){

					return;

				}

				fclose($this->_fp);
				$this->_fp	=	NULL;

			}

			public function &open($mode='r',$reOpen=FALSE){

				if($reOpen){

					$this->close();

				}

				if(!is_null($this->_fp)){

					return $this->_fp;

				}

				$this->_fp	=	fopen($this,$mode);

				if(!$this->_fp){

						throw(new \Exception("Can't open file $this file with mode \"$mode\""));

				}

				return $this->_fp;

			}

			public function setReadFunction($fName=NULL){

				$fName	=	\apf\Validator::emptyString($fName);

				if(!function_exists($fName)){

					throw(new \Exception("Function provided (\"$fName\") for reading file $this does not exists!"));

				}

				$this->_readFn	=	$fName;

			}

			public function getReadFunction(){

				return $this->readFn;

			}

			public function fetch($bytes=2048){

				$this->open();

				$this->_fetchTimes++;

				if(feof($this->_fp)){

					return $this->_line	=	FALSE;

				}

				//Add possibility to use fgets (read a line) instead of fread (read n bytes)
				$fragment		=	call_user_func($this->_readFn,$this->_fp,$bytes);
				$this->_line	=	$fragment;

				return $this->_line;

			}

			public function next(){

				$this->fetch();

			}

			public function valid(){

				return (!($this->_line===FALSE));

			}

			public function __destruct(){

				try{

					if($this->_isTemporary){
						unlink($this);
					}

					$this->close();

					if(!is_null($this->_fp)){

						fclose($this->_fp);

					}

				}catch(\Exception $e){

				}

			}

			public function &getHandler(){

				return $this->_fp;

			}

			public function setContents($contents=NULL){

				$this->_contents	=	$contents;

			}


			public function setFileName($file){

				$this->_dirname = dirname($file);
				$this->_file    = basename($file);

			}

			public function delete(){

				return unlink($this->_dirname.DIRECTORY_SEPARATOR.$this->_file);

			}

			private function validateDirectory(){

				if($this->_directoryValidated){

					return;

				}

				$directory	=	$this->getDirectory();

				if(!$directory->exists()){

					$directory->create();

				}

				return $this->_directoryValidated	=	TRUE;

			}

			public function setWriteMode($mode){

				$this->_writeMode	=	$mode;
				return $this;

			}

			public function getWriteMode(){

				return $this->_writeMode;

			}

			public function write($content){

				$this->validateDirectory();

				if(!$this->_fp){


					$this->open($this->_writeMode);

				}

				return fwrite($this->_fp,$content);

			}

			public function isUsable(){

				$file	=	$this->getFile();

				if(!is_readable($file)){
					throw(new \Exception("File $file is not readable, please check your permissions!"));
				}

				if(!is_file($file)){
					throw(new \Exception("File $file is a directory!"));
				}

			}

			public function dirname(){

				return $this->_dirname;

			}

			public function basename(){

				return $this->_basename;

			}

			public function getFile(){

				return $this->_dirname.DIRECTORY_SEPARATOR.$this->_file;

			}

			public function getContents(){

				return file_get_contents($this->_dirname.DIRECTORY_SEPARATOR.$this->_file);

			}

			public function getContentsAsArray(){

				return file($this->_dirname.DIRECTORY_SEPARATOR.$this->_file);

			}

			public function __toString(){

				return $this->getFile();

			}

		}

	}

?>
