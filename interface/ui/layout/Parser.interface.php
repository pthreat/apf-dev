<?php

	namespace apf\iface\ui\layout{

		interface Parser{

			public function setConfigurableObject($object);
			public function getConfigurableObject();
			public function setFormat($format);
			public function getFormat();
			public function setVarOpeningCharacter($char);
			public function getVarOpeningCharacter();
			public function setVarClosingCharacter($char);
			public function getVarClosingCharacter();
			public function parse();

		}

	}
