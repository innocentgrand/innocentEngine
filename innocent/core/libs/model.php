<?php 
class Model extends Core {

		protected $dbObject;

		public function __construct($dbsetting){
				pr($dbsetting);	
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

		protected function makeDbObject($dsn,$uid,$upass){
				try{
						$this->dbObject = new PDO($dsn,$uid,$upass);	
				} catch(PDOException $ex){
					throw $ex;
				}
		}

}
