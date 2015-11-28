<?php 
class CONFIG extends Core {

    protected $setting;
    
    protected $dbSetting;

    protected $hierarchy;

    protected $aliasdata;

    protected $dirPathData;

    public function __construct($path){
            parent::__construct();
            $settingDirPath = $path . "setting.ini";
            if(file_exists($settingDirPath)) {
                $this->setting = parse_ini_file($settingDirPath, true);
            }
            $dbINI = $path . "db.ini";
            if(file_exists($dbINI)){
                $this->dbSetting = parse_ini_file($dbINI,true);
                $this->dbSettingFairing();
             }
            
            $serverDirPath = $path . "set_path.ini";
            if(file_exists($serverDirPath)) {
                $this->dirPathData = parse_ini_file($serverDirPath, true);
            }
            
    }
    
    private function dbSettingFairing() {
        foreach ($this->dbSetting as $k => $sett) {
            pr($k);
            pr($sett);
        }
    }
    
    public function getSetting(){
        return $this->setting;
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
