<?php

	namespace apf\iface{

		interface Colorize{

			public function setSuccessColor($color);
			public function getSuccessColor();
			public function setWarningColor($color);
			public function getWarningColor();
			public function setErrorColor($color);
			public function getErrorColor();
			public function setInfoColor($color);
			public function getInfoColor();

		}

	}
