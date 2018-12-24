<?php
require_once 'f_general.php';
require_once 'src/BeforeValidException.php';
require_once 'src/ExpiredException.php';
require_once 'src/SignatureInvalidException.php';
require_once 'src/JWT.php';

use \Firebase\JWT\JWT;

/* Error code range - 0100 */ 
class Class_login {
     
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
               
    public function create_jwt ($username='') {
        try {
            if ($username === '') {
                throw new Exception('(ErrCode:0101) [' . __LINE__ . '] - Parameter username empty');   
            }
            $key = "inventory_sample1";
            $token = array('iss'=>'inventory_sample1/jwt', 'username'=>$username, 'iat'=>time(), 'exp'=>time()+10);
            $jwt = JWT::encode($token, $key);              
            return $jwt;
        }
        catch(Exception $ex) {   
            $this->fn_general->log_error(__FUNCTION__, __LINE__, $ex->getMessage());
            throw new Exception($this->get_exception('0102', __FUNCTION__, __LINE__, $ex->getMessage()), $ex->getCode());
        }
    }
    
    public function check_jwt ($jwt='') {
        try {
            if ($jwt === '') {
                throw new Exception('(ErrCode:0103) [' . __LINE__ . '] - Parameter jwt empty');   
            }
            
            $key = "inventory_sample1";
            JWT::$leeway = 60; // $leeway in seconds
            $data = JWT::decode($jwt, $key, array('HS256'));
            return $data;
        }
        catch(Exception $ex) {   
            $this->fn_general->log_error(__FUNCTION__, __LINE__, $ex->getMessage());
            throw new Exception($this->get_exception('0104', __FUNCTION__, __LINE__, $ex->getMessage()), $ex->getCode());
        }
    }
    
    public function get_menu_list ($arr_roles='') {
        try {
            if ($arr_roles === '') {
                throw new Exception('(ErrCode:0105) [' . __LINE__ . '] - Parameter arr_roles empty');  
            }
            if (empty($arr_roles)) {
                throw new Exception('(ErrCode:0106) [' . __LINE__ . '] - Array arr_roles empty');  
            }
            
            $this->fn_general->log_debug(__FUNCTION__, __LINE__, count($arr_roles));
            $role_list = array();
            foreach ($arr_roles as $roles) {
                array_push($role_list, $roles['roleId']);
            }
            $this->fn_general->log_debug(__FUNCTION__, __LINE__, 'Roles = '.$role_list[0]);
            $role_str = implode(',', $role_list);            
            $this->fn_general->log_debug(__FUNCTION__, __LINE__, $role_str);
            
            $menu_return = [];
            $nav_index = 0;
            $menu_list = Class_db::getInstance()->db_select('vw_menu', NULL, NULL, NULL, 1, array('roles'=>$role_str));
            foreach ($menu_list as $menu) {                
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, '$nav_page = '.$menu['nav_page']);
                $this->fn_general->log_debug(__FUNCTION__, __LINE__, '$nav_index = '.$nav_index);
                if (!is_null($menu['nav_page'])) {
                    array_push($menu_return, array('navId'=>$menu['nav_id'], 'navDesc'=>$menu['nav_desc'], 'navIcon'=>$menu['nav_icon'], 'navPage'=>$menu['nav_page'], 'navSecond'=>array()));
                    $nav_index++;
                } 
                else if (!is_null($menu['nav_second_id'])) {
                    array_push($menu_return[$nav_index-1]['navSecond'], array('navSecondId'=>$menu['nav_second_id'], 'navSecondDesc'=>$menu['nav_second_desc'], 'navSecondPage'=>$menu['nav_second_page']));
                }
            }
            return $menu_return;
        } catch (Exception $ex) {
            $this->fn_general->log_error(__FUNCTION__, __LINE__, $ex->getMessage());
            throw new Exception($this->get_exception('0107', __FUNCTION__, __LINE__, $ex->getMessage()), $ex->getCode());
        }
    }
    
}