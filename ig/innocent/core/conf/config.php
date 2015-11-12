<?php 
class CONFIG extends Core {

    protected $dbSetting;

    protected $hierarchy;

    protected $aliasdata;

    protected $dirPathData;

    public function __construct($path){
            parent::__construct();
            $dbINI = $path . "db.ini";
            if(file_exists($dbINI)){
                $this->dbSetting = parse_ini_file($dbINI,true);
            }
            /*$hiINI = $path . "hi.ini";
            if(file_exists($hiINI)){
                            $this->hierarchy = parse_ini_file($hiINI,true);
            }*/
            /*$aliasINI = $path . "alias.ini";
            if(file_exists($aliasINI)){
                $this->aliasdata = parse_ini_file($aliasINI,true);	
            }*/
            $serverDirPath = $path . "set_path.ini";
            if(file_exists($serverDirPath)) {
                $this->dirPathData = parse_ini_file($serverDirPath, true);
            }
    }

    public function defaulDbGetter(){
            return $this->dbSetting;
    }

    public function getHierarchy(){
            return $this->hierarchy;
    }

    public function getAlias(){
            return $this->aliasdata;
    }

    public function getDirPath() {
        return $this->dirPathData;
    }

}
