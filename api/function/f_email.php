<?php
require_once 'f_general.php';

/* Error code range - 0300 */ 
class Class_email {
     
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
               
    public function setup_email ($userId='', $emailTemplateId=0, $emailParam=array()) {
        try {
            $this->fn_general->log_debug(__FUNCTION__, __LINE__, 'Entering register_user()');
            if (empty($userId)) {
                throw new Exception('(ErrCode:0302) [' . __LINE__ . '] - Parameter userId empty');   
            }   
            if (empty($emailTemplateId)) {
                throw new Exception('(ErrCode:0303) [' . __LINE__ . '] - Parameter emailTemplateId empty');   
            }   
            if (empty($emailParam)) {
                throw new Exception('(ErrCode:0304) [' . __LINE__ . '] - Array emailParam empty');   
            } 
            
            $sys_user = Class_db::getInstance()->db_select_single('sys_user', array('user_id'=>$userId), NULL, 1);
            $email_template = Class_db::getInstance()->db_select_single('email_template', array('email_template_id'=>$emailTemplateId), NULL, 1); 
            $emailTitle = $email_template['email_template_title'];
            $emailHtml = $email_template['email_template_html'];
            
            $arr_parameter = Class_db::getInstance()->db_select('email_parameter', array('email_template_id'=>$emailTemplateId), NULL, NULL, 1);
            foreach ($arr_parameter as $parameter) {
                $paramCode = $parameter['email_param_code'];
                if (!array_key_exists($paramCode, $emailParam)) {
                    throw new Exception('(ErrCode:0306) [' . __LINE__ . '] - Index '.paramCode.' in array emailParam empty');  
                } 
                if (strpos($emailTitle,"[".$item."]") !== false) {
                    $emailTitle = str_replace ("[".$paramCode."]", $emailParam[$paramCode], $emailTitle);
                }
                if (strpos($emailHtml,"[".$item."]") !== false) {
                    $emailHtml = str_replace ("[".$paramCode."]", $emailParam[$paramCode], $emailHtml);
                }
            }
            
            Class_db::getInstance()->db_insert('email_send', array('email_template_id'=>$emailTemplateId, 'email_address'=>$sys_user['user_email'], 'email_title'=>$emailTitle,
                'email_html'=>$emailHtml, 'user_id'=>$userId));
            return true;
        }
        catch(Exception $ex) {   
            throw new Exception($this->get_exception('0301', __FUNCTION__, __LINE__, $ex->getMessage()), $ex->getCode());
        }
    }
    
}