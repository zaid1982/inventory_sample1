<?php
require_once 'f_general.php';
require_once 'library/sql.php';
     
class Class_db{
    
    private static $instance;
    private $DBH;
    private $fn_general;
            
    function __construct()
    {
        $config = parse_ini_file('library/config.ini');
        $this->fn_general = new Class_general();
    }
    
    private function __clone() {}

    public static function getInstance() {
        if (!Class_db::$instance instanceof self) {
             Class_db::$instance = new self();
        }
        return Class_db::$instance;
    }
    
    private function get_exception($codes, $function, $line, $msg) {
        if ($msg != '')
            return "(ErrCode:".$codes.") [".__CLASS__.":".$function.":".$line."] - ".$msg;
        else
            return "(ErrCode:".$codes.") [".__CLASS__.":".$function.":".$line."]";
    }
        
    public function __get($property) {
        if (property_exists($this, $property)) 
            return $this->$property;
        else
            throw new Exception($this->get_exception('0001', __FUNCTION__, __LINE__, 'Get Property not exist ['.$property.']'));
    }

    public function __set( $property, $value ) {
        if (property_exists($this, $property)) 
            $this->$property = $value;        
        else
            throw new Exception($this->get_exception('0002', __FUNCTION__, __LINE__, 'Get Property not exist ['.$property.']'));
    }
    
    public function __isset( $property ) {
        if (property_exists($this, $property)) 
            return isset($this->$property);
        else
            throw new Exception($this->get_exception('0003', __FUNCTION__, __LINE__, 'Get Property not exist ['.$property.']'));
    }
    
    public function __unset( $property ) {
        if (property_exists($this, $property)) 
            unset($this->$property);
        else
            throw new Exception($this->get_exception('0004', __FUNCTION__, __LINE__, 'Get Property not exist ['.$property.']'));
    }
    
    private function get_whereAnd_str($columnsArr) {
        $where_str = NULL;
        foreach ($columnsArr as $item => $value) {
            if ($value != '' && $value != '%%') {
                $l1 = substr($value, 0, 1);
                $l2 = substr($value, 0, 2);
                if ($item == 'w1') {
                    $where_str .= $value." AND ";
                } else if ($l2 == 'R_') {
                    $r2 = substr($value, 2);
                    $arr_dateRange = $this->get_date_split($r2);
                    if (!empty($arr_dateRange)) {
                        $where_str .= "($item between '$arr_dateRange[0]' and '$arr_dateRange[1]') AND ";
                    }
                } else if ($value == 'is NULL' || $value == 'is not NULL') {
                    $where_str .= "$item $value AND ";
                } else if ($value == 'user_id') {
                    if (empty($_SESSION['user_id']))
                        throw new Exception($this->get_exception('0016', __FUNCTION__, __LINE__, 'Session user_id not exist.'));
                    $where_str .= "$item = ".$_SESSION['user_id']." AND ";
                } else if ($l1 == '%') {
                    $where_str .= "$item like '".str_replace("'", "`", $value)."' AND ";
                } else if ($l1 == '(') {
                    $where_str .= "$item in $value AND ";
                } else if ($l2 == 'N(') {
                    $r1 = substr($value, 1);
                    $where_str .= "$item not in $r1 AND ";
                } else if ($l2 == '>=' || $l2 == '<=' || $l2 == '<>') {
                    $r2 = substr($value, 2);
                    $where_str .= "$item $l2 '$r2' AND "; 
                } else if ($l1 == '>' || $l1 == '<') {
                    $r1 = substr($value, 1);
                    if ($r1 == 'Now()')
                        $where_str .= "$item $l1 $r1 AND "; 
                    else
                        $where_str .= "$item $l1 '$r1' AND "; 
                } else {
                    $where_str .= "$item = '".addslashes($value)."' AND ";
                }
            }
        } 
        if ($where_str != NULL)
            $where_str = " WHERE ".rtrim($where_str, 'AND ');
        return $where_str;
    }
    
