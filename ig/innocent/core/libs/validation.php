<?php
class Validation extends Core {
    
    protected $rule;
    protected $_post;
    
    public function __construct($rule=null, $post=null){

    }

    public function validate($rule=null, $post=null){
        $this->rule = $rule;
        $this->_post = $post;
        return $this->exec();
    }

    protected function exec() {
        foreach($this->rule as $name => $check) {
            if (method_exists($this, $check)) {
                if($this->$check($this->_post[$name], $check)){
                    return $check["msg"];
                }
            }
        }
        return true;
    }
    
    protected function required($value, $opt=null) {
        if($value) {
            if($value != "") {
                return true;
            }
        }
        return false;
    }
    
}
