<?php

	namespace apf\web{

		use \apf\core\Configurable;
		use \apf\core\Directory						as	Dir;

		abstract class Asset extends Configurable{

			public function isLocal(){

				return is_file(parent::getConfig()->getURI());

			}

			public function isRemote(){

				return !$this->isLocal();

			}

			public function minify(){

				return StringUtil::minify(file_get_contents($this->getContents()));

			}

			public function downloadIn(Dir $dir){

				if($this->isLocal()){

					throw new \LogicException("Can not download a local asset");

				}
				
			}

			public function copyTo(Dir $dir){
			}

			public function requires(Asset $asset){

				$this->requires[$asset->getName()]	=	$asset;
				return $this;

			}

			public function getRequirements(){

				return $this->requires;

			}

			public function __toString(){

				return sprintf('%s',parent::getConfig()->getURI());

			}

		}

	}
