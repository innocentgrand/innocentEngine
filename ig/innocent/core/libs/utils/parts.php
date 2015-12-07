<?php
class Parts extends Core {

    protected $tplPath;

    public function __construct(){
        parent::__construct();
    }

    protected function set($todata=null, $fromdata=null,$escape=true) {
        if($escape){
             $fromdata = $this->sanitize($fromdata);
        }
        $this->assignData[$todata] = $fromdata;
    }

    protected function sanitize(&$data){
        if(is_array($data)){
                return array_map(array(&$this,'sanitize'),$data);
        } else {
                return $this->h($data);
        }
    }

    protected function tpl($mytpl = null) {

        if(!empty($this->assignData)) {
            extract($this->assignData, EXTR_SKIP);
        }

        $backtraces = debug_backtrace();
        $filename = $backtraces[1]['function'];

        if(!empty($mytpl)) {
            $filename = $mytpl;
        }
        $filename = strtolower($filename);

        if(!file_exists($this->tplPath . DS . $filename . ".html")){
            throw new Exception("not tpl file");
        }
        require( $this->tplPath . DS . $filename . ".html");
    }

    public function h($str){
        return htmlspecialchars($str, ENT_QUOTES);
    }

    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function __destruct(){
                    parent::__destruct();
    }


}