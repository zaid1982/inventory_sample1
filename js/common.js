const _ALERT_TITLE_VALIDATION_ERROR = "VALIDATION ERROR";
const _ALERT_MSG_VALIDATION = "Please make sure all fields are valid.";
const _ALERT_TITLE_ERROR = "ERROR";
const _ALERT_MSG_ERROR_DEFAULT = "Error on system. Please contact Administrator!";
const _ALERT_TITLE_SUCCESS = "SUCCESS";

const _ALERT_TITLE_SUCCESS_LOGOUT = "LOGOUT SUCCESS";
const _ALERT_MSG_SUCCESS_LOGOUT = "You have successfully logout";
const _ALERT_MSG_ERROR_LOGOUT = "An error has occurred. Please login again.";
const _ALERT_TITLE_ERROR_TIMEOUT = "TIMEOUT ERROR";
const _ALERT_MSG_ERROR_TIMEOUT = "Your session is expired. Please login again.";

const _ALERT_TITLE_ERROR_LOGIN = "LOGIN ERROR";
const _ALERT_TITLE_ERROR_REGISTER = "REGISTRATION ERROR";
const _ALERT_TITLE_SUCCESS_REGISTER = "REGISTRATION SUCCESS";
const _ALERT_MSG_SUCCESS_REGISTER = "You have successfully registered. Please activate via link sent to your email.";
const _ALERT_TITLE_ERROR_ACTIVATE = "ACTIVATION ERROR";
const _ALERT_TITLE_SUCCESS_ACTIVATE = "ACTIVATION SUCCESS";
const _ALERT_MSG_SUCCESS_ACTIVATE = "Your account has successfully activated. Please login with email as user ID and your registered password.";
const _ALERT_TITLE_ERROR_FORGOT_PASSWORD = "FORGOT PASSWORD ERROR";
const _ALERT_TITLE_SUCCESS_FORGOT_PASSWORD = "FORGOT PASSWORD SUCCESS";
const _ALERT_MSG_SUCCESS_FORGOT_PASSWORD = "Your password successfully reset. Please login with temporary password sent to your email.";

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
        switch (type) {
            case 'notEmpty':
                if (val === true && fieldVal === '')
                    return false;
                break;
            case 'maxLength':
                if (fieldVal.length > val)
                    return false;
                break;
            case 'minLength':
                if (fieldVal.length < val)
                    return false;
                break;
            case 'numeric':
                if (val === true && !mzValidNumeric(fieldVal))
                    return false;
                break;
            case 'email':
                if (val === true && !mzValidMail(fieldVal))
                    return false;
                break;
            case 'digit':
                if (val === true && !mzValidDigit(fieldVal))
                    return false;
                break;
            case 'similar':    
                if (val !== '' && fieldVal !== $('#'+val.id).val())
                    return false;
                break;
        }
        return true;
    };
    
    const validateFields = function (field_id, validator, name) {
        let msg = '';
        $('#'+field_id).removeClass('invalid');
        $('#'+field_id+'Err').html('');
        $.each(validator, function (n2, u2) {
            if (!checkField(field_id, n2, u2)) {
                switch (n2) {
                    case 'notEmpty':
                        msg += '<br>Please key in '+name;
                        return false;
                    case 'maxLength':
                        msg += '<br>Maximum length is '+u2+' words';
                        break;
                    case 'minLength':
                        msg += '<br>Minimum length is '+u2+' words';
                        break;
                    case 'numeric':
                        msg += '<br>'+name+' must be valid number';
                        break;
                    case 'email':
                        msg += '<br>'+name+' must be valid email address';
                        break;
                    case 'digit':
                        msg += '<br>'+name+' must contains digit only';
                        break;
                    case 'similar':    
                        msg += '<br>'+name+' must equals to '+u2.label;
                        break;                
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
    
    const validateFieldsNoError = function (field_id, validator) {
        let noError = true;
        $.each(validator, function (n2, u2) {
            if (!checkField(field_id, n2, u2)) {
                noError = false;
                return false;
            }
        });
        return noError;
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
            if (!validateFieldsNoError(u.field_id, u.validator))
                result = false;
        });
        return result;
    };
    
    this.clearValidation = function () {
        $.each(this.fields, function (n, u) {
            $('#'+u.field_id).removeClass('invalid');
            $('#'+u.field_id+'Err').html('');
        });
    };
}

function mzAjaxRequest(url, type, data, async) {
    let returnVal = '';
    if (typeof url === 'undefined' || typeof type === 'undefined' || url === '' || type === '') {
        throw new Error(_ALERT_MSG_ERROR_DEFAULT);
    }
    if (type !== 'GET' && type !== 'POST' && type !== 'PUT' && type !== 'DELETE') {
        throw new Error(_ALERT_MSG_ERROR_DEFAULT);
    }
    data = typeof data === 'undefined' ? '' : data; // JSON.stringify(data)
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
        url: '../api/' + url,
        type: type,
        //contentType: 'application/json',
        headers: header,
        data : data,
        dataType : 'json',
        async : async,
        success: function(resp){
            if (resp.success) {
                returnVal = resp.result;
            } else {
                errMsg = resp.errmsg !== '' ? resp.errmsg : _ALERT_MSG_ERROR_DEFAULT;
            }
        },
        error: function() {
            errMsg = _ALERT_MSG_ERROR_DEFAULT;
        }
    });

    if (errMsg !== '') {
        throw new Error(errMsg);
    }
    return returnVal;
}

