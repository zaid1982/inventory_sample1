document.addEventListener('DOMContentLoaded', function() {

    ShowLoader();
    setTimeout(function (){
        HideLoader();
    }, 200);

    // display notification
    const getVar = getUrlVars()['f'];
    if (typeof getVar !== 'undefined') {
        switch (getVar) {
            case '0':
                toastr['success'](_ALERT_MSG_SUCCESS_LOGOUT, _ALERT_TITLE_SUCCESS_LOGOUT);
                break;
            case '1':
                toastr['error'](_ALERT_MSG_ERROR_LOGOUT, _ALERT_TITLE_ERROR);
                break;
            case '2':
                toastr['error'](_ALERT_MSG_ERROR_TIMEOUT, _ALERT_TITLE_ERROR_TIMEOUT);
                break;
            case '3':
                toastr['success'](_ALERT_MSG_SUCCESS_REGISTER, _ALERT_TITLE_SUCCESS_REGISTER);
                break;
        }
    }
    
    // activate account
    const getVarKey = getUrlVars()['key'];
    if (typeof getVarKey !== 'undefined') {
        setTimeout(function (){
            try {
                let data = {
                    action : 'activate', 
                    activationInput : getVarKey
                };
                mzAjaxRequest('register.php', 'POST', data);   
                toastr['success'](_ALERT_MSG_SUCCESS_ACTIVATE, _ALERT_TITLE_SUCCESS_ACTIVATE);
            } catch (e) {
                toastr['error'](e.message, _ALERT_TITLE_ERROR_ACTIVATE);
            }
            HideLoader();
        }, 300);        
    }

    const vDataLgn = [
        {
            field_id: 'txtLgnUsername',
            type: 'text',
            name: 'User ID',
            validator: {
                notEmpty: true,
                maxLength: 100,
                email: true
            }
        },
        {
            field_id: 'txtLgnPassword',
            type: 'text',
            name: 'Password',
            validator: {
                notEmpty: true,
                maxLength: 20,
                minLength: 6
            }
        }
    ];

    let formLgnLoginValidate = new mzValidate('formLgnLogin');
    formLgnLoginValidate.registerFields(vDataLgn);
    
    $('#formLgnLogin').on('keyup', function() {
        $('#btnLgnSignIn').attr('disabled', !formLgnLoginValidate.validateForm());
    });

    $('#btnLgnSignIn').on('click', function () {
        ShowLoader();
        setTimeout(function (){
            try {
                if (formLgnLoginValidate.validateForm()) {
                    let data = {
                        username : $('#txtLgnUsername').val(), 
                        password : $('#txtLgnPassword').val()
                    };
                    const respLogin = mzAjaxRequest('login.php', 'POST', data);                   
                    sessionStorage.setItem('token', respLogin.token); 
                    localStorage.setItem('userInfo', JSON.stringify(respLogin));
                    window.location.href = '../home/home.html';
                } else {
                    toastr['error'](_ALERT_MSG_VALIDATION, _ALERT_TITLE_VALIDATION_ERROR);
                }
            } catch (e) {
                toastr['error'](e.message, _ALERT_TITLE_ERROR_LOGIN);
            }
            HideLoader();
        }, 300);
    });

});