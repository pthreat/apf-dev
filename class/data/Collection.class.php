<?php

	namespace oauth\common\collection{

		use oauth\common\iface\Filter as FilterInterface;

		class Base{

			protected $filter = NULL;

			public function __construct(FilterInterface $filter=NULL){

				if(is_null($filter)){

					$filter	=	new Filter();

				}

				$this->filter	=	$filter;

			}

			protected function parseWhere(Array $where){

				if(!sizeof($where)){
					return Array();
				}

				$w	=	Array();

				foreach($where as $wh){

					$w[]	=	$wh;
					$w[]	=	Array(
										"operator"=>"AND"
					);

				}

				unset($w[sizeof($w)-1]);

				return $w;

			}

		}

	}

?>
