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
        $action = filter_input(INPUT_POST, 'action');   
        if (empty($action)) { 
            throw new Exception('(ErrCode:2101) [' . __LINE__ . '] - Parameter action empty');         
        } 
        $result = '';
        
        if ($action === 'register') {
            $userFirstName = filter_input(INPUT_POST, 'userFirstName');
            $userLastName = filter_input(INPUT_POST, 'userLastName');
            $userEmail = filter_input(INPUT_POST, 'userEmail');
            $userMykadNo = filter_input(INPUT_POST, 'userMykadNo');
            $userProfileContactNo = filter_input(INPUT_POST, 'userProfileContactNo');
            $userPassword = filter_input(INPUT_POST, 'userPassword');

            if (is_null($userFirstName) || $userFirstName === '') { 
                throw new Exception('(ErrCode:2103) [' . __LINE__ . '] - Field First Name empty', 31);         
            } 
            if (is_null($userLastName) || $userLastName === '') { 
                throw new Exception('(ErrCode:2104) [' . __LINE__ . '] - Field Last Name empty', 31);         
            } 
            if (is_null($userEmail) || $userEmail === '') { 
                throw new Exception('(ErrCode:2105) [' . __LINE__ . '] - Field Email empty', 31);         
            }  
            if (is_null($userMykadNo) || $userMykadNo === '') { 
                throw new Exception('(ErrCode:2106) [' . __LINE__ . '] - Field MyKad Number empty', 31);         
            }  
            if (is_null($userProfileContactNo) || $userProfileContactNo === '') { 
                throw new Exception('(ErrCode:2107) [' . __LINE__ . '] - Field Phone Number empty', 31);         
            }  
            if (is_null($userPassword) || $userPassword === '') { 
                throw new Exception('(ErrCode:2108) [' . __LINE__ . '] - Field Password empty', 31);         
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
            $result_register = $fn_user->register_user($userDetails, 2);
            $userId = $result_register['userId'];
            $activationKey = $result_register['activationKey'];

            $emailParam = array('fullname'=>$userFirstName.' '.$userLastName, 'username'=>$userEmail, 'activation_key'=>$activationKey); 
            $fn_email->setup_email($userId, 1, $emailParam);

            $fn_general->save_audit('3', $userId);

            Class_db::getInstance()->db_commit();        
            Class_db::getInstance()->db_close();
        }
        else if ($action === 'activate') {
            $activationInput = filter_input(INPUT_POST, 'activationInput');
             
            if (is_null($activationInput) || $activationInput === '') { 
                throw new Exception('(ErrCode:2109) [' . __LINE__ . '] - Parameter activationInput empty');         
            } 
                        
            Class_db::getInstance()->db_connect();
            Class_db::getInstance()->db_beginTransaction();
            $is_transaction = true;
            
            $userId = $fn_user->activate_user($activationInput);
            
            $fn_general->save_audit('5', $userId);
            
            Class_db::getInstance()->db_commit();        
            Class_db::getInstance()->db_close();
        }
        else if ($action === 'forgot_password') {
            $username = filter_input(INPUT_POST, 'username');
            
            if (is_null($username) || $username === '') { 
                throw new Exception('(ErrCode:2110) [' . __LINE__ . '] - Field User ID empty', 31);         
            } 
            
            Class_db::getInstance()->db_connect();
            Class_db::getInstance()->db_beginTransaction();
            $is_transaction = true;
                        
            $sys_user = Class_db::getInstance()->db_select_single('sys_user', array('user_email'=>$username));
            if (empty($sys_user)) {
                throw new Exception('(ErrCode:2111) [' . __LINE__ . '] - User ID not exist', 31);
            }
            
            $userId = $sys_user['user_id'];
            $temporaryPassword = $fn_general->generateRandomString(15);
            Class_db::getInstance()->db_update('sys_user', array('user_password'=>md5($temporaryPassword)), array('user_id'=>$userId));
            
            $emailParam = array('fullname'=>$sys_user['user_first_name'].' '.$sys_user['user_last_name'], 'username'=>$username, 'temporary_password'=>$temporaryPassword); 
            $fn_email->setup_email($userId, 2, $emailParam);

            $fn_general->save_audit('4', $userId);

            Class_db::getInstance()->db_commit();        
            Class_db::getInstance()->db_close();
        } else {
            throw new Exception('(ErrCode:2102) [' . __LINE__ . '] - Parameter action invalid ('.$action.')');        
        }
        
        $form_data['result'] = $result;
        $form_data['success'] = true;
        $fn_general->log_debug($api_name, __LINE__, 'Result = '.print_r($result, true));
    } else {
        throw new Exception('(ErrCode:2100) [' . __LINE__ . '] - Wrong Request Method');   
    }
} catch (Exception $ex) {
    if ($is_transaction) {
        Class_db::getInstance()->db_rollback();
    }
    Class_db::getInstance()->db_close();
    $form_data['error'] = substr($ex->getMessage(), strpos($ex->getMessage(), '] - ') + 4);
    if ($ex->getCode() === 31) {
        $form_data['errmsg'] = substr($ex->getMessage(), strpos($ex->getMessage(), '] - ') + 4);
    } else {
        $form_data['errmsg'] = 'Processing error. Please contact Administrator!';
    }
    $fn_general->log_error($api_name, __LINE__, $ex->getMessage());
}

echo json_encode($form_data);