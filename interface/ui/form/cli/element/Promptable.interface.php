<?php

	namespace apf\iface\ui\form\cli\element{

		use \apf\ui\form\cli\element\Prompt;

		interface Promptable{

			public function setPrompt(Prompt $prompt);
			public function getPrompt();

		}	

	}
