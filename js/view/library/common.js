const _ALERT_ERROR = "ERROR";
const _ALERT_ERROR_MSG_DEFAULT = "Error on system. Please contact Administrator!";
const _ALERT_SUCCESS = "SUCCESS";
const _ALERT_LOGOUT = "You have successfully logout";
const _ALERT_LOGOUT_ERROR = "An error has occurred. Please login again.";
const _ALERT_LOGOUT_TIMEOUT = "Your session is expired. Please login again.";
const _VALIDATION_ERROR = "VALIDATION ERROR";
const _VALIDATION_MSG = "Please make sure all fields are valid.";

function ShowLoader () {
    let overlay = jQuery('<div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.6); z-index: 10000;"><div style="text-align: center; width: 100%; position: absolute; top: 40%; margin-top: -50px;"> <div class="preloader-wrapper big active"> <div class="spinner-layer spinner-blue"> <div class="circle-clipper left"> <div class="circle"></div> </div><div class="gap-patch"> <div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div> </div> </div> <div class="spinner-layer spinner-red"> <div class="circle-clipper left"> <div class="circle"></div> </div><div class="gap-patch"> <div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div> </div> </div> <div class="spinner-layer spinner-yellow"> <div class="circle-clipper left"> <div class="circle"></div> </div><div class="gap-patch"> <div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div> </div> </div> <div class="spinner-layer spinner-green"> <div class="circle-clipper left"> <div class="circle"></div> </div><div class="gap-patch"> <div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div> </div> </div> </div> </div> </div>');
    overlay.appendTo(document.body);
}

function HideLoader () {
    $('#loading-overlay').remove();
}

function mzValidMail (mail) {
    return /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()\.,;\s@\"]+\.{0,1})+([^<>()\.,;:\s@\"]{2,}|[\d\.]+))$/.test(mail);
}

function mzValidDigit (digit) {
    return /^\d+$/.test(digit);
}

function mzValidNumeric (n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function mzValidate (name) {
    let obj = {};
    obj.form_id = name;
    obj.fields = [];
    const checkField = function (field_id, type, val) {
        const fieldVal = $('#'+field_id).val();
        if (type === 'notEmpty' && val === true && fieldVal === '')
            return false;
        if (type === 'maxLength' && fieldVal.length > val)
            return false;
        if (type === 'minLength' && fieldVal.length < val)
            return false;
        if (type === 'numeric' && val === true && !mzValidNumeric(fieldVal))
            return false;
        if (type === 'email' && val === true && !mzValidMail(fieldVal))
            return false;
        if (type === 'digit' && val === true && !mzValidDigit(fieldVal))
            return false;
        return true;
    };
    const validateFields = function (field_id, validator, name) {
        let msg = '';
        $('#'+field_id).removeClass('invalid');
        $('#'+field_id+'Err').html('');
        $.each(validator, function (n2, u2) {
            if (!checkField(field_id, n2, u2)) {
                if (n2 === 'notEmpty') {
                    msg += '<br>Please key in '+name;
                    return false;
                } else if (n2 === 'maxLength') {
                    msg += '<br>Maximum length is '+u2+' words';
                } else if (n2 === 'minLength') {
                    msg += '<br>Minimum length is '+u2+' words';
                } else if (n2 === 'numeric') {
                    msg += '<br>'+name+' must be valid number';
                } else if (n2 === 'email') {
                    msg += '<br>'+name+' must be valid email address';
                } else if (n2 === 'digit') {
                    msg += '<br>'+name+' must contains digit only';
                }
            }
        });
        if (msg !== '') {
            $('#'+field_id).addClass('invalid');
            $('#'+field_id+'Err').html(msg.substring(4));
            return false;
        }
        return true;
    };
    this.registerFields = function (data) {
        this.fields = data;
        $.each(this.fields, function (n, u) {
            $('#'+u.field_id).on('keyup', function () {
                validateFields(u.field_id, u.validator, u.name);
            });
        });
    };
    this.validateForm = function () {
        let result = true;
        $.each(this.fields, function (n, u) {
            if (!validateFields(u.field_id, u.validator, u.name))
                result = false;
        });
        return result;
    };
}

function mzAjaxRequest(url, type, data, async) {
    let returnVal = '';
    if (typeof url === 'undefined' || typeof type === 'undefined' || url === '' || type === '') {
        toastr['error'](_ALERT_ERROR_MSG_DEFAULT, _ALERT_ERROR);
        throw new Error('error');
    }
    if (type !== 'GET' && type !== 'POST' && type !== 'PUT' && type !== 'DELETE') {
        toastr['error'](_ALERT_ERROR_MSG_DEFAULT, _ALERT_ERROR);
        throw new Error('error');
    }
    data = typeof data === 'undefined' ? '' : JSON.stringify(data);
    async = typeof async === 'undefined' ? false : async;

    let header = {};
    if (sessionStorage.getItem('token') !== null) {
        header = { 'Authorization': 'Bearer ' + sessionStorage.getItem('token') };
    } 
    if (type === 'GET' && data !== '') {
        jQuery.extend(header, JSON.parse(data));
        data = '';
    }

    let errMsg = '';
    $.ajax({
        url: '../../api/' + url,
        type: type,
        contentType: 'application/json',
        headers: header,
        data : data,
        dataType : 'json',
        async : async,
        success: function(resp){
            if (resp.success) {
                returnVal = resp.result;
            } else {
                errMsg = 'error';
                toastr['error'](resp.errmsg !== '' ? resp.errmsg : _ALERT_ERROR_MSG_DEFAULT, _ALERT_ERROR);
            }
        },
        error: function() {
            errMsg = 'error';
            toastr['error'](_ALERT_ERROR_MSG_DEFAULT, _ALERT_ERROR);
        }
    });

    if (errMsg !== '')
        throw new Error(errMsg);
    return returnVal;
}