    private function get_set_str($columnsArr) {
        $set_str = NULL;
        foreach ($columnsArr as $item => $value) {
            if ($value != '') {
                $l1 = substr($value, 0, 1);
                if ($value == 'Now()')
                    $set_str .= "$item=Now(), ";
                else if ($value == 'Curdate()')
                    $set_str .= "$item=Curdate(), ";
                else if ($value == 'NULL')
                    $set_str .= "$item=$value, ";
                else if ($l1 == '|') {
                    $r1 = substr($value, 1);
                    $set_str .= "$item=$r1, ";
                } else
                    $set_str .= "$item='".addslashes($value)."', ";  // $set_str .= "$item='".str_replace("'", "`", $value)."', ";
            } else {
                $set_str .= "$item=NULL, ";
            }
        } 
        if ($set_str != NULL)
            $set_str = " SET ".rtrim($set_str, ', ');
        else
            throw new Exception($this->get_exception('0007', __FUNCTION__, __LINE__, ''));
        return $set_str;
    }
    
    private function get_comma_str($valueArr) {
        $comma_str = NULL;
        foreach ($valueArr as $item => $value) {
            if ($value != '') {
                $comma_str .= "$item, ";
            }
        } 
        if ($comma_str != NULL)
            $comma_str = " (".rtrim($comma_str, ", ").")";
        else
            $comma_str = " ";
        
        return $comma_str;
    }
    
    private function get_commaVal_str($valueArr) {
        $comma_str = NULL;
        foreach ($valueArr as $item => $value) {
            if ($value != '') {
                $l1 = substr($value, 0, 1);
                if ($value == 'Now()')
                    $comma_str .= "$value, ";
                else if ($l1 == '|') {
                    $r1 = substr($value, 1);
                    $comma_str .= "$r1, ";
                } else if ($value == 'Curdate()') {
                    $comma_str .= "$value, ";
                } 
                else
                    $comma_str .= "'".addslashes($value)."', ";
            }
        } 
        if ($comma_str != NULL)
            $comma_str = " (".rtrim($comma_str, ", ").")";
        else
            $comma_str = " ()";
        
        return $comma_str;
    }
    
    public function convert_tStamp ($inputDate) {    
        $outputDate = '';
        $dateSplit = explode("/", $inputDate);
        if (count($dateSplit) == 3) {
            list($day, $month, $year) = $dateSplit;
            $outputDate = $year."-".$month."-".$day;
        }
        return $outputDate;
    }
    
    public function get_date_split ($inputDate) {
        $outputDate = array();
        if (strlen($inputDate) == 23) {
            $dateRangeSplit = explode(" - ", $inputDate);
            if (count($dateRangeSplit)) {
                list($dateFrom, $dateTo) = $dateRangeSplit;
                $outputDate[0] = $this->convert_tStamp($dateFrom);
                $outputDate[1] = $this->convert_tStamp($dateTo);
            }
        }
        return $outputDate;
    }
    
    public function get_sql ($tablename, $param=array()) {
        $s = '';
        if (substr($tablename, 0, 2) == 'vw' || substr($tablename, 0, 2) == 'dt' || substr($tablename, 0, 2) == 'vg') {
            $fn_sql = new Class_sql();
            $s = $fn_sql->get_sql($tablename);
            if (substr($tablename, 0, 2) == 'vg') {
                $s = "SELECT * FROM (".$s.") as mainTable ";
            }          
            if (!empty($param)){
                foreach ($param as $item => $value) {
                    if (strpos($s,"[".$item."]") !== false)
                        $s = str_replace ("[".$item."]", $value, $s);
                }
            }
        } else {
            $s = "SELECT * FROM ".$tablename;
        }
        return $s;
    }
    
    public function get_sql_v2 ($tablename, $param=array()) {
        $s = '';
        if (substr($tablename, 0, 2) == 'vw' || substr($tablename, 0, 2) == 'dt') {
            $fn_sql = new Class_sql();
            $s = $fn_sql->get_sql($tablename);
            if (strpos($s,"[season_id]") !== false) 
                $s = str_replace ("[season_id]", $_SESSION["season_id"], $s);
            if (!empty($param)){
                foreach ($param as $item => $value) {
                    if (strpos($s,"[".$item."]") !== false)
                        $s = str_replace ("[".$item."]", $value, $s);
                }
            }
        } else {
            $s = "SELECT * FROM ".$tablename." ";
        } 
        return $s;
    }
    
