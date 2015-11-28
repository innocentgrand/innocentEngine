<?php
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
$rootDir = dirname(dirname(__FILE__));
$appDir = dirname(dirname(__FILE__));
//session_start();
require_once($rootDir . DS . "autoloader.php");

///BASIC Auth////////////////////////////////////////////////////

//BasicAuth("test","test");

///////////////////////////////////////////////////////////////

set_error_handler(function ($no, $msg, $file, $line) {
    if (error_reporting()) {
        throw new ErrorException($msg, 0, $no, $file, $line);
    }
});


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
        
        $xmode = $x_config->getSetting();
        
        if($xmode) {
            if ($mode['SETTING']) {
                if ($mode['SETTING']['MODE']) {
                    if($mode['SETTING']['MODE'] == XFile::MODE_SET_DEBUG) {
                        error_reporting(-1);
                    }
                    else {
                        error_reporting(0);
                    }
                }
            }
        }

	if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ){
            $protocol = "https://";
	} 
	else {
            $protocol = "http://";
	}
	$x_request = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$FWM->request($x_request);

	$x_parse_url = parse_url($x_request);

        if ($x_config->getDirPath()) {
            $FWM->pathiIniLoader($x_config->getDirPath());
            $FWM->resetDirPath();
        }
	$x_object = $FWM->makeCObject();
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
    echo $ex->getMessage();
}
function pr($data){
		echo "<pre>";
		var_dump($data);
		echo "</pre>";
}

