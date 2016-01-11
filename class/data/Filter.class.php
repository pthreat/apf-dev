<?php

namespace oauth\common\filter {

    abstract class Base{

        /**
         * @var null
         */
        private	$limit		=	NULL;
        /**
         * @var null
         */
        private	$offset		=	NULL;
        /**
         * @var array
         */
        private	$order		=	NULL;

        /**
         * @param array $order
         * @return $this
         */
        public function setOrder(Array $order){

            $this->order	=	$order;

            return $this;
        }

        /**
         * @return array
         */
        public function getOrder(){

            return $this->order;
        }

        /**
         * @param $num
         * @return $this
         */
        public function setLimit($num){

            $this->limit  =   $num;

            return $this;
        }

        /**
         * @return $limit
         */
        public function getLimit(){

            return $this->limit;

        }

        /**
         * @param null $offset
         */
        public function setOffset($offset=NULL){

            $this->offset	=	$offset;
        }

        /**
         * @return null
         */
        public function getOffset(){

            return $this->offset;
        }

    }

}

