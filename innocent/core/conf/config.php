<?php 
class CONFIG extends Core {
		
		protected $dbSetting;

		protected $hierarchy;

		public function __construct($path){
				parent::__construct();
				$dbINI = $path . "db.ini";
				if(file_exists($dbINI)){
						$this->dbSetting = parse_ini_file($dbINI,true);
				}
				$hiINI = $path . "hi.ini";
				if(file_exists($hiINI)){
						$this->hierarchy = parse_ini_file($hiINI,true);
				}
		}

		public function defaulDbGetter(){
			return $this->dbSetting;
		}

		public function getHierarchy(){
			return $this->hierarchy;
		}
		
}
