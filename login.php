<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Inventory System</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="css/mdb.min.css" rel="stylesheet">
        <!-- Your custom styles (optional) -->
        <link href="css/style.css" rel="stylesheet">

        <style>            
            .form-elegant .font-small {
                font-size: 0.8rem;
            }

            .form-elegant .z-depth-1a {
                -webkit-box-shadow: 0 2px 5px 0 rgba(55, 161, 255, 0.26), 0 4px 12px 0 rgba(121, 155, 254, 0.25);
                box-shadow: 0 2px 5px 0 rgba(55, 161, 255, 0.26), 0 4px 12px 0 rgba(121, 155, 254, 0.25);
            }

            .form-elegant .z-depth-1-half,
            .form-elegant .btn:hover {
                -webkit-box-shadow: 0 5px 11px 0 rgba(85, 182, 255, 0.28), 0 4px 15px 0 rgba(36, 133, 255, 0.15);
                box-shadow: 0 5px 11px 0 rgba(85, 182, 255, 0.28), 0 4px 15px 0 rgba(36, 133, 255, 0.15);
            }

        </style>

        <!-- JQuery -->
        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    </head>

    <body class="intro-2">

        <!-- Start your project here-->
        <div class="mx-3 mt-sm-3 mt-lg-5 mb-3">
            <div class="flex-center flex-column">

                <h1 class="animated fadeIn mb-4 text-center">Inventory System Management</h1>
                <h5 class="animated fadeIn mb-3 text-center d-none d-sm-block">Thank you for using our product. We're glad you're with us.</h5>                
                <p class="animated fadeIn text-muted text-center">Please proceed to sign in to manage inventory.</p>

                <section class="form-elegant">

                    <!--Form without header-->
                    <div class="card mx-3 mt-3 mb-3">

                        <div class="card-body mx-4">
                            <form id="formLgnLogin">
                                <!--Header-->
                                <div class="text-center">
                                    <h3 class="dark-grey-text mb-5"><strong>Sign in</strong></h3>
                                </div>
                                <!--Body-->
                                <div class="md-form">
                                    <input type="email" id="txtLgnUsername" name="txtLgnUsername" class="form-control" >
                                    <label for="txtLgnUsername" >User ID</label>
                                    <p class="font-small text-danger" id="txtLgnUsernameErr"></p>
                                </div>

                                <div class="md-form pb-3">
                                    <input type="password" id="txtLgnPassword" name="txtLgnPassword" class="form-control" >
                                    <label for="txtLgnPassword">Your password</label>
                                    <p class="font-small text-danger" id="txtLgnPasswordErr"></p>
                                    <p class="font-small blue-text d-flex justify-content-end">
                                        <a href="#" class="blue-text ml-1" data-toggle="modal" data-target="#modalForgotPassword">Forgot Password?</a>
                                    </p>
                                </div>

                                <div class="text-center mb-3">
                                    <button type="button" id="btnLgnSignIn" class="btn blue-gradient btn-block btn-rounded z-depth-1a" disabled="">Sign in</button>
                                </div>
                                <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> More Information:</p>

                                <div class="row my-3 d-flex justify-content-center">
                                    <button type="button" class="btn btn-white btn-rounded mr-md-3 z-depth-1a" style="width:150px"><i
                                            class="fa fa-question blue-text"></i> FAQ</button>
                                    <button type="button" class="btn btn-white btn-rounded mr-md-3 z-depth-1a" style="width:150px"><i
                                            class="fa fa-file-pdf-o blue-text text-center"></i> Manuals</button> 
                                </div>
                            </form>

                        </div>

                        <!--Footer-->
                        <div class="modal-footer mx-5 pt-3 mb-1">
                            <p class="font-small grey-text d-flex justify-content-end">
                                Not a member? <a href="register.php" class="blue-text ml-1">Sign Up</a>
                            </p>
                        </div>

                    </div>
                    <!--/Form without header-->

                </section>

            </div>
        </div>
        <!-- /Start your project here-->

        <!-- Modal: modalForgotPassword-->
        <div class="modal fade" id="modalForgotPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-notify modal-info" role="document">
                <!--Content-->
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header">
                        <p class="heading">Forgot Password
                        </p>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="white-text">&times;</span>
                        </button>
                    </div>

                    <!--Body-->
                    <div class="modal-body">
                        <form id="formMfpForgotPassword">                            
                            <div class="md-form">
                                <i class="fa fa-user prefix grey-text"></i>
                                <input type="text" id="txtMfpUserId" name="txtMfpUserId" class="form-control">
                                <label for="txtMfpUserId">Your User ID</label>
                                <p class="font-small text-danger pl-4_5" id="txtMfpUserIdErr"></p>
                            </div>
                        </form>
                    </div>

                    <!--Footer-->
                    <div class="modal-footer justify-content-end px-2 py-2">
                        <button id="btnMfpSend" class="btn btn-outline-cyan mt-1">Send <i class="fa fa-paper-plane-o ml-1"></i></button>
                    </div>

                </div>
                <!--/.Content-->
            </div>
        </div>
        <!-- Modal: modalForgotPassword-->

        <input type="hidden" id="logoutReason" value="">

        <!--  SCRIPTS  -->
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="js/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="js/mdb.js"></script>

        <script type="text/javascript">
            new WOW().init();

            document.write('<scr' + 'ipt src="js/common.js?' + new Date().valueOf() + '" type="text/javascript"></scr' + 'ipt>');

            document.addEventListener('DOMContentLoaded', function () {

                ShowLoader();
                setTimeout(function () {
                    HideLoader();
                }, 200);

                // display notification
                const getVar = mzGetUrlVars()['f'];
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
                const getVarKey = mzGetUrlVars()['key'];
                if (typeof getVarKey !== 'undefined') {
                    ShowLoader();
                    setTimeout(function () {
                        try {
                            const data = {
                                action: 'activate',
                                activationInput: getVarKey
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

                $('#formLgnLogin').on('keyup', function () {
                    $('#btnLgnSignIn').attr('disabled', !formLgnLoginValidate.validateForm());
                });

                $('#btnLgnSignIn').on('click', function () {
                    ShowLoader();
                    setTimeout(function () {
                        try {
                            if (!formLgnLoginValidate.validateForm()) {
                                throw new Error(_ALERT_MSG_VALIDATION);
                            }                            
                            const data = {
                                username: $('#txtLgnUsername').val(),
                                password: $('#txtLgnPassword').val()
                            };                                
                            const respLogin = mzAjaxRequest('login.php', 'POST', data);                                
                            sessionStorage.setItem('token', respLogin.token);       

                            let userInfo = {};
                            $.each(respLogin, function (n, u) {
                                if (n !== 'token') {
                                    userInfo[n] = u;
                                }
                            });
                            sessionStorage.setItem('userInfo', JSON.stringify(userInfo));                         
                            sessionStorage.setItem('navId', 1);                                
                            sessionStorage.setItem('navSecondId', 0);
                            window.location.href = 'home.php';
                        } catch (e) {
                            toastr['error'](e.message, _ALERT_TITLE_ERROR_LOGIN);
                        }
                        HideLoader();
                    }, 300);
                });

                const vDataMfp = [
                    {
                        field_id: 'txtMfpUserId',
                        type: 'text',
                        name: 'User ID',
                        validator: {
                            notEmpty: true,
                            maxLength: 100
                        }
                    }
                ];

                let formMfpValidate = new mzValidate('formMfpForgotPassword');
                formMfpValidate.registerFields(vDataMfp);

                $('#formMfpForgotPassword').on('keyup', function () {
                    $('#btnMfpSend').attr('disabled', !formMfpValidate.validateForm());
                });

                $('#modalForgotPassword').on('shown.bs.modal', function () {
                    formMfpValidate.clearValidation();
                    $('#btnMfpSend').attr('disabled', true);
                });

                $('#btnMfpSend').on('click', function () {
                    ShowLoader();
                    setTimeout(function () {
                        try {
                            if (!formMfpValidate.validateForm()) {
                                throw new Error(_ALERT_MSG_VALIDATION);
                            }
                            const data = {
                                action: 'forgot_password',
                                username: $('#txtMfpUserId').val()
                            };
                            mzAjaxRequest('register.php', 'POST', data);
                            $('#modalForgotPassword').modal('hide');
                            toastr['success'](_ALERT_MSG_SUCCESS_FORGOT_PASSWORD, _ALERT_TITLE_SUCCESS_FORGOT_PASSWORD);
                        } catch (e) {
                            toastr['error'](e.message, _ALERT_TITLE_ERROR_FORGOT_PASSWORD);
                        }
                        HideLoader();
                    }, 300);
                });

            });
        </script>
    </body>
</html>
