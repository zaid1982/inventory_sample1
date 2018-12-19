<?php
require_once 'function/general.php';

$fn_general = new Class_general();
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
        
        $form_data['result'] = $username;
        $form_data['success'] = true;
    } 
    else {
        throw new Exception('(ErrCode:2000) [' . __LINE__ . '] - Wrong Request Method');   
    }
} catch (Exception $ex) {
    if ($is_transaction) {
        //Class_db::getInstance()->db_rollback();
    }
    //Class_db::getInstance()->db_close();
    $form_data['error'] = substr($ex->getMessage(), strpos($ex->getMessage(), '] - ') + 4);
    if ($ex->getCode() == 31) {
        $form_data['errmsg'] = substr($ex->getMessage(), strpos($ex->getMessage(), '] - ') + 4);
    } else {
        $form_data['errmsg'] = 'Processing error. Please contact Administrator!';
    }
    $fn_general->log_error($api_name, __LINE__, $ex->getMessage());
}

echo json_encode($form_data);
