<?php

class Class_sql {
     
    function __construct()
    {
        // 1010 - 1019
    }
    
    private function get_exception($codes, $function, $line, $msg) {
        if ($msg != '') {            
            $pos = strpos($msg,'-');
            if ($pos !== false)   
                $msg = substr($msg, $pos+2); 
            return "(ErrCode:".$codes.") [".__CLASS__.":".$function.":".$line."] - ".$msg;
        } else
            return "(ErrCode:".$codes.") [".__CLASS__.":".$function.":".$line."]";
    }
    
    public function get_sql ($title) {
        try {     
            if ($title == 'vw_roles') { 
                $sql = "SELECT
                        ref_role.role_id, ref_role.role_desc
                    FROM sys_user_role
                    INNER JOIN ref_role ON sys_user_role.role_id = ref_role.role_id AND role_status = 1";
            } else
                throw new Exception($this->get_exception('0098', __FUNCTION__, __LINE__, 'Sql not exist : '.$title)); 
            return $sql;
        }
        catch(Exception $e) {
            if ($e->getCode() == 30) { $errCode = 32; } else { $errCode = $e->getCode(); }
            throw new Exception($this->get_exception('0099', __FUNCTION__, __LINE__, $e->getMessage()), $errCode);
        }
    }
    
}

?>
