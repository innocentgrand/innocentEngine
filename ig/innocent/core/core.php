<?php 
class Core {
        
        protected $logDir;

        protected function __construct(){
        }

        public function logDirSetter($path){
                        if(!is_dir($path)){
                                throw new Exception("log dir not exists");
                        }
                        $this->logDir = $path;
        }

        protected function log($text = null, $filename = null){
                if(empty($filename)){
                                $filename = "default.log";
                }
                $dumptext = date("Y-m-d H:i:s") . ':';
                $dumptext .= $text . "\r\n";

                $file_dir = $this->logDir;

                $files = $file_dir . $filename;

                $fp = fopen($files, "a");
                fwrite($fp, $dumptext);
                fclose($fp);

                return;		
        }

    public function debugLog($vardata = null,$filename = null) {
        if(empty($filename)) {
            $filename = "debug.log";
        }
        ob_start();
        echo "[" . date("Y-m-d H:i:s") . "]\n";
        var_dump($vardata);
        $out=ob_get_contents();
        ob_end_clean();
        $file_dir = $this->logDir;
        file_put_contents($file_dir.$filename,$out,FILE_APPEND);

    }


        public function __destruct(){
        }
}
