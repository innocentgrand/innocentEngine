<?php
class XFile {
	private $x_path_root;
	private $x_path_controller;
	private $x_path_model;
	private $x_path_view;
	private $x_path_log;
	private $x_path_confs;

	private $x_config;

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
			pr($tmpAlias);
		}catch(Exception $ex){
			throw($ex);
		}

	}

}
