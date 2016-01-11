<?php

	namespace apf\core{

		class Directory{

			private	$_directory	=	NULL;

			public function __construct($directory){

				$this->setDirectory($directory);

			}

			public function createTree(Array $directories,Log $log=NULL){

				foreach($directories as $dir){

					$dir	=	new static(sprintf("%s%s%s",$this->_directory,DIRECTORY_SEPARATOR,$dir));

					if(!$dir->isDir()){

						if(!is_null($log)){

							$log->debug("Creating directory $dir");

						}

						$dir->create();
						continue;

					}

					if(!is_null($log)){

						$log->debug("Directory $dir already exists");

					}

				}

			}

			public function chdir(){

				if(!$this->isDir()){

					throw new \RuntimeException("Directory \"$this\" does not exists, can not chdir");

				}

				chdir($this);

				return $this;

			}

			public function setDirectory($directory){
		
				$this->_directory	=	$directory;

			}

			public function getDirectory(){

				return $this->_directory;

			}

			public function isDir(){

				return is_dir($this->_directory);

			}

			public function create($mode=0755){

				if(!@mkdir($this->_directory,$mode,$recursive=TRUE)){

					throw new \RuntimeException("Could not create directory {$this->_directory}");

				}

				return $this;

			}

			public function toArray(){

				return $this->getFilesAsArray();

			}

			public function getFilesAsArray(){

				$directoryIterator	=	new \DirectoryIterator($this->_directory);

				$files	=	Array();

				foreach ($directoryIterator as $fileInfo){

					if($fileInfo->isDot()){
						continue;
					}

					$files[]	=	$fileInfo->getFileName();

				}

				return $files;

			}

			public function __toString(){

				return sprintf('%s',$this->_directory);

			}

		}

	}
