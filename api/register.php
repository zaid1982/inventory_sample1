<?php
require_once 'function/db.php';
require_once 'function/f_general.php';
require_once 'function/f_user.php';
require_once 'function/f_email.php';

$fn_general = new Class_general();
$fn_user = new Class_user();
$fn_email = new Class_email();
$api_name = 'api_user';
$is_transaction = false;
$form_data = array('success'=>false, 'result'=>'', 'error'=>'', 'errmsg'=>'');

/* Error code range - 2100 */ 
try {  
    $request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD'); 
    $fn_general->log_debug($api_name, __LINE__, 'Request method = '.$request_method);
    
    if ('POST' === $request_method) {
        $userFirstName = filter_input(INPUT_POST, 'userFirstName');
        $userLastName = filter_input(INPUT_POST, 'userLastName');
        $userEmail = filter_input(INPUT_POST, 'userEmail');
        $userMykadNo = filter_input(INPUT_POST, 'userMykadNo');
        $userProfileContactNo = filter_input(INPUT_POST, 'userProfileContactNo');
        $userPassword = filter_input(INPUT_POST, 'userPassword');
        
        if (is_null($userFirstName) || $userFirstName === '') { 
            throw new Exception('(ErrCode:2101) [' . __LINE__ . '] - First Name empty', 31);         
        } 
        if (is_null($userLastName) || $userLastName === '') { 
            throw new Exception('(ErrCode:2102) [' . __LINE__ . '] - Last Name empty', 31);         
        } 
        if (is_null($userEmail) || $userEmail === '') { 
            throw new Exception('(ErrCode:2103) [' . __LINE__ . '] - Email empty', 31);         
        }  
        if (is_null($userMykadNo) || $userMykadNo === '') { 
            throw new Exception('(ErrCode:2104) [' . __LINE__ . '] - MyKad Number empty', 31);         
        }  
        if (is_null($userProfileContactNo) || $userProfileContactNo === '') { 
            throw new Exception('(ErrCode:2105) [' . __LINE__ . '] - Phone Number empty', 31);         
        }  
        if (is_null($userPassword) || $userPassword === '') { 
            throw new Exception('(ErrCode:2106) [' . __LINE__ . '] - Password empty', 31);         
        }      
        
        Class_db::getInstance()->db_connect();
        Class_db::getInstance()->db_beginTransaction();
        $is_transaction = true;
        
        $userDetails = array(
            'userFirstName'=>$userFirstName,
            'userLastName'=>$userLastName,
            'userEmail'=>$userEmail,
            'userMykadNo'=>$userMykadNo,
            'userProfileContactNo'=>$userProfileContactNo,
            'userPassword'=>$userPassword
        );
        $result_register = $fn_user->register_user($userDetails, 1);
        $userId = $result_register['userId'];
        $activation_key = $result_register['activation_key'];
                
        $emailParam = array('fullname'=>$userFirstName.' '.$userLastName, 'username'=>$userEmail, 'activation_key'=>$activation_key); 
        $fn_email->setup_email($userId, 1, $emailParam);
        
        $fn_general->save_audit('3', $userId);
        
        Class_db::getInstance()->db_commit();        
        Class_db::getInstance()->db_close();
        
        $form_data['result'] = $result;
        $form_data['success'] = true;
        $fn_general->log_debug($api_name, __LINE__, 'Result = '.print_r($result, true));
    }
    else {
        throw new Exception('(ErrCode:2100) [' . __LINE__ . '] - Wrong Request Method');   
    }
} catch (Exception $ex) {
    if ($is_transaction) {
        Class_db::getInstance()->db_rollback();
    }
    Class_db::getInstance()->db_close();
    $form_data['error'] = substr($ex->getMessage(), strpos($ex->getMessage(), '] - ') + 4);
    if ($ex->getCode() == 31) {
        $form_data['errmsg'] = substr($ex->getMessage(), strpos($ex->getMessage(), '] - ') + 4);
    } 
    else {
        $form_data['errmsg'] = 'Processing error. Please contact Administrator!';
    }
    $fn_general->log_error($api_name, __LINE__, $ex->getMessage());
}

echo json_encode($form_data);