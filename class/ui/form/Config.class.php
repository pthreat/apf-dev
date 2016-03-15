<?php

	namespace apf\ui\form{

		use \apf\core\Config;

		abstract class Config extends BaseConfig{

			public function configure(){

				parent::getAttributeContainer()
				->add(
						Array(
								'name'			=>	'title',
								'description'	=>	'Form title'
						)
				)
				->add(
						Array(
								'name'			=>	'action',
								'description'	=>	'Form action'
						)
				)
				->add(
						Array(
								'name'			=>	'layout',
								'description'	=>	'Form layout'
						)
				)
				->add(
						Array(
								'name'			=>	'elements',
								'description'	=>	'Elements composing this form'
						)
				);

			}

		}

	}
