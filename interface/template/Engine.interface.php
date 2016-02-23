<?php

	namespace apf\iface\template{

		interface Engine{

			public function render();
			public function parse($template);

		}

	}
