<?php

	namespace apf\db\adapter\mysql5\adapter{

		use \apf\db\adapter\Config	as	BaseAdapterConfig;

		class Config extends BaseAdapterConfig{

			public function getNonExportableAttributes(){
				return Array();
			}

		}

	}
