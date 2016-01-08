<?php 
class Form extends core {
	
    private $_data;

    public function start($name="form1",$method="post",$action="/",$opt=array()){
        $option = "";
        if(!empty($opt)){
            foreach($opt as $key => $value){
                switch($key){
                    case 'accept-charset':
                        $option .= ' accept-charset="' . $value . '"';
                        break;
                    case 'enctype':
                        $option .= ' enctype="' . $value . '"';
                        break;
                    case 'target':
                        $option .= ' target="' . $value . '"';
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
        return '<input type="hidden" name="'.$name.'" value="'.$value.'"" />';
    }
    
    public function submit($name="submit",$value="submit",$opt=array()){
        $option="";
        if(!empty($opt)){
            foreach($opt as $key => $value){
                switch($key){
                    case 'formaction':
                        $option .= ' formaction="' . $value . '"';
                        break;
                    case 'formenctype':
                        $option .= ' formenctype="' . $value . '"';
                        break;
                    case 'formmethod':
                        $option .= ' formmethod="' . $value . '"';
                        break;
                    default:
                        $option .= ' '.$key.'="' . $value . '"';
                        break;	
                }	
            }
        }
        return '<input type="submit" name="'.$name.'" value="'.$value.'"'.$option.' />';
    }

    public function file($name="file",$opt=array()) {
        return '<input type="file" name="'.$name.'" />';
    }

    public function textarea($name, $opt) {
        $html = "<textarea __options__ >";
        $option = "";
        if(!empty($opt)){
            foreach($opt as $key => $value){
                switch($key){
                    case "cols":
                        $option .= ' cols="' . $value . '"';
                        break;
                    case "rows":
                        $option .= ' rows="' . $value . '"';
                        break;
                    case "autofocus":
                        $option .= " autofocus";
                        break;
                    case "disabled":
                        $option .= " disabled";
                        break;
                    case "wrap":
                        $option .= ' wrap="' . $value . '"';
                        break;
                    default:
                        if($value != "") {
                            $option .= ' ' . $key . '="' . $value . '"';
                        }
                        else {
                            $option .= ' ' . $key;
                        }
                        break;
                }
            }
        }
        $html = str_replace("__options__", $option, $html);
        $setVal = "";
        foreach($this->_data['post'] as $indexName => $val){
            if($name == $indexName){
                $setVal = $val;
                break;
            }
        }
        $html .= "{$setVal}</textarea>";
        return $html;
    }

    public function dataSetter($data){
        $this->_data = $data;
    }	

}
