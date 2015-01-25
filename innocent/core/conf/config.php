<?php 
class CONFIG extends Core {
		
		protected $dbSetting;
			
		public function __construct($path){
				parent::__construct();
				$dbINI = $path . "db.ini";
				if(file_exists($dbINI)){
						$this->dbSetting = parse_ini_file($dbINI,true);
				}
		}

		public function defaulDbGetter(){
			return $this->dbSetting;
		}
		
}
