<?php

	namespace apf\generator{

		use apf\iface\database\Table;
		use apf\util\String								as StringUtil;
		use apf\generator\code\Namespace_			as NamespaceBlock;
		use apf\generator\code\Class_					as ClassBlock;
		use apf\generator\code\func\Parameter;
		use apf\generator\code\class_\Attribute;
		use apf\generator\code\class_\Method;
		use apf\generator\Code;

		class Data{

			private	$table		=	NULL;
			private	$namespace	=	Array();
			private	$className	=	Array();

			public function __construct(Table $table,Array $namespace=Array(),$className=NULL){

				$this->table		=	$table;

				$this->setClassName(empty($className) ? $table->getName($includeSchema=FALSE) : $className);
				$this->setNamespace(empty($namespace) ? $table->getSchema() : $namespace);

			}

			public function setNamespace($namespace){

				$this->namespace	=	$namespace;
				return $this;

			}

			public function getNamespace(){

				return $this->namespace;

			}

			public function setClassName($name){

				$this->className	=	StringUtil::toCamelCase($name);
				return $this;

			}

			public function getClassName(){

				return $this->className;

			}

			public function exportAsModel(Array $namespace=Array(),$className=NULL){

				if(empty($namespace)){

					$namespace		=	$this->namespace;
					$namespace[]	=	'model';

				}

				$className	=	trim($className);
				$className	=	empty($className)	?	$this->className	:	$className;

				$classObj	=	new ClassBlock($className);
				$classObj->setExtendsTo("BaseModel");

				foreach($this->table->getFields() as $field){

					$scope				=	'private';
					$name					=	StringUtil::toCamelCase($field->name);
					$upperCamelName	=	StringUtil::toUpperCamelCase($name);
					$default				=	'NULL';
					$hasDefault			=	FALSE;

					if($field->nullable == 'YES' && $field->defaultValue === ''){
					
						$hasDefault	=	FALSE;

					}elseif($field->nullable == 'NO' && $field->defaultValue != ''){

						$hasDefault	=	TRUE;
						$default		=	$field->defaultValue;

					}

					$classObj->addAttribute(

									new Attribute($scope,$name,$hasDefault,$default)
					)
					->addMethod(
									(new Method("set$upperCamelName"))
									->addParameter(
														new Parameter($name)
									)
									->l(sprintf('$this->%s = $%s;',$name,$name))
									->l('return $this;')
					)
					->addMethod(
									(new Method("get$upperCamelName"))
									->l(sprintf('return $this->%s;',$name))
					);

				}

				$nsObj		=	(new NamespaceBlock($namespace));
				$nsObj->addAlias("\\apf\\data\\Model","BaseModel");
				$nsObj->addClass($classObj);

				return $nsObj;

			}

			public function exportMockup(Array $namespace,$className,Array $parentClass=Array()){

				$className	=	StringUtil::toUpperCamelCase($this->table->getName());

				$nsObj		=	new NamespaceBlock($namespace);
				$classObj	=	new ClassBlock($className);

				if($parentClass){

					$nsObj->addAlias(implode('\\',$parentClass,'Base'));
					$classObj->setExtendsTo('Base');

				}

				$nsObj->addClass($classObj);

				return $nsObj;

			}

			public function exportAsClass(Array $namespace=Array(),$className=NULL){

				if(empty($namespace)){

					$namespace	=	Array(
												$this->namespace,
												'table'
					);

				}

				$fieldsArray	=	Array();

				foreach($this->table->getFields()->toArray() as $f){

					$fieldsArray[]	=	preg_replace("/\n/",'',var_export($f->toArray(),TRUE));

				}

				$classObj	=	new ClassBlock(empty($className)	?	$this->className	:	$className);

				$classObj->l("const TABLE = {$this->table->getName()};")
				->addAttribute(
												new Attribute('protected','__tColumns',$hasDefault=TRUE,sprintf('Array(%s);',implode('\\',$fieldsArray)))
				)
				->addMethod(
								(new Method('getColumns'))
								->l('return $this->__tColumns')

				);

				$nsObj		=	new NamespaceBlock($namespace);
				$nsObj->addClass($classObj);

				return $nsObj;

			}

			public function exportAsFactory(Array $namespace = Array(),$className=NULL){

				if(empty($namespace)){

					$namespace	=	Array(
												$this->namespace,
												'table'
					);

				}

				$nsObj		=	new NamespaceBlock($namespace);
				$nsObj->addAlias('\\apf\\data\\Factory','Factory');

				$className	=	StringUtil::toUpperCamelCase(empty($className)	?	$this->className	:	$className);

				$classObj	=	new ClassBlock($className);
				$classObj->setExtendsTo('Factory');

				$schema		=	StringUtil::toCamelCase($this->table->getSchema());

				$nsObj->addAlias("\\{$this->namespace}\\filter\\$className",'Filter');
				$nsObj->addAlias("\\{$this->namespace}\\collection\\$className",'Collection');

				foreach($this->table->getFields() as $field){

					$methodName	=	sprintf('getInstanceBy%s',StringUtil::toUpperCamelCase($field->name));

					$method		=	(new Method($methodName))
					->addParameter(
										new Parameter('value')
					)
					->addParameter(
										(new Parameter('filter',$default=TRUE,'NULL'))
										->setTypeHint('Filter')
					)
					->l('$filter = parent::getFilterInstance($filter);')
					->l('return (new Collection($filter))->fetch();')
					->setIsStatic(TRUE);

					$classObj->addMethod($method);

				}

				$nsObj->addClass($classObj);

				return $nsObj;

			}

			public function exportAsForm(Array $namespace = Array(),$className=NULL){

				if(empty($namespace)){

					$namespace	=	Array(
												$this->namespace,
												'form'
					);

				}

				$nsObj		=	new NamespaceBlock($namespace);

				$className	=	StringUtil::toUpperCamelCase(empty($className)	?	$this->className	:	$className);
				$classObj	=	new ClassBlock($className);
				$classObj->setExtendsTo('Form');
				$nsObj->addAlias("\\{$this->table->getSchema()}\\model\\$className",'Model');
				$nsObj->addAlias("\\apf\data\Form");
				$nsObj->addClass($classObj);

				return $nsObj;

			}

			public function exportAsCollection(Array $namespace=Array(),$className=NULL){

				$filterNamespace	=	Array(
												StringUtil::toCamelCase($this->table->getSchema()),
												'filter'				
				);

				if(empty($namespace)){

					$namespace	=	Array(
												$this->namespace,
												'form'
					);

				}

				$nsObj		=	new NamespaceBlock($namespace);

				$className	=	trim($className);
				$className	=	empty($className)	?	$this->className	:	$className;
				$classObj	=	new ClassBlock($className);

				$filterNS	=	implode("\\",$filterNamespace);

				$nsObj->addAlias('\\apf\\data\\Collection');

				$classObj->setExtendsTo('Collection')
				->l("const FILTER = '$filterNS';");

				$nsObj->addClass($classObj);

				return $nsObj;

			}

			public function exportAsFilter(Array $modelNamespace,Array $namespace=Array(),$className=NULL){

				if(empty($namespace)){

					$namespace	=	Array(
												$this->namespace,
												'filter'
					);

				}

				$className	=	trim($className);
				$className	=	empty($className)	?	$this->className	:	$className;
				$namespace	=	implode("\\",$namespace);
				$modelNS		=	implode("\\",$modelNamespace);

				$mapTo		=	"\\$modelNS\\$className";

				$nsObj		=	new NamespaceBlock($namespace);
				$classObj	=	new ClassBlock($className);

				$nsObj->addAlias('\\apf\\iface\\data\\Filter','FilterInterface');
				$nsObj->addAlias($mapTo,'BaseFilter');

				$classObj->setExtendsTo('BaseFilter')
				->addInterface('FilterInterface')
				->addTrait('\\apf\\traits\\data\\Filter');

				$columns			=	Array();
				$textFields		=	Array();

				foreach($this->table->getFields() as $field){

					if(in_array($field->type,Array('text','char','varchar'))){
						
						$classObj->addAttribute(
														new Attribute('private',sprintf('%sLike',StringUtil::toCamelCase($field->name)))
						)
						->addMethod(
										(new Method(sprintf('set%sLike',StringUtil::toUpperCamelCase($field->name))))
										->l(sprintf('$this->%sLike = $like;',StringUtil::toCamelCase($field->name)))
										->l('return $this;')
						)
						->addMethod(
										(new Method(sprintf('get%sLike',StringUtil::toUpperCamelCase($field->name))))
										->l(sprintf('return $this->%sLike = $like;',StringUtil::toCamelCase($field->name)))
						);

					}

				}

				$classObj->addMethod(
											(new Method('getMap'))
											->l("return Array('class'=>'$mapTo')")
				);

				$nsObj->addClass($classObj);

				return $nsObj;

			}

		}

	}