    public function db_select($tablename, $columns=array(), $orderby='', $limit='', $throwEmpty=0, $sqlParam=array())
    {
        try { 
            if (!empty($this->DBH)){
                $where_str = "";
                if (!empty($columns)){  $where_str = $this->get_whereAnd_str($columns); } 
                if ($orderby != '')  { $orderby = ' ORDER BY '.$orderby; }
                if ($limit != '')    { $limit = ' LIMIT '.$limit; }
                else                 { $limit = ' '; }
                $sql = $this->get_sql($tablename, $sqlParam).$where_str.$orderby.$limit;
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $stmt = $this->DBH->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);                
                if (empty($result)){
                    if ($throwEmpty == 1)
                        throw new Exception($this->get_exception('0010', __FUNCTION__, __LINE__, 'Select query result empty'));
                    elseif ($throwEmpty == 2) 
                        throw new Exception($this->get_exception('0011', __FUNCTION__, __LINE__, 'Select query result empty'), 30);                    
                } 
                $stmt = null;
                return $result;
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_select_single($tablename, $columns=array(), $orderby='', $throwEmpty=0, $sqlParam=array())
    {
        try { 
            if (!empty($this->DBH)){
                $where_str = "";
                if (!empty($columns)){  $where_str = $this->get_whereAnd_str($columns); }  
                $orderby != '' ? $orderby = ' ORDER BY '.$orderby : $orderby = '';
                $sql = $this->get_sql($tablename, $sqlParam).$where_str.$orderby." LIMIT 1";
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $stmt = $this->DBH->query($sql);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);                
                if (empty($result)){
                    if ($throwEmpty == 1)
                        throw new Exception($this->get_exception('0010', __FUNCTION__, __LINE__, 'Select query result empty'));
                    elseif ($throwEmpty == 2) 
                        throw new Exception($this->get_exception('0011', __FUNCTION__, __LINE__, 'Select query result empty'), 30);                    
                } 
                $stmt = null;
                return $result;
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_select_col ($tablename, $columns, $colOut, $orderby='', $throwEmpty=0, $sqlParam=array())
    {
        try { 
            if (!empty($this->DBH)){
                $where_str = "";
                if (!empty($columns)){  $where_str = $this->get_whereAnd_str($columns); } 
                $orderby != '' ? $orderby = ' ORDER BY '.$orderby : $orderby = '';
                $sql = $this->get_sql($tablename,$sqlParam).$where_str.$orderby." LIMIT 1";
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $stmt = $this->DBH->query($sql);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);                
                if (empty($result)){
                    if ($throwEmpty == 1)
                        throw new Exception($this->get_exception('0010', __FUNCTION__, __LINE__, 'Select query result empty'));
                    elseif ($throwEmpty == 2) 
                        throw new Exception($this->get_exception('0011', __FUNCTION__, __LINE__, 'Select query result empty'), 30);                    
                    else
                        $result = '';                    
                } else {
                    if (array_key_exists($colOut, $result))
                        $result = $result[$colOut];
                    else
                        throw new Exception($this->get_exception('0012', __FUNCTION__, __LINE__, 'Column in result query not found'));   
                }
                $stmt = null;
                return $result;
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_select_colm ($tablename, $columns, $colOut, $orderby='', $throwEmpty=0, $sqlParam=array())
    {
        try { 
            if (!empty($this->DBH)){
                $arrCols = array();
                $where_str = "";
                if (!empty($columns)){  $where_str = $this->get_whereAnd_str($columns); } 
                $orderby != '' ? $orderby = ' ORDER BY '.$orderby : $orderby = '';
                $sql = $this->get_sql($tablename,$sqlParam).$where_str.$orderby;
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $stmt = $this->DBH->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);                
                if (empty($result)){
                    if ($throwEmpty == 1)
                        throw new Exception($this->get_exception('0010', __FUNCTION__, __LINE__, 'Select query result empty'));
                    elseif ($throwEmpty == 2) 
                        throw new Exception($this->get_exception('0011', __FUNCTION__, __LINE__, 'Select query result empty'), 30);    
                } else {
                    foreach ($result as $rows) {       
                        if (array_key_exists($colOut, $rows)) 
                            array_push($arrCols, $rows[$colOut]);
                        else
                            throw new Exception($this->get_exception('0012', __FUNCTION__, __LINE__, 'Column in result query not found'));   
                    }
                }
                $stmt = null;
                return $arrCols;
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_select_cols($tablename, $colOut, $columns=array(), $orderby='', $limit='', $throwEmpty=0, $sqlParam=array())
    {
        try { 
            if (!empty($this->DBH)){
                $where_str = "";
                if (!empty($columns)){  $where_str = $this->get_whereAnd_str($columns); } 
                if ($orderby != '')  { $orderby = ' ORDER BY '.$orderby; }
                if ($limit != '')    { $limit = ' LIMIT '.$limit; }
                else                 { $limit = ' '; }
                $sql = $this->get_sql($tablename, $sqlParam).$where_str.$orderby.$limit;
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $stmt = $this->DBH->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);                
                if (empty($result)){
                    if ($throwEmpty == 1)
                        throw new Exception($this->get_exception('0010', __FUNCTION__, __LINE__, 'Select query result empty'));
                    elseif ($throwEmpty == 2) 
                        throw new Exception($this->get_exception('0011', __FUNCTION__, __LINE__, 'Select query result empty'), 30);                    
                } 
                $stmt = null;
                $arrCols = array();
                foreach ($result as $rows) {       
                    if (array_key_exists($colOut[0], $rows) && array_key_exists($colOut[1], $rows)) 
                        $arrCols[$rows[$colOut[0]]] = $rows[$colOut[1]];
                    else
                        throw new Exception($this->get_exception('0012', __FUNCTION__, __LINE__, 'Column in result query not found'));   
                }
                return $arrCols;
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_select_dTable($tablename, $columns, $limitStart, $sortCol, $sortDir, $sortDef, $sqlParam=array(), $tableLimit='10')
    {
        try { 
            if (!empty($this->DBH)){
                $this->log_debug(__FUNCTION__, __LINE__, $tablename);
                $where_str = "";
                if (!empty($columns)){  $where_str = $this->get_whereAnd_str($columns); } 
                $sortCol == '0' ? $sortCol = $sortDef : $sortCol = $sortCol.' '.$sortDir;
                $limitStart == '' ? $limit = '' : $limit = " LIMIT ".$limitStart.",".$tableLimit;
                $sql = $this->get_sql_v2($tablename, $sqlParam)." ORDER BY ".$sortCol.$limit;
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $stmt = $this->DBH->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                $stmt = null;
                $no = 0;
                foreach ($result as $rows) {         
                    $result[$no++]['row_no'] = $no+intval($limitStart);
                }
                return $result;
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_count($tablename, $columns=array(), $sqlParam=array())
    {
        try {
            if (!empty($this->DBH)){
                $where_str = "";
                if (!empty($columns))  
                    $where_str = $this->get_whereAnd_str($columns); 
                //$sql = "SELECT count(*) FROM ".$this->get_sql($tablename, $sqlParam).$where_str;
                $sql = "SELECT COUNT(*) FROM (".$this->get_sql($tablename, $sqlParam).") aa";
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $stmt = $this->DBH->query($sql);
                $result = $stmt->fetch();
                $stmt = null;
                return $result[0];
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_count_2($tablename, $columns=array())
    {
        try {
            if (!empty($this->DBH)){
                $where_str = "";
                if (!empty($columns))  
                    $where_str = $this->get_whereAnd_str($columns); 
                $sql = "SELECT COUNT(*) FROM ".$tablename." ".$where_str;
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $stmt = $this->DBH->query($sql);
                $result = $stmt->fetch();
                $stmt = null;
                return $result[0];
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_insert($tablename, $columns=NULL)
    {
        try {    
            if (!empty($this->DBH)){
                if (empty($columns))
                    $sql = "INSERT INTO ".$tablename." () VALUES ()";
                else
                    $sql = "INSERT INTO ".$tablename.$this->get_comma_str($columns)." VALUES ".$this->get_commaVal_str($columns);
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $this->DBH->exec($sql);
                return $this->DBH->lastInsertId();
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()), $e->getCode());
        }
    }
    
    public function db_update($tablename, $setArr, $whereArr)
    {
        try {
            if (!empty($this->DBH)){
                if (!empty($whereArr)){
                    $whereStr = $this->get_whereAnd_str($whereArr);
                    if ($whereStr != NULL) {
                        $sql = "UPDATE ".$tablename.$this->get_set_str($setArr).$whereStr;
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                        $stmt = $this->DBH->prepare($sql);
                        $stmt->execute();
                        return $stmt->rowCount();
                    } else
                        throw new Exception($this->get_exception('0014', __FUNCTION__, __LINE__, 'Where String empty'));
                } else
                    throw new Exception($this->get_exception('0015', __FUNCTION__, __LINE__, 'Where String empty'));
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()), $e->getCode());
        }
    }
    
    public function db_delete($tablename, $columns, $whereCustom=NULL)
    {
        try {
            if (!empty($this->DBH)){
                if (!empty($columns)){
                    if (!empty($whereCustom))
                        $sql = "DELETE FROM ".$tablename.' WHERE '.$whereCustom;
                    else {    
                        $whereStr = $this->get_whereAnd_str($columns);
                        if ($whereStr == NULL || $whereStr == '')
                            throw new Exception($this->get_exception('0014', __FUNCTION__, __LINE__, 'Where String empty'));
                        $sql = "DELETE FROM ".$tablename.$whereStr;
                    }
                    $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                    $stmt = $this->DBH->prepare($sql);
                    $stmt->execute();
                    return $stmt->rowCount();
                } else
                    throw new Exception($this->get_exception('0014', __FUNCTION__, __LINE__, 'Where String empty'));
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_insert_custom($tablename, $columns=NULL, $selectCustom=NULL)
    {
        try {    
            if (!empty($this->DBH)){
                if (empty($columns) || empty($selectCustom))
                    throw new Exception($this->get_exception('0013', __FUNCTION__, __LINE__, 'Parameters empty'));  
                else
                    $sql = "INSERT INTO ".$tablename." ".$columns." ".$selectCustom;
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                $this->DBH->exec($sql);
                return $this->DBH->lastInsertId();
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()), $e->getCode());
        }
    }
    
    public function db_create_table($sql)
    {
        try {
            if (!empty($this->DBH)){
                if (!empty($sql)){
                    $this->fn_general->log_debug(__FUNCTION__, __LINE__, $sql);
                    $stmt = $this->DBH->prepare($sql);
                    $stmt->execute();
                    return '1';
                } else
                    throw new Exception($this->get_exception('0015', __FUNCTION__, __LINE__, 'SQL String empty'));
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_beginTransaction() {   
        try {
            if (!empty($this->DBH)){
                $this->DBH->beginTransaction();
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_commit() {   
        try {
            if (!empty($this->DBH)){
                $this->DBH->commit();
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
        
    public function db_connect() {   
        try {
            $config = parse_ini_file('library/config.ini');
            //$config = parse_ini_file('../library/config_2.ini');
            $dbname = $config['dbname'];    
            $dbhost = $config['dbhost'];    
            $this->DBH = new PDO("mysql:host=$dbhost;dbname=$dbname", $config['username'], $config['password']);
            //$this->DBH = new PDO("mysql:host=10.16.41.129;dbname=$dbname", $config['username'], $config['password']);
            $this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_rollback() {   
         try {
            if (!empty($this->DBH)){
                $this->DBH->rollBack();
            } else {
                throw new Exception($this->get_exception('0006', __FUNCTION__, __LINE__, 'Connection lost'));
            }
        }
        catch(PDOException $e) {
            throw new Exception($this->get_exception('0005', __FUNCTION__, __LINE__, $e->getMessage()));
        }
    }
    
    public function db_close() {  
        $this->DBH = null;
    }
}
?>
