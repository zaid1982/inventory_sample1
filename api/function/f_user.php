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
               
    public function register_user ($userDetails=array(), $type=0) {
        try {
            $this->fn_general->log_debug(__FUNCTION__, __LINE__, 'Entering register_user()');
            if (empty($userDetails)) {
                throw new Exception('(ErrCode:0202) [' . __LINE__ . '] - Array userDetails empty');   
            }     
            if (empty($type)) {
                throw new Exception('(ErrCode:0203) [' . __LINE__ . '] - Parameter type empty');   
            }     
            if (!array_key_exists('userFirstName', $userDetails)) {
                throw new Exception('(ErrCode:0204) [' . __LINE__ . '] - Index userFirstName in array userDetails empty');  
            }  
            if (!array_key_exists('userLastName', $userDetails)) {
                throw new Exception('(ErrCode:0205) [' . __LINE__ . '] - Index userLastName in array userDetails empty');  
            } 
            if (!array_key_exists('userEmail', $userDetails)) {
                throw new Exception('(ErrCode:0206) [' . __LINE__ . '] - Index userEmail in array userDetails empty');  
            } 
            if (!array_key_exists('userMykadNo', $userDetails)) {
                throw new Exception('(ErrCode:0207) [' . __LINE__ . '] - Index userMykadNo in array userDetails empty');  
            } 
            if (!array_key_exists('userProfileContactNo', $userDetails)) {
                throw new Exception('(ErrCode:0208) [' . __LINE__ . '] - Index userProfileContactNo in array userDetails empty');  
            } 
            if (!array_key_exists('userPassword', $userDetails)) {
                throw new Exception('(ErrCode:0209) [' . __LINE__ . '] - Index userPassword in array userDetails empty');  
            }            
            
            $userFirstName = $userDetails['userFirstName'];
            $userLastName = $userDetails['userLastName'];
            $userEmail = $userDetails['userEmail'];
            $userMykadNo = $userDetails['userMykadNo'];
            $userProfileContactNo = $userDetails['userProfileContactNo'];
            $userPassword = $userDetails['userPassword'];
            
            // insert sys_user
            // insert sys_user_profile
            // insert sys_user_role
            // insert sys_group_user
            // insert wfl_task_user
            
            if (Class_db::getInstance()->db_count('sys_user', array('user_email'=>$userEmail)) > 0) {
                throw new Exception('(ErrCode:0210) [' . __LINE__ . '] - Email already exist. Please use different email.', 31);                     
            }
            
            return 'fdfdfd';
        }
        catch(Exception $ex) {   
            throw new Exception($this->get_exception('0201', __FUNCTION__, __LINE__, $ex->getMessage()), $ex->getCode());
        }
    }
    
}