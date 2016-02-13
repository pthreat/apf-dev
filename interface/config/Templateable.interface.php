<?php 

	namespace apf\iface\config{

		use \apf\core\Directory	as	Dir;

		interface Templateable{

			public function setTemplatesDirectory(Dir $dir);
			public function getTemplatesDirectory();
			public function setFragmentsDirectory(Dir $dir);
			public function getFragmentsDirectory();

		}

	}