function mzGetUrlVars() {
    let vars = {};
    const parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
        function(m,key,value) {
          vars[key] = value;
        }
    );
    return vars;
}

function mzSleep(delay) {
    let start = new Date().getTime();
    while (new Date().getTime() < start + delay);
}

function initiatePages() {

    $(".button-collapse").sideNav();

    let container = document.querySelector('.custom-scrollbar');
    Ps.initialize(container, {
        wheelSpeed: 2,
        wheelPropagation: true,
        minScrollbarLength: 20
    });
    
    const token = sessionStorage.getItem('token');
    let userInfo = sessionStorage.getItem('userInfo');
    if (token === null || userInfo === null) {
        sessionStorage.removeItem('token');
        sessionStorage.removeItem('userInfo');
        window.location.href = 'login.html?f=2';
    }
    userInfo = JSON.parse(userInfo);
        
    let menuHtml = '<li>\
      <a class="collapsible-header waves-effect arrow-r">\
        <i class="fa fa-tachometer"></i> Dashboards\
        <i class="fa fa-angle-down rotate-icon"></i>\
      </a>\
      <div class="collapsible-body">\
        <ul>\
          <li>\
            <a href="../dashboards/v-1.html" class="waves-effect">Version 1</a>\
          </li>\
          <li>\
            <a href="../dashboards/v-2.html" class="waves-effect">Version 2</a>\
          </li>\
          <li>\
            <a href="../dashboards/v-3.html" class="waves-effect">Version 3</a>\
          </li>\
          <li>\
            <a href="../dashboards/v-4.html" class="waves-effect">Version 4</a>\
          </li>\
          <li>\
            <a href="../dashboards/v-5.html" class="waves-effect">Version 5</a>\
          </li>\
          <li>\
            <a href="../dashboards/v-6.html" class="waves-effect">Version 6</a>\
          </li>\
        </ul>\
      </div>\
    </li>\
    <li>\
      <a class="collapsible-header waves-effect arrow-r">\
        <i class="fa fa-photo"></i> Pages\
        <i class="fa fa-angle-down rotate-icon"></i>\
      </a>\
      <div class="collapsible-body">\
        <ul>\
          <li>\
            <a href="../pages/login.html" class="waves-effect">Login</a>\
          </li>\
          <li>\
            <a href="../pages/register.html" class="waves-effect">Register</a>\
          </li>\
          <li>\
            <a href="../pages/pricing.html" class="waves-effect">Pricing</a>\
          </li>\
          <li>\
            <a href="../pages/about.html" class="waves-effect">About us</a>\
          </li>\
        </ul>\
      </div>\
    </li>';
    $('#ulNavLeft').append(menuHtml);

    $('.collapsible').collapsible();
}