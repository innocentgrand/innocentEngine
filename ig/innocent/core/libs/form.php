<?php 
class Form extends core {
	
    private $_data;

    public function start($name="form1",$method="post",$action="/",$opt=array()){
        $option = "";
        if(!empty($opt)){
            foreach($opt as $key => $value){
                switch($key){
                    case 'accept-charset':
                        $option .= ' accept-charset="' . $valu . '"'; 
                        break;
                    case 'enctype':
                        $option .= ' enctype="' . $valu . '"'; 
                        break;
                    case 'target':
                        $option .= ' target="' . $valu . '"'; 
                        break;
                    default:
                        $option .= ' '.$key.'="' . $value . '"';
                        break;	

                }	
            }
        }
        if($action == "/"){
            $action = $_SERVER['REQUEST_URI'];
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
                    default:
                        $option .= ' '.$key.'="' . $value . '"';
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
                    default:
                        $option .= ' '.$key.'="' . $value . '"';
                        break;	

                }
            }
        }

        return '<input type="password" name="'.$name.'" '.$option.$setVal.' />';
    }
    
    public function hidden($name, $value) {
        return '<input type="hidden" name="'.$name.'" '.$value.' />';
    }
    
    public function submit($name="submit",$value="submit",$opt=array()){
        $option="";
        if(!empty($opt)){
            foreach($opt as $key => $value){
                switch($key){
                    case 'formaction':
                        $option .= ' formaction="' . $valu . '"'; 
                        break;
                    case 'formenctype':
                        $option .= ' formenctype="' . $valu . '"'; 
                        break;
                    case 'formmethod':
                        $option .= ' formmethod="' . $valu . '"'; 
                        break;
                    default:
                        $option .= ' '.$key.'="' . $value . '"';
                        break;	
                }	
            }
        }
        return '<input type="submit" name="'.$name.'" value="'.$value.'"'.$option.' />';
    }

    public function dataSetter($data){
        $this->_data = $data;
    }	

}
