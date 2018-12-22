<?php

class Class_general {
     
    private $log_dir = '';
    
    function __construct()
    {
        $config = parse_ini_file('library/config.ini');
        $this->log_dir = $config['log_dir'];
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
           
    public function log_debug ($function, $line, $msg) {
        $debugMsg = date("Y/m/d h:i:sa")." [".__CLASS__.":".$function.":".$line."] - ".$msg."\r\n";
        error_log($debugMsg, 3, $this->log_dir.'/debug/debug_'.date("Ymd").'.log');
    }
    
    public function log_error ($function, $line, $msg) {
        $debugMsg = date("Y/m/d h:i:sa")." [".__CLASS__.":".$function.":".$line."] - ".$msg."\r\n";
        error_log($debugMsg, 3, $this->log_dir.'/error/error'.date("Ymd").'.log');
    }
    
    public function clear_null ($param) {
        try {
            if (is_null($param)) {
                return '';
            }
            return $param;
        }
        catch(Exception $ex) {
            $this->log_error(__FUNCTION__, __LINE__, $ex->getMessage());            
            throw new Exception($this->get_exception('0051', __FUNCTION__, __LINE__, $ex->getMessage()), $ex->getCode());
        }
    }
    
    public function save_audit ($audit_action_id='', $user_id='', $remark='') {
        try {
            if ($audit_action_id == '') {
                throw new Exception('(ErrCode:0052) [' . __LINE__ . '] - Parameter audit_action_id empty');   
            }
            $place = '';
            $ipaddress = '';
            $this->log_debug(__FUNCTION__, __LINE__, 'Insert Audit Trail, audit_action_id = '.$audit_action_id.', user_id = '.$user_id.', remark = '.$remark);
            if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']!='') {
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']!='') {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if(isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED']!='') {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            } else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR']!='') {
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            } else if(isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED']!='') {
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            } else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']!='') {
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            } else {
                $ipaddress = 'UNKNOWN';
            }
            if (!in_array($ipaddress, array('', 'UNKNOWN', '::1'), true)) {
                $details = json_decode(file_get_contents("http://ipinfo.io/$ipaddress/json"));
                if (isset($details->city)) {
                    $place = $details->city;
                }
            }
            return Class_db::getInstance()->db_insert('sys_audit', array('audit_action_id'=>$audit_action_id, 'user_id'=>$user_id, 'audit_ip'=>$ipaddress, 'audit_place'=>$place, 'audit_remark'=>$remark));
        }
        catch(Exception $ex) {
            $this->log_error(__FUNCTION__, __LINE__, $ex->getMessage());            
            throw new Exception($this->get_exception('0053', __FUNCTION__, __LINE__, $ex->getMessage()), $ex->getCode());
        }
    }
    
}