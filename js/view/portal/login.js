document.addEventListener('DOMContentLoaded', function() {

    ShowLoader();
    setTimeout(function (){
        HideLoader();
    }, 300);

    // logout notification
    if ($('#logoutReason').val() === '0')
        toastr['success'](_ALERT_LOGOUT, _ALERT_SUCCESS);
    else if ($('#logoutReason').val() === '1')
        toastr['error'](_ALERT_LOGOUT_ERROR, _ALERT_ERROR);
    else if ($('#logoutReason').val() === '2')
        toastr['error'](_ALERT_LOGOUT_TIMEOUT, _ALERT_ERROR);

    const vDataLgn = [
        {
            field_id: 'txtLgnUsername',
            type: 'text',
            name: 'User ID',
            validator: {
                notEmpty: true,
                maxLength: 30
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
                    // get token and store to sessionStorage
                    let data = {username : $('#txtLgnUsername').val(), password : $('#txtLgnPassword').val()};
                    sessionStorage.setItem('token', 'uiiuiu');
                    const respLogin = mzAjaxRequest('login.php', 'POST', data);
                    
                    //localStorage.setItem('userInfo', JSON.stringify(user));
                    //window.location.href = '../home/home.html';
                } else {
                    toastr['error'](_VALIDATION_MSG, _VALIDATION_ERROR)
                }
            } catch (e) {
                if (e.message !== 'error')   toastr['error'](e.message, _ALERT_ERROR);
            }
            HideLoader();
        }, 300);
    });

});