<?php

	namespace apf\type{

		class Vector extends \ArrayObject{

			use \apf\traits\Type;

			public function __construct(Array $value=Array(),$cast=FALSE){

				$this->value	=	$value;
				parent::__construct($this->value);

			}

			public static function cast($val){

				return (Array)$val;

			}

			public function __toString(){

				return implode(',',$this->value);

			}

			public static function toObject($array){

				if(is_array($array)){

					return (object)array_map(sprintf('%s::%s',__CLASS__,__FUNCTION__),$array);

				}

				return $array;

			}

		}

	}

?>
