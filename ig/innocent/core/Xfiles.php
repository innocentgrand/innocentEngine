<?php
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
$rootDir = dirname(dirname(__FILE__));
$appDir = dirname(dirname(__FILE__));
//session_start();
require_once($rootDir . DS . "autoloader.php");

///BASIC Auth////////////////////////////////////////////////////

//BasicAuth("test","test");

///////////////////////////////////////////////////////////////

$x_path_root = $rootDir;
$x_path_controller = $x_path_root . DS . "controller" . DS;
$x_path_modl = $x_path_root . DS . "model" . DS;
$x_path_view = $x_path_root . DS . "view" . DS;
$x_path_log = $x_path_root . DS . "log" . DS;
$x_path_confs = $x_path_root . DS ."confs" . DS;

try{
	require_once( $x_path_root . DS . 'core' . DS . 'Xfile.php');		
	$FWM = new XFile($x_path_root);
	
	$x_config = new CONFIG($x_path_confs);

	if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ){
			$protocol = "https://";
	} 
	else {
			$protocol = "http://";
	}
	$x_request = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$FWM->request($x_request);

	$x_parse_url = parse_url($x_request);

	$FWM->makeCObject();	
	$x_object = $FWM->makeCObject();
        if ($x_config->getDirPath()) {
            $FWM->pathiIniLoader($x_config->getDirPath());
        }
	$x_object->tplPathsetter($FWM->getViewPath());
	$x_object->logDirSetter($FWM->getLogDirPath());
	$x_object->modelPathSetter($FWM->getModelPath());
	$x_object->dbDataSetter($x_config->defaulDbGetter());
	$x_object->startUp();

	$x_method = $FWM->getMethodName();
	$x_class_name = get_class($x_object);

	if(!method_exists($x_object,$x_method)){
			$text = <<<TEXT
{$x_class_name} not method exists
TEXT;
		throw new Exception($text);
	}

	$x_object->$x_method();

	$x_object->shutDown();
} catch (Exception $ex){
	echo nl2br($ex->getMessage());
}
function pr($data){
		echo "<pre>";
		var_dump($data);
		echo "</pre>";
}

