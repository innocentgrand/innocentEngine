<?php 
class Model extends Core {
		
    const CONDITION_SIMPLE = 1;
    const CONDITION_LITTLE_VERY = 2;
    const CONDITION_MIDDLE_VERY = 3;
    const CONDITION_ULTRA_VERY = 4;
    const CONDITION_OBSCURITY = 5;

    protected $dbObject;

    protected $table;

    protected $columns;

    protected $stmtObject;

    protected $whereDatas;

    public $validation;

    protected $dbconStrArray;

	private $dbKeyPrefix;

    public $rule;

    public function __construct($dbsetting, $DBkey = null, $prefix = null){
        
        $i = 0;
		$dbName = array();
        foreach ((array)$dbsetting as $key => $val) {
            $dsn[$key] = <<<TEXT
mysql:host={$val['host']};dbname={$val['db']};charset=utf8
TEXT;
            $dsn2[$i] = <<<TEXT
mysql:host={$val['host']};dbname={$val['db']};charset=utf8
TEXT;
			$dbName[$key] = $val['db'];
			$dbName[$i] = $val['db'];

            $user[$key] = $val['user'];
            $pass[$key] = $val['passwd'];
            $user[$i] = $val['user'];
            $pass[$i] = $val['passwd'];
            $i++;
        }


		try {
			$this->table = strtolower(get_class($this));
            if (empty($DBkey)) {
				$this->makeDbObject($dsn[0], $user[0], $pass[0]);
				$tmpDbName = $dbName[0];
			} else {
				if ($prefix) {
					$DBkey .= "_" . $prefix;
					$this->dbKeyPrefix = $prefix;
				}
				$this->makeDbObject($dsn[$DBkey], $user[$DBkey], $pass[$DBkey]);
				$tmpDbName = $dbName[$DBkey];
			}


			$this->tableExist($tmpDbName);

			$this->getColumn();

			$this->validation = new Validation();

			$this->startUp();
		}catch (Exception $ex) {
			throw $ex;
		}
    }

    public function validateRule($arr=null) {
        $this->rule = $arr;
    }

    public function validate($post=null){
        if(!empty($this->rule)) {
            return $this->validation->validate($this->rule, $post);
        }
    }

    protected function startUp(){
    }

    protected function getColumn(){
            $stmt = $this->dbObject->query("SELECT * FROM {$this->table} LIMIT 0");
            $i = 0;
            while($column = $stmt->getColumnMeta($i++)){
                $this->columns[$i]["name"] = $column["name"];
                $this->columns[$i]["type"] = $column["native_type"];
                switch($column["native_type"]){
                case 'LONGLONG':
                    $this->columns[$i]["type"] = PDO::PARAM_INT;
                    break;
                default:
                    $this->columns[$i]["type"] = PDO::PARAM_STR;
                    break;
                }
            }
    }

