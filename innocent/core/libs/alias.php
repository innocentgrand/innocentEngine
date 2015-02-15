<?php 
class Alias extends Core {

		protected $aliasData;
		
		protected $aliasSetData;

		public function  __construct(){
				parent::__construct();
		}

		public function aliasDataSetter($data){
			$this->aliasSetData = $data;
			pr($this->aliasData);
		}

		protected function makeAliasData(){
				foreach($this->aliasData as $hi => $aliasValue){
						pr($hi);
				}	
		}


}
