<?php
class XFile {

	const DIRNAME_CONT = "controller";
	const DIRNAME_MODEL = "model";
	const DIRNAME_VIEW = "view";
	const DIRNAME_LOG = "log";
	const DIRNAME_CONFS = "confs";
	
	private $x_path_root;
	private $x_path_controller;
	private $x_path_model;
	private $x_path_view;
	private $x_path_log;
	private $x_path_confs;

    private $x_path_app_root;
    private $x_path_app_controller;
    private $x_path_app_model;
    private $x_path_app_view;
        
	private $x_config;

	private $x_protcol;

	private $hierarchy;
	
	private $x_request;

	private $x_exp_path;

	private $x_path_parts;

	private $loaderPaths;

	private $layoutMode = false;

	public function __construct($rootDir){
            $this->x_path_root = $rootDir;

            $this->x_path_controller = $this->x_path_root . DS;
            $this->x_path_model = $this->x_path_root . DS;
            $this->x_path_view = $this->x_path_root . DS;
            $this->x_path_log = $this->x_path_root . DS . self::DIRNAME_LOG . DS;
            $this->x_path_confs = $this->x_path_root . DS . self::DIRNAME_CONFS .DS;

            try{
				if (is_array(Loader::$path)) {
					foreach (Loader::$path as $path) {
						$path = str_replace("/", DS, $path);
						$this->loaderPaths[] = $path;
					}
				}

            }catch(Exception $ex){
                    throw($ex);
            }

	}
        
        public function resetDirPath() {
            $this->x_path_controller = $this->x_path_root;
            $this->x_path_model = $this->x_path_root;
            $this->x_path_view = $this->x_path_root;

			if (!empty($this->loaderPaths)) {
				foreach ($this->loaderPaths as $path) {
					if (file_exists($this->x_path_root . $path)) {
						require_once($this->x_path_root . $path);
					}
				}
			}

	    }

	public function request($req){
            $this->x_request = $req;
	}

	private $subDirName;	
	private $subClassName;	
	public function makeCObject(){
            $x_parse_url = parse_url($this->x_request);
            $x_exp_path = explode('/',$x_parse_url['path']);
            $this->x_exp_path = $x_exp_path;
            foreach((array)$this->hierarchy as $hiKey => $hiValue){
                if(!empty($x_exp_path[1])){
                        $tmpPath = "/" . $x_exp_path[1];
                }
                else {
                        $tmpPath = "";
                }
                if($hiValue['alias'] == $tmpPath){

                    if(empty($x_exp_path[2]) || $x_exp_path[2] == ""){
                            $x_class_sub_name = "Default";
                    }
                    else {
                            $x_class_sub_name = ucfirst($x_exp_path[2]);
                    }
                    $x_class_name = $x_class_sub_name."Controller";
                    $x_controller_file = $this->x_path_controller . $hiValue['hi']['hi'] . DS . self::DIRNAME_CONT  . DS . $x_class_name . ".php";
                    $this->subClassName = $x_class_sub_name;

                    return $this->makeObject($x_class_name,$x_controller_file);
                }
            }

            if($x_exp_path[1] == ""){
                    $x_class_sub_name = "Default";
            } 
            else {
                    $x_class_sub_name = ucfirst($x_exp_path[1]);
            }
            $x_class_name = $x_class_sub_name."Controller";
            $x_controller_file = $this->x_path_controller . self::DIRNAME_CONT . DS . $x_class_name . ".php";
            $this->subClassName = $x_class_sub_name;

            return $this->makeObject($x_class_name,$x_controller_file);
	}

    public function partsObjectLoader($name) {
		$className = ucfirst($name);
		$x_parts = $this->x_path_parts . $className . ".php";

		if(file_exists($x_parts)) {
			require_once($x_parts);
			if(class_exists($className)) {
				$x_obj = new $className();
				return $x_obj;
			}
		}
		return false;
	}

	private function makeObject($className,$path){
            if(!file_exists($path)){
                    throw new Exception("controller file not exists");
            }	
            require_once($path);

            if(!class_exists($className)){
                    throw new Exception("class not exists");
            }

            $x_object = new $className();

            return $x_object;
	}

	public function getViewPath(){
            $tmpViewDir = "";

            if(!empty($this->x_exp_path)){
                $tmpPath = "/" . $this->x_exp_path[1];

                if($tmpViewDir == ""){
                        $tmpViewDir = $this->x_path_root  . self::DIRNAME_VIEW . DS . $this->subClassName;
                }
                return $tmpViewDir;
            }
            else {
                $tmpViewDir = $this->x_path_root . DS . self::DIRNAME_VIEW .DS;
            }
            return $tmpViewDir;
	}
	
	public function getModelPath(){
		$tmpModelDir = "";

		if(!empty($this->x_exp_path)){
			$tmpPath = "/" . $this->x_exp_path[1];
			if(!empty($this->hierarchy)){
				foreach($this->hierarchy as $hiKey => $hiValue){
					if($tmpPath == $hiValue['alias']){
						$tmpModelDir = $this->x_path_root . DS . $hiValue['hi']['hi'] . DS . self::DIRNAME_MODEL . DS;
					}
				}
			}
			if($tmpModelDir == ""){
				$tmpModelDir = $this->x_path_root . DS .self::DIRNAME_MODEL . DS;
			}
			return $tmpModelDir;
		}
		else {
			$tmpModelDir = $this->x_path_root . DS . self::DIRNAME_MODEL .DS;
		}
		return $tmpModelDir;
	}

	public function getMethodName(){
		
		if(!empty($this->x_exp_path)){
			$tmpName = "/" . $this->x_exp_path[1];
			$methodName = "";
			if(!empty($this->hierarchy)){
				foreach($this->hierarchy as $hiKey => $hiValue){
					if($tmpName == $hiValue['alias']){
						if(!empty($this->x_exp_path[3])){
							$methodName = ucfirst($x_exp_path[3]);
						}
						if($methodName == ""){
							$methodName = "Index";
						}
					}
				}
			}
			if($methodName == ""){
				if(!empty($this->x_exp_path[2])){
					$methodName = ucfirst($this->x_exp_path[2]);
				}
				else {
					$methodName = "Index";
				}
			}	
		}
		return $methodName;
	}

	public function getLogDirPath(){
		return 	$this->x_path_log;
	}

	public function getSubClassName(){
		return $this->subClassName;
	}
        
        public function pathiIniLoader($d) {
            foreach ($d as $k => $v) {
                foreach ($v as $setName => $setValue) {
                    switch ($setName) {
                        case "rootdir":
                            $this->x_path_app_root = DS . $setValue;
                            break;
                        case "app":
                            $this->x_path_app_root = $this->x_path_app_root . DS . $setValue;
                            break;
                        case "path":
                            $this->x_path_app_root = $setValue . $this->x_path_app_root;
                            break;
                    }
                }
            }
            
            $this->x_path_root = $this->x_path_app_root . DS;
        }

	public function getDefaultTplPath() {
		return $this->x_path_root  . self::DIRNAME_VIEW . DS;
	}

	public function setLayoutMode($mode = null) {
		if ($mode){
			if ($mode == 0 ) {
				$this->layoutMode = false;
			}
			else {
				$this->layoutMode = true;
			}
		}
		else {
			$this->layoutMode = false;
		}
	}

	public function getLayoutMode() {
		return $this->layoutMode;
	}

}