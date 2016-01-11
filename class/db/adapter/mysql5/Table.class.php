<?php

	namespace apf\db\mysql5{

		use apf\core\File;
		use apf\util\String as StringUtil;
		use apf\iface\database\Table	as	TableInterface;

		class Table implements TableInterface{

			protected	$schema	=	NULL;
			protected	$name		=	NULL;
			protected	$fields	=	Array();
			protected	$params	=	NULL;
			protected	$struct	=	NULL;

			public function __construct($name=NULL,$params=NULL){

				$this->params	=	$params;

				if(!is_null($name)){

					$this->setName($name);

				}

			}

			public static function getInstance($name){

				return new static($name);

			}

			public function exportAsModel(Array $namespace=Array()){

				if(empty($namespace)){

					$namespace	=	Array(
												StringUtil::toCamelCase($this->getSchema()),
												'model'
					);

				}


				$className	=	StringUtil::toUpperCamelCase($this->name);

				$class		=	sprintf('%snamespace %s{',"\t",implode("\\",$namespace));
				$class		=	"$class\n\n\t\tuse\t\\apf\\data\\Model as BaseModel;\n\n";
				$class		=	"$class\t\tclass $className extends BaseModel{\n\n";

				$columns		=	Array();

				foreach($this->getFields() as $fields){

					$columns[]	=	sprintf('%sprivate%s$%s%s=%sNULL;',"\t\t\t","\t",$fields->name,"\t","\t");

				}

				$class	=	sprintf('%s%s%s',$class,implode("\n",$columns),"\n\n");

				foreach($this->getFields() as $fields){

					$parameter	=	$fields->name;

					if($fields->nullable == 'YES' && $fields->defaultValue === ''){

						$parameter	=	sprintf('%s=NULL',$parameter);

					}elseif($fields->nullable == 'NO' && $fields->defaultValue !== ''){

						$parameter	=	sprintf('%s="%s"',$parameter,$fields->defaultValue);

					}

					$upperCamelParam	=	StringUtil::toUpperCamelCase($fields->name);
					$lowerCamelParam	=	StringUtil::toCamelCase($fields->name);

					$setter	=	sprintf('%spublic function set%s($%s){%s',"\t\t\t",$upperCamelParam,$lowerCamelParam,"\n\n");
					$setter	=	sprintf('%s%s$this->%s = $%s;%s',$setter,"\t\t\t\t",$fields->name,$fields->name,"\n");
					$setter	=	sprintf('%s%sreturn $this;%s',$setter,"\t\t\t\t","\n\t\t\n\t\t\t}\n");

					$getter	=	sprintf('%spublic function get%s(){%s',"\n\t\t\t",$upperCamelParam,"\n\n");
					$getter	=	sprintf('%s%sreturn $this->%s;%s%s',$getter,"\t\t\t\t",$fields->name,"\n","\t\t\n\t\t\t}\n\n");

					$class	=	sprintf('%s%s',$class,$setter);
					$class	=	sprintf('%s%s',$class,$getter);

				}

				$class	=	sprintf('%s%s',$class,"\n\t\t}\n");
				$class	=	sprintf('%s%s',$class,"\n\t}\n");

				return $class;

			}

			public function exportMockup(Array $namespace,$className,Array $parentClass=Array()){

				$className	=	StringUtil::toUpperCamelCase($this->name);
				$class		=	sprintf('%snamespace %s{',"\t",implode("\\",$namespace));

				if($parentClass){

					$class	=	sprintf("$class\n\n\t\tuse\t\\%s as Base;\n\n",implode('\\',$parentClass));
					$class	=	sprintf("$class\t\tclass $className extends Base{");

				}else{

					$class	=	"$class\n\t\tclass $className{\n";

				}

				$class	=	sprintf('%s%s',$class,"\n\t\t}\n");
				$class	=	sprintf('%s%s',$class,"\n\t}\n");

				return $class;

			}

			public function exportAsClass(Array $namespace=Array()){

				if(empty($namespace)){

					$namespace	=	Array(
												StringUtil::toCamelCase($this->getSchema()),
												'table'
					);

				}

				$className	=	StringUtil::toUpperCamelCase($this->name);

				$class	=	sprintf('%snamespace %s{',"\t",implode("\\",$namespace));
				$class	=	"$class\n\n\t\tclass $className extends BaseModel{\n\n";
				$class	=	"$class\t\t\tconst TABLE = '{$this->name}';\n\n";
				$class	=	sprintf('%s%sprotected $__tColumns = Array( %s',$class,"\t\t\t","\n");

				$fieldsArray	=	Array();

				foreach($this->getFields()->toArray() as $f){

					$fieldsArray[]	=	preg_replace("/\n/",'',var_export($f->toArray(),TRUE));

				}

				$class	=	sprintf('%s%s%s',StringUtil::tabs(6),$class,implode(",\n",$fieldsArray));

				$class	=	"$class\n\t\t\t);\n\n";
				$class	=	"$class\t\t\tpublic function getColumns(){\n";
				$class	=	"$class\n\t\t\t\treturn \$this->__tColumns;\n";
				$class	=	"$class\n\t\t\t}\n\n";
				$class	=	"$class\t\t}\n\n";
				$class	=	"$class\t}\n\n";

				return $class;

			}

			public function addRecord(\apf\data\Model $model){
			
			}

			public function exportAsFactory(Array $namespace = Array()){

				if(empty($namespace)){

					$namespace	=	Array(
												StringUtil::toCamelCase($this->getSchema()),
												'factory'
					);

				}

				$className	=	StringUtil::toUpperCamelCase($this->name);
				$schema		=	StringUtil::toCamelCase($this->getSchema());

				$class		=	sprintf('%snamespace %s{',"\t",implode("\\",$namespace));
				$class		=	"$class\n\n\t\tuse\t\\apf\\data\\Factory;\n\n";
				$class		=	"$class\t\tclass $className extends Factory{\n\n";
				$className	=	StringUtil::toUpperCamelCase($this->name);
				$schema		=	StringUtil::toCamelCase($this->getSchema());

				$class		=	"$class\t\t\tuse\t\\{$this->getSchema()}\\filter\\$className as Filter;\n\n";
				$class		=	"$class\t\t\tuse\t\\{$this->getSchema()}\\collection\\$className as Collection;\n\n";

				$columns			=	Array();

				foreach($this->getFields() as $field){

					$class	=	sprintf('%spublic static function getInstanceBy%s($value,Filter $filter=NULL){%s',"$class\t\t\t",StringUtil::toUpperCamelCase($field->name),"\n\n");
					$class	=	sprintf('%s$filter = parent::getFilterInstance($filter);%s',"$class\t\t\t\t","\n");
					$class	=	sprintf('%s$filter->set%s($value);%s',"$class\t\t\t\t",StringUtil::toUpperCamelCase($field->name),"\n");
					$class	=	sprintf('%sreturn (new Collection($filter))->fetch();%s',"$class\n\t\t\t\t","\n\n");
					$class	=	"$class\t\t\t}\n\n";

				}

				$class	=	"$class\t\t}\n\n";
				$class	=	"$class\t}\n\n";

				return $class;

			}

			public function exportAsForm(Array $namespace = Array()){

				if(empty($namespace)){

					$namespace	=	Array(
												StringUtil::toCamelCase($this->getSchema()),
												'form'
					);

				}

				$className	=	StringUtil::toUpperCamelCase($this->name);
				$schema		=	StringUtil::toCamelCase($this->getSchema());

				$class		=	sprintf('%snamespace %s{',"\t",implode("\\",$namespace));
				$class		=	"\n$class\t\tuse\t\\{$this->getSchema()}\\model\\$className as Model;\n\n";
				$class		=	"$class\t\tuse\t\\apf\\data\\Form;\n\n";
				$class		=	"$class\t\tclass $className extends Form{\n\n";
				$class		=	"$class\t\t}\n";

				return $class;

			}

			public function exportAsCollection(Array $namespace=Array()){

				if(empty($namespace)){

					$namespace	=	Array(
												StringUtil::toCamelCase($this->getSchema()),
												'collection'
					);

				}

				$filterNamespace	=	Array(
												StringUtil::toCamelCase($this->getSchema()),
												'filter'				
				);

				$className	=	StringUtil::toUpperCamelCase($this->name);
				$schema		=	StringUtil::toCamelCase($this->getSchema());

				$class		=	sprintf('%snamespace %s{',"\t",implode("\\",$namespace));
				$class		=	"$class\n\n\t\tuse\t\\apf\\data\\Collection;\n\n";
				$class		=	"$class\t\tclass $className extends Collection{\n\n";
				$class		=	sprintf('%s%sconst FILTER="\%s\%s";%s',$class,"\t\t\t",implode("\\",$filterNamespace),$className,"\n\n");
				$class		=	"$class\t\t}\n\n";
				$class		=	"$class\t}\n";

				return $class;


			}

			public function exportAsFilter(Array $modelNamespace,Array $namespace=Array()){

				if(empty($namespace)){

					$namespace	=	Array(
												StringUtil::toCamelCase($this->getSchema()),
												'filter'
					);

				}

				$className	=	StringUtil::toUpperCamelCase($this->name);
				$schema		=	StringUtil::toCamelCase($this->getSchema());
				$mapTo		=	sprintf('\\%s\\%s',implode('\\',$modelNamespace),$className);

				$class		=	sprintf('%snamespace %s{',"\t",implode("\\",$namespace));
				$class		=	"$class\n\n\t\tuse\t\\apf\\iface\\data\\Filter as FilterInterface;\n";
				$class		=	"$class\t\tuse\t$mapTo as BaseFilter;\n\n";
				$class		=	"$class\t\tclass $className extends BaseFilter implements FilterInterface{\n\n";
				$class		=	"$class\t\t\tuse \\apf\\traits\\data\\Filter;\n\n";

				$columns			=	Array();
				$textFields		=	Array();

				foreach($this->getFields() as $field){

					if(in_array($field->type,Array('text','char','varchar'))){

						$columns[]		=	sprintf('%sprivate%s$%sLike = NULL;',"\t\t\t","\t",StringUtil::toCamelCase($field->name));
						$textFields[]	=	$field;

					}

				}

				$class	=	sprintf('%s%s%s',$class,implode("\n",$columns),"\n\n");

				foreach($textFields as $field){

					$parameter	=	$field->name;

					$upperCamelParam	=	StringUtil::toUpperCamelCase($field->name);
					$lowerCamelParam	=	StringUtil::toCamelCase($field->name);

					$setter	=	sprintf('%spublic function set%sLike($like=NULL){%s',"\t\t\t",$upperCamelParam,"\n\n");
					$setter	=	sprintf('%s%s$this->%sLike = $like;%s',$setter,"\t\t\t\t",StringUtil::toCamelCase($field->name),"\n");
					$setter	=	sprintf('%s%sreturn $this;%s',$setter,"\t\t\t\t","\n\t\t\n\t\t\t}\n");

					$getter	=	sprintf('%spublic function get%sLike(){%s',"\n\t\t\t",$upperCamelParam,"\n\n");
					$getter	=	sprintf('%s%sreturn $this->%sLike;%s%s',$getter,"\t\t\t\t",StringUtil::toCamelCase($field->name),"\n","\t\t\n\t\t\t}\n\n");

					$class	=	sprintf('%s%s',$class,$setter);
					$class	=	sprintf('%s%s',$class,$getter);

				}


				$getMap	=	"\t\t\tpublic function getMap(){\n\n";
				$getMap	=	"$getMap\t\t\t\treturn Array('class'=>'$mapTo')";

				$class	=	sprintf('%s%s',"$class$getMap","\n\t\t\t}\n");
				$class	=	sprintf('%s%s',"$class","\n\t\t}\n");
				$class	=	sprintf('%s%s',$class,"\n\t}\n");

				return $class;

			}

			public function setFields(Array $fields){

				$this->fields	=	$fields;

			}

			public function getFields($cache=TRUE){

				if($cache && sizeof($this->fields)){

					return $this->fields;

				}

				return $this->fields	=	$this->getColumnsFromInformationSchema();

			}

			public function getColumnsFromInformationSchema(){

				$table	=	new static("information_schema.COLUMNS",$this->params);
				$select	=	new Select($table,$this->params);
				$select->fields(
										Array(
												"DATA_TYPE AS type",
												"CHARACTER_MAXIMUM_LENGTH AS maxLen",
												"COLUMN_NAME AS name",
												"COLUMN_TYPE AS extType",
												"COLUMN_DEFAULT AS defaultValue",
												"IS_NULLABLE AS nullable",
										)
				);

				$where	=	Array(
										Array(
												"field"=>"TABLE_SCHEMA",
												"value"=>$this->schema
										),
										Array(
												"operator"=>"AND"
										),
										Array(
												"field"=>"TABLE_NAME",
												"value"=>$this->name
										)
				);

				$select->where($where);
				$res	=	$select->execute(NULL,$smartMode=FALSE);

				if(is_null($res)){

					$msg	=	"Can't fetch columns for table ".$this->name." on schema `".
					$this->schema.'`, wrong connection, database or a temporary table?.'.
					'you might want to try specifying fields in your query so they\'re not '.
					'gathered dynamically through information_schema';

					throw(new \Exception($msg));

				}

				return $res;

			}

			public function getFieldsAsArray($prefixTableName=FALSE){

				if(!sizeof($this->fields)){

					throw(new \Exception("Can't getFieldsAsArray, no fields have been set"));

				}

				$tmpFields	=	Array();

				foreach($this->fields as $field){

					$field			=	($prefixTableName) ? $this->name.'.'.$field["name"] : $field["name"];
					$tmpFields[]	=	$field;

				}

				return $tmpFields;

			}

			public function getFieldsAsString($separator=',',$space=' '){

				if(!sizeof($this->fields)){

					throw(new \Exception("No fields have been set in ".get_class($this)));

				}

				foreach($this->fields as $field){

					$tmpField	=	$this->name.'.'.$field["name"];

					if(array_key_exists("alias",$field)){

						$tmpField.=$space.'AS'.$space.$field["alias"];

					}

					$string[]	=	$tmpField;


				}

				return implode($separator,$string);

			}

			public function setName($name=NULL){

				if(is_object($name)&&$name->TABLE_NAME){

						$name	=	$name->TABLE_NAME;
					
				}

				\apf\Validator::emptyString($name,"Table name can't be empty");

				$pos	=	strpos($name,'.');

				if($pos===FALSE){

					$adapter			=	Adapter::getInstance($this->params);
					$this->schema	=	$adapter->getDatabaseName();
					$this->name		=	$name;

					return;

				}

				$this->schema	=	substr($name,0,$pos);
				$this->name		=	substr($name,$pos+1);

			}

			public function getName($includeSchema=TRUE){

				if($includeSchema){

					return	$this->schema.'.'.$this->name;

				}

				return $this->name;

			}

			public function getSchema(){
				return $this->schema;
			}

			public function truncate(){

				$adapter			=	Adapter::getInstance($this->params);
				$adapter->directQuery("TRUNCATE $this");

			}

			public function drop(){

				$adapter	=	Adapter::getInstance($this->params);
				$adapter->directQuery("DROP TABLE $this");

			}

			public function dump(){
				$adapter	=	Adapter::getInstance($this->params);
				return $adapter->directQuery("SHOW CREATE TABLE $this");
			}

			public function __set($var,$value){

					$this->fields[$var]	=	$value;
					
			}

			public function __toString(){

				return $this->getName();

			}

		}

	}

	
?>
