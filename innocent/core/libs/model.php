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

						$fieldSQL = "";
						if(!empty($option['field'])) {
							$field = $option['field'];
							if(!is_array($field)){
									$fieldSQL = ":field";
							}
							else {
									$no = 0;
									foreach($field as $f){
											if($fieldSQL == ""){
													$fieldSQL = ":field_" . $no;
											}
											else {
													$fieldSQL .= ",:field_" . $no;
											}
											$no++;
									}
							}
						}
						else {
							$fieldSQL = "*";
						}

						$whereSQL = "";
						if(!empty($option['conditions'])){
								$conditions = $option['conditions'];
								$no = 0;
								if(array_depth($conditions) == self::CONDITION_SIMPLE){
									foreach($conditions as $column => $value){
											if($whereSQL == ""){
													$whereSQL = "WHERE :column_" . $no . " = :value_" . $no; 
											} else {
													$whereSQL .= " AND :column_" . $no . " = :value_" . $no; 
											}
											$no++;
									}
								}
								else if(array_depth($conditions) == self::CONDITION_LITTLE_VERY){
									foreach($conditions as $term => $val){
											$subWhere = "";	
											foreach($val as $column => $value){
												if($subWhere == ""){
													$subWhere = "(:column_" . $no . " = :value_" . $no;
												}
												else {
													$subWhere .= " {$term} :column_" . $no . " = :value_" . $no;
												}
												$no++;
											}
											if($whereSQL == ""){
													$whereSQL = " WHERE " . $subWhere . ")";
											}
											else {
													$whereSQL .= " AND " . $subWhere . ")";
											}
									}
								}
								else if(array_depth($conditions) == self::CONDITION_MIDDLE_VERY){
								
								}	
						}	
						
						$sql = <<<SQL
SELECT {$fieldSQL} FROM {$this->table}
SQL;

					if($kind == 'first'){
						
					}

						if($whereSQL != ""){
							$sql .= $whereSQL;
						}
				
				} catch(PDOException $ex){
					throw $ex;
				}
		}

}
