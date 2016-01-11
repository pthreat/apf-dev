<?php

	namespace apf\traits{

		use \apf\core\File;

		trait Exportable{

			private	$exportAs			=	'ini';

			public function exportAs($type){

				return $this->setExportAs($type);

			}

			public function setExportAs($type){

				$this->exportAs	=	$type;
				return $this;

			}

			public function getExportAs(){

				return $this->exportAs;

			}

			public function save(File $file,$as=NULL){

				return $this->export($as)->save($file);

			}

			public function export($as=NULL){

				$as							=	empty($as) ? $this->exportAs : $as;
				$configType					=	sprintf('\\apf\\core\\config\\adapter\\%s',ucwords($as));

				if(!class_exists($configType)){

					throw new \InvalidArgumentException("Invalid configuration adapter specified");

				}

				$config						=	new $configType();

				foreach($this->toArray() as $key=>$value){

					$config->$key	=	$value;

				}

				return $config;

			}

			abstract public function toArray();

		}

	}

