<?php
class Validation extends Core {
    
    protected $rule;
    protected $_post;
    public $msg = "";
    
    public function __construct($rule=null, $post=null){

    }

    public function validate($rule=null, $post=null){
        $this->rule = $rule;
        $this->_post = $post;
        return $this->exec();
    }

    protected function exec() {
        foreach($this->rule as $name => $check) {
            foreach($check as $method['validation'] => $m) {
                foreach((array)$m as $key => $m2) {
                    pr($m2);
                    if (method_exists(__CLASS__, $m2)) {
                        if (!is_array($m2)) {
                            if ($this->$m2($this->_post[$name])) {
                                $this->msg = $check["msg"];
                                return false;
                            }
                        } else {
                            if ($this->$m2($this->_post[$name], $opt)) {
                                $this->msg = $check["msg"];
                                return false;
                            }
                        }
                    }
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
