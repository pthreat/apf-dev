<?php

	namespace apf\type {

		class String {

			use \apf\traits\Type;

			public function __construct($value){

				\apf\validate\String::mustBeString($value,$trim=TRUE);
				$this->value = sprintf('%s',$value);

			}

			public static function cast($val) {

				if(is_string($val)){

					return $val;

				}

				if(is_object($val)){

					if(Class_::hasMethod(get_class($val),'__toString')){

						return sprintf('%s',$val);

					}

				}

				if(is_numeric($val)){

					return (string)$val;

				}

				if(is_array($val)){

					return implode(',',$val);

				}

				if(is_resource($val)){

					return get_resource_type($val);

				}

				return "";

			}

		}

	}

