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

	private $x_config;

	private $x_protcol;

	private $hierarchy;
	
	private $x_request;

	public function __construct($rootDir){
		$this->x_path_root = $rootDir;
		/*
		$this->x_path_controller = $this->x_path_root . DS . 'controller' . DS;
		$this->x_path_model = $this->x_path_root . DS . 'model' . DS;
		$this->x_path_view = $this->x_path_root . DS . 'view' . DS;
		$this->x_path_log = $this->x_path_root . DS . 'log' . DS;
		$this->x_path_confs = $this->x_path_root . DS . 'confs' . DS;
		 */

		$this->x_path_controller = $this->x_path_root . DS . 'controller' . DS;
		$this->x_path_model = $this->x_path_root . DS . 'model' . DS;
		$this->x_path_view = $this->x_path_root . DS . 'view' . DS;
		$this->x_path_log = $this->x_path_root . DS . 'log' . DS;
		$this->x_path_confs = $this->x_path_root . DS . 'confs' . DS;
		
		try{
			$this->x_config = new CONFIG($this->x_path_confs);
			$tmpAlias = $this->x_config->getAlias();	
			$i = 0;
			foreach($tmpAlias as $alias => $value){
				$this->hierarchy[$i]["alias"] = $alias;
				if(!empty($value)){
						$this->hierarchy[$i]["hi"] = $value;
				}
				else {
						$this->hierarchy[$i]["hi"] = null;
				}
				$i++;
			}

		}catch(Exception $ex){
			throw($ex);
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
		foreach($this->hierarchy as $hiKey => $hiValue){
				if(!empty($x_exp_path[1])){
						$tmpPath = "/" . $x_exp_path[1];
				}
				else {
						$tmpPath = "";
				}

				if($hiValue['alias'] == $tmpPath) {
						if($x_exp_path[2] == ""){
							$x_class_sub_name = "Default";
						}
						else {
							$x_class_sub_name = ucfirst($x_exp_path[2]);
						}
						$x_class_name = $x_class_sub_name."Controller";
						$x_controller_file = $this->x_path_controller . $hiValue['hi'] . DS . $x_class_name . ".php";
					   $this->subDirName = $hiValue['hi'];	
				} 
				else {
					if($x_exp_path[1] == ""){
						$x_class_sub_name = "Default";
					} 
					else {
						$x_class_sub_name = ucfirst($x_exp_path[1]);
					}
					$x_class_name = $x_class_sub_name."Controller";
					$x_controller_file = $this->x_path_controller .  $x_class_name . ".php";
				}
				
				$this->subClassName = $x_class_sub_name;

				if(!file_exists($x_controller_file)){
					throw new Exception("file not exists");
				}	

				require_once($x_controller_file);

				if(!class_exists($x_class_name)){
					throw new Exception("class not exists");
				}

				$x_object = new $x_class_name();
		
				return $x_object;
		}

	}

	public function getViewPath(){
		$tmpViewDir = "";
		if(!empty($this->subDirName)){
			$tmpViewDir = $this->subDirName . DS;
		}
		else {
			$tmpViewDir = "";
		}

		return $tmpViewDir;
	}

	public function getSubClassName(){
		return $this->subClassName;
	}

}
