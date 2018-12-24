document.addEventListener("DOMContentLoaded", function() {

    ShowLoader();
    setTimeout(function () {
        HideLoader();
    }, 200);

    const vDataReg = [
        {
            field_id : "txtRegFirstName",
            type : "text",
            name : "First Name",
            validator : {
                notEmpty : true,
                maxLength : 100
            }
        },
        {
            field_id : "txtRegLastName",
            type : "text",
            name : "Last Name",
            validator : {
                notEmpty : true,
                maxLength : 100
            }
        },
        {
            field_id : "txtRegEmail",
            type : "text",
            name : "E-mail",
            validator : {
                notEmpty : true,
                maxLength : 100,
                email : true
            }
        },
        {
            field_id : "txtRegMyKad",
            type : "text",
            name : "MyKad Number",
            validator : {
                notEmpty : true,
                minLength : 12,
                maxLength : 12,
                digit : true
            }
        },
        {
            field_id : "txtRegPhone",
            type : "text",
            name : "Phone Number",
            validator : {
                notEmpty : true,
                maxLength : 15,
                digit : true
            }
        },
        {
            field_id : "txtRegPassword",
            type : "text",
            name : "Password",
            validator : {
                notEmpty : true,
                maxLength : 20,
                minLength : 6
            }
        },
        {
            field_id : "txtRegPasswordConfirm",
            type : "text",
            name : "Confirm Password",
            validator : {
                notEmpty : true,
                maxLength : 20,
                minLength : 6,
                similar : {
                    id: "txtRegPassword",
                    label: "Password"
                }
            }
        }
    ];
    
    let formRegRegisterValidate = new mzValidate('formRegRegister');
    formRegRegisterValidate.registerFields(vDataReg);
    
    $('#formRegRegister').on('keyup', function() {
        $('#btnRegSignUp').attr('disabled', !formRegRegisterValidate.validateForm());
    });
    
    $('#btnRegSignUp').on('click', function () {
        ShowLoader();
        setTimeout(function (){
            try {
                if (formRegRegisterValidate.validateForm()) {
                    let data = {};
                    //const respRegister = mzAjaxRequest("", "POST", data);
                    window.location.href = 'login.html?f=3';
                } else {
                    toastr["error"](_VALIDATION_MSG, _VALIDATION_ERROR)
                }
            } catch (e) {
                if (e.message !== "error")   toastr["error"](e.message, _ALERT_ERROR);
            }
            HideLoader();
        }, 300);
    });

});