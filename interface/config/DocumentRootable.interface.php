<?php

	namespace apf\iface\config{

		use \apf\core\project\DocumentRoot;

		interface DocumentRootable{

			public function setDocumentRoot(DocumentRoot $documentRoot);
			public function getDocumentRoot();

		}

	}
