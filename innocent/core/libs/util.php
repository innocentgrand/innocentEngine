<?php 
function BasicAuth($user,$pass){
		if(!isset($_SERVER["PHP_AUTH_USER"])){
				header("WWW-Authenticate: Basic realm=\"Please Enter Your Password\"");
				header("HTTP/1.0 401 Unauthorized");
				echo "Authorization Required";
				exit;
		}
		else {
				if($_SERVER["PHP_AUTH_USER"] != $user || $_SERVER["PHP_AUTH_PW"] != $pass ){
					echo "Authorization Required";
					exit;
				}

		}
}

