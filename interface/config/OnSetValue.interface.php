<?php

	namespace apf\iface\config{

		interface OnSetValue{

			public function onSetValue(Callable $callback);
			public function getOnSetValue();

		}

	}
