<?php 
class Form extends core {
	
	private $_data;

	public function start($name="form1",$method="post",$action="/",$opt=array()){
		$option = "";
		if(!empty($opt)){
			foreach($opt as $key => $value){
				
			}
		}
		
		return '<form name="'.$name.'" action="'.$action.'" method="'.$method.'"'.$option.' >';
	}
	
	public function end(){
		return "</form>";
	}

	public function text($name,$optarray = array()){
		$option = "";
		$setVal = "";
		
		if(!empty($optarray)){
			foreach($optarray as $key => $value){
				switch($key){
					case 'id':
						$option .= ' id="' . $value . '"';
						break;
					case 'class':
						$option .= ' class="' . $value . '"';
						break;
					case 'size':
						$option .= ' size="' . $value . '"';
						break;
					case 'maxlength':
						$option .= ' maxlength="' . $value . '"';
						break;

				}
			}
		}
		foreach($this->_data['post'] as $indexName => $val){
			if($name == $indexName){
				$setVal = ' value="' . $val . '"';
				break;
			}
		}
		
		return '<input type="text" name="'.$name.'" '.$option.$setVal.' />';
	}
	
	public function password($name,$optarray = array()){
		$option = "";
		$setVal = "";
		
		if(!empty($optarray)){
			foreach($optarray as $key => $value){
				switch($key){
					case 'id':
						$option .= ' id="' . $value . '"';
						break;
					case 'class':
						$option .= ' class="' . $value . '"';
						break;
					case 'size':
						$option .= ' size="' . $value . '"';
						break;
					case 'maxlength':
						$option .= ' maxlength="' . $value . '"';
						break;

				}
			}
		}
		
		return '<input type="password" name="'.$name.'" '.$option.$setVal.' />';
	}
	
	public function dataSetter($data){
		$this->_data = $data;
	}	

}
