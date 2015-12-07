<?php
class Controller extends Core {

    protected $params;

    protected $_server;

    protected $_files;

    protected $Session;

    protected $assignData;

    protected $tplPath;

    protected $modelPath;

    public $models;

    protected $dbSetting;

    protected $form;

    protected $modelVarious;

    private $prefix;

	public $tplflg;

	public $parts = array();

	protected $partName = array();

    public function __construct(){
        parent::__construct();
        $this->params = array(
                    'get' => $_GET,
                    'post' => $_POST,
                );
        $this->_server = $_SERVER;
        $this->_files = $_FILES;
        $this->Session = new SessionClass();
        $this->form = new Form();
        $this->form->dataSetter($this->params);
		$this->tplflg = true;
    }

    public function startUp(){
        $this->modelLoader();
    }


    protected function modelLoader(){
        if(!empty($this->models)) {
            foreach ($this->models as $model) {
                $modelfile = $this->modelPath . ucfirst($model) . ".php";
                if (!file_exists($modelfile)) {
                    throw new Exception("not model file.");
                }
                require_once($modelfile);
                $tmpModelName = ucfirst($model);
                if (is_array($this->modelVarious)) {
                    foreach ($this->modelVarious as $model => $db) {
                        if ($tmpModelName == ucfirst($model)) {
                            $this->{$tmpModelName} = new $tmpModelName($this->dbSetting, $db, $this->prefix);
                        }
                    }
                } else {
                    $this->{$tmpModelName} = new $tmpModelName($this->dbSetting);
                }


            }
        }
    }

    public function tplPathsetter($path){
        if(!is_dir($path)){
            throw new Exception("not tpl path");
        }

        $this->tplPath = $path;

    }

    public function modelPathSetter($path){
        $this->modelPath = $path;
    }

    public function dbDataSetter($data){
            $this->dbSetting = $data;
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

        $this->assignData['form'] = $this->form;

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

    public function shutDown(){

    }

    public function redirect($url) {
        header("Location: {$url}");
        exit();
    }

	public function partsObjectSetter($obj){
		$name = get_class($obj);
		$this->$name = $obj;
		$this->partName[$name] = $name;
	}

    public function __destruct(){
		parent::__destruct();
    }


}