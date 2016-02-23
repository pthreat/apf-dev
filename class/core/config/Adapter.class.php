<?php

	namespace apf\core\config{

		use \apf\core\File;

		class Adapter{

			private	$file	=	NULL;

			final public function __construct(File $file=NULL){

				if($file !== NULL){

					$this->setFile($file);

				}

			}

			public function setFile(File $file){

				$this->file	=	$file;
				return $this;

			}

			public function getFile(){

				return $this->file;

			}

			public static function factory(File $file){

				$configFile	=	new File(sprintf('%s',$file));
				$adapter		=	sprintf('\apf\core\config\adapter\%s',ucwords($configFile->getExtension()));

				return new $adapter($configFile);

			}

		}

	}
