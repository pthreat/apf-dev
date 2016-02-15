<?php

	namespace apf\iface\config{

		use \apf\core\project\module\Sub;

		interface Subable{

			public function addSub(Sub $sub);
			public function setSubs(Array $subs);
			public function getSubs();
			public function getSub($name);
			public function hasSub($name);
			public function hasSubs();

		}

	}
