<?php
require_once 'f_general.php';

/* Error code range - 0200 */ 
class Class_user {
     
    private $fn_general;
    
    function __construct()
    {
        $this->fn_general = new Class_general();
    }
    
    private function get_exception($codes, $function, $line, $msg) {
        if ($msg != '') {            
            $pos = strpos($msg,'-');
            if ($pos !== false) {   
                $msg = substr($msg, $pos+2); 
            }
            return "(ErrCode:".$codes.") [".__CLASS__.":".$function.":".$line."] - ".$msg;
        } 
        else {
            return "(ErrCode:".$codes.") [".__CLASS__.":".$function.":".$line."]";
        }
    }
    
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        else {
            throw new Exception($this->get_exception('0001', __FUNCTION__, __LINE__, 'Get Property not exist ['.$property.']'));
        }
    }

    public function __set( $property, $value ) {
        if (property_exists($this, $property)) {
            $this->$property = $value;        
        }
        else {
            throw new Exception($this->get_exception('0002', __FUNCTION__, __LINE__, 'Get Property not exist ['.$property.']'));
        }
    }
    
    public function __isset( $property ) {
        if (property_exists($this, $property)) {
            return isset($this->$property);
        }
        else {
            throw new Exception($this->get_exception('0003', __FUNCTION__, __LINE__, 'Get Property not exist ['.$property.']'));
        }
    }
    
    public function __unset( $property ) {
        if (property_exists($this, $property)) {
            unset($this->$property);
        }
        else {
            throw new Exception($this->get_exception('0004', __FUNCTION__, __LINE__, 'Get Property not exist ['.$property.']'));
        }
    }
               
    public function register_user ($userDetails=array(), $type='') {
        try {
            if (empty($userDetails)) {
                throw new Exception('(ErrCode:0201) [' . __LINE__ . '] - Array userDetails empty');   
            }     
            if (!array_key_exists('userFirstName', $userDetails)) {
                throw new Exception('(ErrCode:0202) [' . __LINE__ . '] - Index userFirstName in array userDetails empty');  
            }  
            if (!array_key_exists('userLastName', $userDetails)) {
                throw new Exception('(ErrCode:0203) [' . __LINE__ . '] - Index userLastName in array userDetails empty');  
            }
            return $userId;
        }
        catch(Exception $ex) {   
            $this->fn_general->log_error(__FUNCTION__, __LINE__, $ex->getMessage());
            throw new Exception($this->get_exception('0202', __FUNCTION__, __LINE__, $ex->getMessage()), $ex->getCode());
        }
    }
    
}