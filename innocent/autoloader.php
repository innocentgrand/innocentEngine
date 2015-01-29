<?php
$dir = dirname(__FILE__);
$core_dir = $dir .DS. "core" . DS;

$files = autoLoad($core_dir);

//優先度が超高いものを先に読んでおく
require_once($core_dir."libs".DS."util.php");

foreach($files as $file){
		require_once($file);
}

function autoLoad($dir){
	$list = array();
	$files = scandir($dir);
	foreach($files as $file){
		if($file == "." || $file == ".." ){
			continue;
		} 
		else if(is_file($dir.$file)) {
				$info = pathinfo($dir.$file);
				if($info['filename'] == 'core'){
						require_once($dir.$file);
				}
				else if($info['filename'] == "Xfiles") {
						/////////
				}	
				else if($info['filename'] == "util") {
						/////////
				}	
				else {	
					if($info['extension'] == 'php'){	
							$list[] = $dir.$file;
					}
				}
		}
		else if(is_dir($dir.$file)){
				$list = array_merge($list,autoLoad($dir.$file.DS));
		}		
	}
	return $list;
}
?>