    protected function makeDbObject($dsn,$uid,$upass,$charset="utf8"){
        try{
			$this->dbObject = new PDO($dsn,$uid,$upass);
            $stmt = $this->dbObject->prepare("SET NAMES :charset");

            $stmt->bindValue(":charset", $charset, PDO::PARAM_STR);

            $this->dbObject->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {
			throw $ex;
		} catch(Exception $ex) {
			throw $ex;
		}
    }

    public function find($kind, $option = array()){
        try{

            $sql = "SELECT ";
            if(!empty($option['fields'])){
                    $fieldData = $option['fields'];
            } else {
                    $fieldData = null;
            }
            if(!empty($option['conditions'])){
                    $conditionData = $option['conditions'];
            } else {
                    $conditionData = null;
            }
            switch($kind){
                case 'first':
                    $field = $this->makeFields($fieldData);
                    $sql .= $field;
                    $sql .= " FROM {$this->table} ";
                    $where = $this->makeWhere($conditionData);
                    if($where != ""){
                            $sql .= " WHERE " .  $where;
                    }
                    $this->stmtObject = $this->dbObject->prepare($sql);
                    return $this->returnFirst($conditionData);
                    break;
                case 'all':
                    $field = $this->makeFields($fieldData);
                    $sql .= $field;
                    $sql .= " FROM {$this->table} ";
                    $where = $this->makeWhere($conditionData);
                    if($where != ""){
                            $sql .= " WHERE " .  $where;
                    }
                    $this->stmtObject = $this->dbObject->prepare($sql);
                    return $this->returnAll($conditionData);
                    break;
                case 'count':
                    //$field = $this->makeFields($fieldData);
                    $sql .= "COUNT(*) AS counter";
                    $sql .= " FROM {$this->table} ";
                    $where = $this->makeWhere($conditionData);
                    if($where != ""){
                            $sql .= " WHERE " .  $where;
                    }
                    $this->stmtObject = $this->dbObject->prepare($sql);
                    $data = $this->returnFirst($conditionData);
                    return (int)$data['counter'];
                    break;
            }

        } catch(PDOException $ex){
                throw $ex;
        }
    }

    protected function returnFirst($conditions = null){
            foreach($this->columns as $index => $value){
                foreach($this->whereDatas as $key => $val){
                    if($value['name'] == $val){
                        $bindStr = ":" . $value['name'];
                        $this->stmtObject->bindParam($bindStr,$conditions[$value['name']],$value['type']);
                    }
                }
            }
            $this->stmtObject->execute();
            return $this->stmtObject->fetch();
    }

    protected function returnAll($conditions = null){
        foreach($this->columns as $index => $value){
            foreach($this->whereDatas as $key => $val){
                if($value['name'] == $val){
                        $bindStr = ":" . $value['name'];
                        $this->stmtObject->bindParam($bindStr,$conditions[$value['name']],$value['type']);
                }
            }
        }
        $this->stmtObject->execute();
        return $this->stmtObject->fetchAll();
    }

    protected function setParams($conditions = null){
        if(!empty($conditions)){
            if(count($conditions) == self::CONDITION_SIMPLE){
                foreach($conditions as $name => $val){
                }
            }
        }
    }

    protected function makeFields($field = null){
        $makeFieldStr = "";
        if(!empty($field)){
            foreach($this->columns as $name => $volumes) {
                foreach ($volumes as $volume) {
                    if ($name == 'type')
                        break;
                    foreach ($field as $f) {
                        if ($volume == $f) {
                            if ($makeFieldStr == "") {
                                $makeFieldStr = $f;
                            } else {
                                $makeFieldStr .= "," . $f;
                            }
                        }
                    }
                }
            }
            return $makeFieldStr;
        }
        return '*';
    }
    protected function makeWhere($conditions = null){
        $where = "";
        if(!empty($conditions)){
            $conditions = $this->conditioFairing($conditions);
            if(array_depth($conditions) == self::CONDITION_SIMPLE) {
                foreach($conditions as $name => $val) {
                    if ($where == "") {
                        $where .= "{$name} = :{$name}";
                        $this->whereDatas[] = $name;
                    } else {
                        $where .= " AND ";
                        $where .= " {$name} = :{$name}";
                        $this->whereDatas[] = $name;
                    }
                }
            } 
        }
        return $where;
    }

    private function conditioFairing($data) {
        $arr = array();
        foreach($this->columns as $column) {
            foreach ($data as $k => $v) {
                if ($column['name'] == $k) {
                    $arr[$column['name']] = $v;
                }
            }
        }
        return $arr;

    }

	private function tableExist($dbName) {
		$sql = <<<SQL
SELECT COUNT(*) AS COUNTER FROM information_schema.tables WHERE table_name = '{$this->table}' AND table_schema = '{$dbName}'
SQL;
		$this->stmtObject = $this->dbObject->query($sql);
		$count = $this->stmtObject->fetch();
		if ($count['COUNTER'] != 0) {
			return true;
		}
		else {
			$this->tablePrefixCanger($dbName);
		}
	}

	private function tablePrefixCanger($dbName){
		$wc = new Wordconversion();
		$this->table = $wc->make($this->table);
        $this->tableExist($dbName);
	}

    public function save($data=array(), $condition = array())
    {
        try {
            $this->begin();

            if(!$data) {
                return false;
            }

            $sql = "";
            if(!empty($data['id'])){
                $sql .= "UPDATE {$this->table} ";
                $sql .= "SET ";
                $setSql = "";
                foreach($this->columns as $column) {
                    switch($column["name"]) {
                        case "updated":
                            $setSql .= ",updated=NOW()";
                            break;
                        case "modified":
                            $setSql .= ",modified=NOW()";
                            break;
                        default:
                            foreach ($data as $name => $value) {
                                if ($column["name"] == $name) {
                                    if ($setSql == "") {
                                        $setSql .= "{$name} = :{$name}";
                                    } else {
                                        $setSql .= ",{$name} = :{$name}";
                                    }
                                }
                                break;
                        }
                    }
                }
                $sql .= $setSql;

                $sql .= " WHERE id = :id";
            }
            else {
                $sql .= "INSERT INTO  {$this->table} ";
                $set = "";
                $val = "";
                foreach($this->columns as $column) {
                    switch($column["name"]){
                        case "created":
                            $set .= ",created";
                            $val .= ",NOW()";
                            break;
                        case "updated":
                            $set .= ",updated";
                            $val .= ",NOW()";
                            break;
                        case "modified":
                            $set .= ",modified";
                            $val .= ",NOW()";
                            break;
                        default:
                            foreach($data as $name => $value) {
                                if($column["name"] == $name) {
                                    if ($set == "") {
                                        $set .= "{$name}";
                                        $val .= ":{$name}";
                                    } else {
                                        $set .= ",{$name}";
                                        $val .= ",:{$name}";
                                    }
                                }
                            }
                            break;
                    }
                }
                $sql .= "(" . $set . ")VALUES(" .$val .")";
            }
            $this->stmtObject = $this->dbObject->prepare($sql);
            foreach($this->columns as $index => $value){
                foreach($data as $key => $val){
                    if($value['name'] == $key){
                        $bindStr = ":" . $value['name'];
                        $this->stmtObject->bindValue($bindStr,$val,$value['type']);
                    }
                }
            }
            $this->stmtObject->execute();
            $this->commit();
        }catch (PDOException $ex){
            $this->rollback();
            throw($ex);
        }catch (Exception $ex){
            throw($ex);
        }
    }


    public function begin(){
        $this->dbObject->beginTransaction();
    }

    public function commit(){
        $this->dbObject->commit();
    }
    public function rollback(){
        $this->dbObject->rollBack();
    }

}
