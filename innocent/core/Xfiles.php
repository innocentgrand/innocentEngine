<?php
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
$rootDir = dirname(dirname(__FILE__));
session_start();
require_once($rootDir . DS . "autoloader.php");

///BASIC Auth////////////////////////////////////////////////////

BasicAuth("test","test");

///////////////////////////////////////////////////////////////

$x_path_root = $rootDir;
$x_path_controller = $x_path_root . DS . "controller" . DS;
$x_path_modl = $x_path_root . DS . "model" . DS;
$x_path_view = $x_path_root . DS . "view" . DS;
$x_path_log = $x_path_root . DS . "log" . DS;
$x_path_confs = $x_path_root . DS ."confs" . DS;

try{

	$x_config = new CONFIG($x_path_confs);
	
	$tmpHi = $x_config->getHierarchy();

	$x_object_alias = new Alias();

	if(!empty($tmpHi)){

	}

	$tmpAlias = $x_config->getAlias();


	if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ){
			$protocol = "https://";
	} 
	else {
			$protocol = "http://";
	}
	$x_request = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$x_parse_url = parse_url($x_request);

	xfileSecend(&$x_config,&$x_path_controller,&$x_parse_url);	
}catch (Exception $ex){
	echo nl2br($ex->getMessage());
}


function xfileSecend($x_config,$x_parse_url,$x_path_controller,$parseCount=1){
	try{
		$x_exp_path = explode('/',$x_parse_url["path"]);
	
		if($x_exp_path[$parseCount] == ""){
				$x_class_sub_name = "Default";
		} 
		else {
			$x_class_sub_name = ucfirst($x_exp_path[1]);
		}

		$x_class_name = $x_class_sub_name."Controller";

		$x_controller_file = $x_path_controller . $x_class_name . ".php";
	
		if(!file_exists($x_controller_file)){
			throw new Exception("file not exists");
		}

		require_once($x_controller_file);

		if(!class_exists($x_class_name)){
			throw new Exception("class not exists");
		}

		$x_object = new $x_class_name();
		$x_object->tplPathsetter($x_path_view.$x_class_sub_name);
		$x_object->logDirSetter($x_path_log);
		$x_object->modelPathSetter($x_path_modl);
		$x_object->dbDataSetter($x_config->defaulDbGetter());

		$x_object->startUp();

		if(empty($x_exp_path[2])){
			$x_method = "Index";
		} 
		else {
			$x_mehod = ucfirst($x_exp_path[2]);
		}

		if(!method_exists($x_object,$x_method)){
				$text = <<<TEXT
{$x_class_name} not method exists
TEXT;
			throw new Exception($text);
		}

		$x_object->$x_method();

	} catch (Exception $ex){
		throw($ex);
	}
		pr($configObj);
}

function pr($data){
		echo "<pre>";
		var_dump($data);
		echo "</pre>";
}

?>
