<?php

	namespace apf\iface\config\web{

		use \apf\web\Asset;

		interface Assetable{

			public function addAsset(Asset $asset);
			public function getAsset($type,$name);
			public function hasAsset($type,$name);

		}

	}
