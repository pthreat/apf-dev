<?php

	namespace apf\iface\database{

		interface Table{

				public function getName($includeSchema=TRUE);
				public function getSchema();

		}

	}
