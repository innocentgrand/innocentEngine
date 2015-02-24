<?php 
class Model extends Core {
		
		const CONDITION_SIMPLE = 1;
		const CONDITION_LITTLE_VERY = 2;
		const CONDITION_MIDDLE_VERY = 3;
		const CONDITION_ULTRA_VERY = 4;
		const CONDITION_OBSCURITY = 5;

		protected $dbObject;

		protected $table;
		
		protected $columns;

		public function __construct($dbsetting){
				if(strtolower($dbsetting['dbkind']) == "mysql") {
					//MySql
						$dsn = <<<TEXT
mysql:host={$dbsetting['host']};dbname={$dbsetting['db']};charset=utf8
TEXT;
					$this->makeDbObject($dsn,$dbsetting['user'],$dbsetting['passwd']);		
				}
				else if(strtolower($dbsetting['dbkind']) == "pgsql") {
					//Postgresql
				}
				else if(strtolower($dbsetting['dbkind']) == "sqlsrv") {
					//SQLServer
				}

				$this->table = strtolower(get_class($this));
				
				$this->getColumn();

				$this->startUp();
		}

		protected function startUp(){
		}

		protected function getColumn(){
			$stmt = $this->dbObject->query("SELECT * FROM {$this->table} LIMIT 0");
			$i = 0;
			while($column = $stmt->getColumnMeta($i++)){
				$this->columns["name"][$i] = $column["name"];
				$this->columns["type"][$i] = $column["native_type"];
				switch($column["native_type"]){
					case 'LONGLONG':
						$this->columns["type"][$i] = PDO::PARAM_INT;
						break;
					default:
						$this->columns["type"][$i] = PDO::PARAM_STR;
						break;
				}
			}
		}

		protected function makeDbObject($dsn,$uid,$upass,$charset="utf8"){
				try{
					$this->dbObject = new PDO($dsn,$uid,$upass);
						$stmt = $this->dbObject->prepare("SET NAMES :charset");

						$stmt->bindValue(":charset", $charset, PDO::PARAM_STR);
						
						$this->dbObject->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

				} catch(PDOException $ex){
					throw $ex;
				}
		}

		public function find($kind, $option = array()){
				try{
					
					$sql = "SELECT ";
					if(!empty($option['fields'])){
						$fieldData = $option['fields'];
					} else {
						$fieldData = null;
					}
					switch($kind){
						case 'first':
							$field = $this->makeFields($fieldData);
							$sql .= $field;
							$sql .= " FROM {$this->table} ";
							pr($sql);
							$stn = $this->dbObject->prepare($sql);
							break;
						case 'all':
							$field = $this->makeFields($fieldData);
							break;
						case 'count':
							$field = $this->makeFields($fieldData);
							$field = "({$field}) AS counter";
							break;
					}
					pr($field);
				
				} catch(PDOException $ex){
					throw $ex;
				}
		}

		protected function makeFields($field = null){
			$makeFieldStr = "";
			if(!empty($field)){
				foreach($this->columns as $name => $volumes){
					foreach($volumes as $volume){
						if($name == 'type')
							break;
						foreach($field as $f){
							if($volume == $f){
								if($makeFieldStr == ""){
									$makeFieldStr = $f;
								}else{
									$makeFieldStr .= "," . $f;
								}
							}
						}
					}
				}
				return $makeFieldStr;
			}
			return '*';
		}
		protected function makeWhere($conditions){
		}

}
