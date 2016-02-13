<?php

	namespace apf\traits\config{

		use \apf\core\project\DocumentRoot;
	
		trait DocumentRootable{

			public function setDocumentRoot(DocumentRoot $documentRoot){

				$this->documentRoot	=	$documentRoot;
				return $this;

			}

			public function getDocumentRoot(){

				return parent::getDocumentRoot();

			}

		}	

	}
	
