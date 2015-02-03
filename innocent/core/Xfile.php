<?php
class XFile {
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
	
	private function makeCObject(){
		$x_parse_url = parse_url($this->request);
		pr($x_parse_url);
	}

}
