<?php 

	namespace apf\iface\config{

		use \apf\core\Directory	as	Dir;

		interface Fragmentable{

			public function addFragment($fragment);
			public function hasFragment($name);
			public function removeFragment($name);
			public function getFragment($name);
			public function getFragments();
			public function hasFragments();

		}

	}
