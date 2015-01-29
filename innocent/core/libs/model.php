<?php 
class Model extends Core {

		protected $dbObject;

		protected $table;

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



				$this->startUp();
		}

		protected function startUp(){
		}

		protected function makeDbObject($dsn,$uid,$upass,$charset="utf8"){
				try{
						$this->dbObject = new PDO($dsn,$uid,$upass);
						$stmt = $this->dbObject->prepare("SET NAMES :charset");

						$stmt->bindValue(":charset", $charset, PDO::PARAM_STR);

				} catch(PDOException $ex){
					throw $ex;
				}
		}

		public function find($kind, $field = array(),$conditions = array()){
				try{


						$fieldSQL = "";
						if(!empty($field)) {
							if(!is_array($field)){
									$fieldSQL = ":field";
							}
							else {
									$no = 0;
									foreach($field as $val){
											if($fieldSQL == ""){
													$fieldSQL = ":field_" . $no;
											}
											else {
													$fieldSQL .= ":field_" . $no;
											}
											$no++;
									}
							}
						}
						else {
							$fieldSQL = "*";
						}

						$whereSQL = "";
						if(!empty($conditions)){
								$no = 0;
								foreach($conditions as $column => $value){
										if($whereSQL == ""){
												$whereSQL = "WHERE :column_" . $no . " = :value_" . $no; 
										} else {
												$whereSQL .= " AND :column_" . $no . " = :value_" . $no; 
										}
								}		
						}	
						
						$sql = <<<SQL
SELECT {$fieldSQL} FROM {$this->table}
SQL;
						pr($sql);
				
				} catch(PDOException $ex){
					throw $ex;
				}	
		}

}
