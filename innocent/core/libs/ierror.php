<?php
register_shutdown_function(
    function(){
        $e = error_get_last();
        if( $e['type'] == E_ERROR ||
            $e['type'] == E_PARSE ||
            $e['type'] == E_CORE_ERROR ||
            $e['type'] == E_COMPILE_ERROR ||
            $e['type'] == E_USER_ERROR ){
            // お好きな処理を書く
            echo "致命的なエラーが発生しました。\n";
            echo "Error type:\t {$e['type']}\n";
            echo "Error message:\t {$e['message']}\n";
            echo "Error file:\t {$e['file']}\n";
            echo "Error line:\t {$e['line']}\n";
        }
    }
);


set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});
