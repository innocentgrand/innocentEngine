<?php 
class Alias extends Core {

		protected $aliasData;

		protected $hiData;

		public function  __construct(){
				parent::__construct();
		}

		public function hierarchyDataSetter($data){
				$this->hiData = $data;
		}

		protected function makeAliasData(){
				foreach($this->aliasData as $hi => $aliasValue){
						pr($hi);
				}	
		}


}
