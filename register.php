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
        <div class="mx-3 mt-3 mb-3">
            <div class="flex-center flex-column">

                <section class="form-elegant">

                    <!--Form without header-->
                    <div class="card mt-5 mb-5">

                        <!--Header-->
                        <div class="header pt-3 blue-gradient-rgba">
                            <div class="row d-flex justify-content-center">
                                <h3 class="white-text mb-3 pt-2 font-weight-bold">Sign up</h3>
                            </div>
                        </div>

                        <!--Body-->
                        <div class="card-body mx-4">

                            <p class="animated fadeIn text-muted text-center">Inventory System - New user registration</p>

                            <!-- Form -->
                            <form id="formRegRegister" style="color: #757575;">

                                <div class="form-row">
                                    <div class="col">
                                        <!-- First name -->
                                        <div class="md-form">
                                            <i class="fa fa-user prefix grey-text"></i>
                                            <input type="text" id="txtRegFirstName" name="txtRegFirstName" class="form-control">
                                            <label for="txtRegFirstName">First Name</label>
                                            <p class="font-small text-danger pl-4_5" id="txtRegFirstNameErr"></p>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <!-- Last name -->
                                        <div class="md-form">
                                            <input type="email" id="txtRegLastName" name="txtRegLastName" class="form-control">
                                            <label for="txtRegLastName">Last Name</label>
                                            <p class="font-small text-danger" id="txtRegLastNameErr"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- E-mail -->
                                <div class="md-form mt-0">
                                    <i class="fa fa-envelope prefix grey-text"></i>
                                    <input type="email" id="txtRegEmail" name="txtRegEmail" class="form-control">
                                    <label for="txtRegEmail">E-mail</label>
                                    <p class="font-small text-danger pl-4_5" id="txtRegEmailErr"></p>
                                </div>

                                <!-- Phone number -->
                                <div class="md-form">
                                    <i class="fa fa-drivers-license-o prefix grey-text"></i>
                                    <input type="text" id="txtRegMyKad" name="txtRegMyKad" class="form-control">
                                    <label for="txtRegMyKad">MyKad Number</label>
                                    <p class="font-small text-danger pl-4_5" id="txtRegMyKadErr"></p>
                                </div>

                                <!-- Phone number -->
                                <div class="md-form">
                                    <i class="fa fa-phone prefix grey-text"></i>
                                    <input type="text" id="txtRegPhone" name="txtRegPhone" class="form-control">
                                    <label for="txtRegPhone">Phone Number</label>
                                    <p class="font-small text-danger pl-4_5" id="txtRegPhoneErr"></p>
                                </div>

                                <!-- Password -->
                                <div class="md-form">
                                    <i class="fa fa-lock prefix grey-text"></i>
                                    <input type="password" id="txtRegPassword" name="txtRegPassword" class="form-control" aria-describedby="txtRegPasswordHelpBlock">
                                    <label for="txtRegPassword">Password</label>
                                    <p class="font-small text-danger pl-4_5" id="txtRegPasswordErr"></p>
                                    <small id="txtRegPasswordHelpBlock" class="form-text text-muted mb-4">
                                        At least 6 characters
                                    </small>
                                </div>

                                <!-- Password -->
                                <div class="md-form">
                                    <i class="fa fa-lock prefix grey-text"></i>
                                    <input type="password" id="txtRegPasswordConfirm" name="txtRegPasswordConfirm" class="form-control">
                                    <label for="txtRegPasswordConfirm">Confirm Password</label>
                                    <p class="font-small text-danger pl-4_5" id="txtRegPasswordConfirmErr"></p>
                                </div>

                                <!-- Sign up button -->
                                <div class="text-center mb-3">
                                    <button type="button" id="btnRegSignUp" class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" disabled="">Sign up</button>
                                </div>

                                <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> More Information:</p>

                                <div class="row my-3 d-flex justify-content-center">
                                    <button type="button" class="btn btn-white btn-rounded mr-md-3 z-depth-1a" style="width:150px"><i
                                            class="fa fa-question blue-text"></i> FAQ</button>
                                    <button type="button" class="btn btn-white btn-rounded mr-md-3 z-depth-1a" style="width:150px"><i
                                            class="fa fa-file-pdf-o blue-text text-center"></i> Manuals</button> 
                                </div>

                                <!-- Terms of service -->
                                <p>By clicking
                                    <em>Sign up</em> you agree to our
                                    <a href="" target="_blank">terms of service</a> and
                                    <a href="" target="_blank">terms of service</a>.
                                </p>

                            </form>

                        </div>

                        <!--Footer-->
                        <div class="modal-footer mx-5 pt-3 mb-1">
                            <p class="font-small grey-text d-flex justify-content-end">
                                Back to <a href="login.php" class="blue-text ml-1">Sign in</a>
                            </p>
                        </div>

                    </div>
                    <!--/Form without header-->

                </section>

            </div>
        </div>
        <!-- /Start your project here-->

        <!-- SCRIPTS -->
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="js/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="js/mdb.min.js"></script>

        <script type="text/javascript">
            new WOW().init();

            document.write('<scr' + 'ipt src="js/common.js?' + new Date().valueOf() + '" type="text/javascript"></scr' + 'ipt>');

            document.addEventListener("DOMContentLoaded", function () {

                ShowLoader();
                setTimeout(function () {
                    HideLoader();
                }, 200);

                const vDataReg = [
                    {
                        field_id: "txtRegFirstName",
                        type: "text",
                        name: "First Name",
                        validator: {
                            notEmpty: true,
                            maxLength: 100
                        }
                    },
                    {
                        field_id: "txtRegLastName",
                        type: "text",
                        name: "Last Name",
                        validator: {
                            notEmpty: true,
                            maxLength: 100
                        }
                    },
                    {
                        field_id: "txtRegEmail",
                        type: "text",
                        name: "E-mail",
                        validator: {
                            notEmpty: true,
                            maxLength: 100,
                            email: true
                        }
                    },
                    {
                        field_id: "txtRegMyKad",
                        type: "text",
                        name: "MyKad Number",
                        validator: {
                            notEmpty: true,
                            minLength: 12,
                            maxLength: 12,
                            digit: true
                        }
                    },
                    {
                        field_id: "txtRegPhone",
                        type: "text",
                        name: "Phone Number",
                        validator: {
                            notEmpty: true,
                            maxLength: 15,
                            digit: true
                        }
                    },
                    {
                        field_id: "txtRegPassword",
                        type: "text",
                        name: "Password",
                        validator: {
                            notEmpty: true,
                            maxLength: 20,
                            minLength: 6
                        }
                    },
                    {
                        field_id: "txtRegPasswordConfirm",
                        type: "text",
                        name: "Confirm Password",
                        validator: {
                            notEmpty: true,
                            maxLength: 20,
                            minLength: 6,
                            similar: {
                                id: "txtRegPassword",
                                label: "Password"
                            }
                        }
                    }
                ];

                let formRegRegisterValidate = new mzValidate('formRegRegister');
                formRegRegisterValidate.registerFields(vDataReg);

                $('#formRegRegister').on('keyup', function () {
                    $('#btnRegSignUp').attr('disabled', !formRegRegisterValidate.validateForm());
                });

                $('#btnRegSignUp').on('click', function () {
                    ShowLoader();
                    setTimeout(function () {
                        try {
                            if (!formRegRegisterValidate.validateForm()) {
                                throw new Error(_ALERT_MSG_VALIDATION);
                            }   
                            let data = {
                                action: 'register',
                                userFirstName: $('#txtRegFirstName').val(),
                                userLastName: $('#txtRegLastName').val(),
                                userEmail: $('#txtRegEmail').val(),
                                userMykadNo: $('#txtRegMyKad').val(),
                                userProfileContactNo: $('#txtRegPhone').val(),
                                userPassword: $('#txtRegPasswordConfirm').val()
                            };
                            mzAjaxRequest('register.php', 'POST', data);
                            window.location.href = 'login.php?f=3';
                        } catch (e) {
                            toastr["error"](e.message, _ALERT_TITLE_ERROR_REGISTER);
                        }
                        HideLoader();
                    }, 300);
                });

            });

        </script>
    </body>

</html>
