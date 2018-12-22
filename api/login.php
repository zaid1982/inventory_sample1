<?php
require_once 'function/db.php';
require_once 'function/f_general.php';
require_once 'function/f_login.php';

$fn_general = new Class_general();
$fn_login = new Class_login();
$api_name = 'api_login';
$is_transaction = false;
$form_data = array('success'=>false, 'result'=>'', 'error'=>'', 'errmsg'=>'');

/* Error code range - 2000 */ 
try {   
    $request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD'); 
    $fn_general->log_debug($api_name, __LINE__, 'Request method = '.$request_method);
    
    //$headers = apache_request_headers();
    //$fn_general->log_debug($api_name, __LINE__, $headers['Authorization']);
    
    if ('POST' === $request_method) {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');        
        
        Class_db::getInstance()->db_connect();
        
        $sys_user = Class_db::getInstance()->db_select_single('sys_user', array('user_email'=>$username));
        if (empty($sys_user)) {
            throw new Exception('(ErrCode:2001) [' . __LINE__ . '] - User ID not exist', 31);
        } else if ($sys_user['user_password'] !== md5($password)) {
            throw new Exception('(ErrCode:2002) [' . __LINE__ . '] - Incorrect password', 31);
        } else if ($sys_user['user_status'] !== '1') {
            throw new Exception('(ErrCode:2003) [' . __LINE__ . '] - User ID inactive. Please contact adminsitrator.', 31);
        }
        
        $user_id = $sys_user['user_id'];
        $group_id = $sys_user['group_id'];
            
        $token = $fn_login->create_jwt($username);        
        $arr_roles = Class_db::getInstance()->db_select('vw_roles', array('sys_user_role.user_id'=>$user_id));
        $sys_group = Class_db::getInstance()->db_select_single('sys_group', array('group_id'=>$group_id), NULL, 1);
                
        $result['token'] = $token;
        $result['userId'] = $user_id;
        $result['userFirstName'] = $sys_user['user_first_name'];
        $result['userLastName'] = $sys_user['user_last_name'];
        $result['userType'] = $sys_user['user_type'];
        $result['isFirstTime'] = is_null($sys_user['user_time_activate']) ? 'Yes' : 'No';
        $result['userVersion'] = $sys_user['user_version'];
        $result['userMenuVersion'] = $sys_user['user_menu_version'];
        $result['roles'] = $arr_roles;
        $result['group']['groupId'] = $sys_group['group_id'];
        $result['group']['groupName'] = $sys_group['group_name'];
        $result['group']['groupType'] = $sys_group['group_type'];
        $result['group']['groupRegNo'] = $fn_general->clear_null($sys_group['group_reg_no']);
        $result['group']['groupStatus'] = $sys_group['group_status'];
        $result['menu'] = $fn_login->get_menu_list($arr_roles);        
        
        Class_db::getInstance()->db_beginTransaction();
        $is_transaction = true;
        
        $fn_general->save_audit('1', $user_id);
        
        Class_db::getInstance()->db_commit();        
        Class_db::getInstance()->db_close();
        
        $form_data['result'] = $result;
        $form_data['success'] = true;
    } 
    else {
        throw new Exception('(ErrCode:2000) [' . __LINE__ . '] - Wrong Request Method');   
    }
} catch (Exception $ex) {
    if ($is_transaction) {
        Class_db::getInstance()->db_rollback();
    }
    Class_db::getInstance()->db_close();
    $form_data['error'] = substr($ex->getMessage(), strpos($ex->getMessage(), '] - ') + 4);
    if ($ex->getCode() == 31) {
        $form_data['errmsg'] = substr($ex->getMessage(), strpos($ex->getMessage(), '] - ') + 4);
    } else {
        $form_data['errmsg'] = 'Processing error. Please contact Administrator!';
    }
    $fn_general->log_error($api_name, __LINE__, $ex->getMessage());
}

echo json_encode($form_data);
