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
                        ref_role.role_id AS roleId, 
                        ref_role.role_desc AS roleDesc
                    FROM sys_user_role
                    INNER JOIN ref_role ON sys_user_role.role_id = ref_role.role_id AND role_status = 1";
            } else if ($title == 'vw_menu') { 
                $sql = "SELECT 
                        sys_nav.nav_id,
                        sys_nav.nav_desc,
                        sys_nav.nav_icon,
                        sys_nav.nav_page,
                        sys_nav_second.nav_second_id,
                        sys_nav_second.nav_second_desc,
                        sys_nav_second.nav_second_page
                    FROM
                        (SELECT
                                nav_id, nav_second_id, MIN(nav_role_turn) AS turn
                        FROM sys_nav_role
                        WHERE role_id IN ([roles])
                        GROUP BY nav_id, nav_second_id) AS nav_role
                    LEFT JOIN sys_nav ON sys_nav.nav_id = nav_role.nav_id
                    LEFT JOIN sys_nav_second ON sys_nav_second.nav_second_id = nav_role.nav_second_id
                    WHERE nav_status = 1  AND (ISNULL(sys_nav_second.nav_second_id) OR nav_second_status = 1)
                    ORDER BY nav_role.turn";
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
