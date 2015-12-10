<?php 
class CONFIG extends Core {

    const MODE_SET_DEBUG = "DEBUG";
    const MODE_MODE_NORMAL = "NORMAL";

    
    protected $setting;
    
    protected $dbSetting;

    protected $hierarchy;

    protected $aliasdata;

    protected $dirPathData;

    protected $mode;
    
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

        $loaderPath = $path . "loader.php";
        if(file_exists($loaderPath)) {
            require_once($loaderPath);
        }
    }
    
    
    public function setMode($mode) {
        $this->mode = $mode;
    }
    
    private function dbSettingFairing() {
        $tmp = array();
        $mode = $this->getSetting();
        if($mode) {
            if ($mode['SETTING']) {
                if ($mode['SETTING']['MODE']) {
                    if($mode['SETTING']['MODE'] == self::MODE_SET_DEBUG) {
                        $prefix = self::MODE_SET_DEBUG;
                    }
                    else {
                        $prefix = self::MODE_MODE_NORMAL;
                    }
                }
            }
        }
        foreach ($this->dbSetting as $k => $sett) {
            if(strpos($k, $prefix) !== false){
                $tmp[$k] = $sett;
            }
        }
        $this->dbSetting = $tmp;
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
    
    public function getMode() {
        
    }

}
