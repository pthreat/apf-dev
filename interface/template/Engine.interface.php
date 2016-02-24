<?php

	namespace apf\iface\template{

		use \apf\core\File;

		interface Engine{

			public function render();
			public function parse(File $template);

		}

	}
