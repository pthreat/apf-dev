<?php 

	namespace apf\iface\config{

		use \apf\core\Directory	as	Dir;

		interface Templateable{

			public function addTemplate($template);
			public function hasTemplate($name);
			public function removeTemplate($name);
			public function getTemplate($name);
			public function getTemplates();
			public function hasTemplates();

		}

	}
