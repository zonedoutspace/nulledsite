//dropdown message/error/warning message function

function success(msg) {
    $.simplyToast('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;' + msg, 'success');
}

function error(msg) {
    $.simplyToast('<i class="fa fa-times-circle"></i>&nbsp;&nbsp;' + msg, 'danger');
}

function info(msg) {
    $.simplyToast('<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;' + msg, 'info');
}

// Enter Key Press Function Start Here

$(document).ready(function () {

    var highestBox = 0;
    $('.covered-event-div', this).each(function () {

        if ($(this).height() > highestBox)
            highestBox = $(this).height();
    });
    $('.covered-event-div', this).height(highestBox);
});

//taking heighest height and applying it to all div
//for featured event
//$(document).ready(function () {
//
//    var highestBox = 0;
//    $('.event-item', this).each(function () {
//
//        if ($(this).height() > highestBox)
//            highestBox = $(this).height();
//    });
//    $('.event-item', this).height(highestBox);
//});
////taking heighest height and applying it to all div



//taking heighest height and applying it to all div
//for featured event
$(document).ready(function () {

    var highestBox = 0;
    $('.similar-events-per-div', this).each(function () {

        if ($(this).height() > highestBox)
            highestBox = $(this).height();
    });
    $('.similar-events-per-div', this).height(highestBox);
});
////taking heighest height and applying it to all div



//custom function for javascript field validation
function validateInput(htmlID) {
    var fieldName = $("#" + htmlID).attr("data-field-name");
    var inputVal = $("#" + htmlID).val();
    if (inputVal == "" || inputVal == 0) {
        error('<strong>' + fieldName + ' is required.</strong>');
        $("#" + htmlID).addClass("inputError");
        return false;
    } else {
        $("#" + htmlID).removeClass("inputError");
        return true;
    }
}
//custom function for javascript field validation






$(function () {

// Login Modal Code
    $('#login_user_email').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#clickLogin").click();
        }
    });
    $('#login_user_password').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#clickLogin").click();
        }
    });
    // Login Modal Code

    // Sign up Modal Code
    $('#user_first_name').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#clickSignUp").click();
        }
    });
    $('#signup_user_email').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#clickSignUp").click();
        }
    });
    $('#signup_user_password').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#clickSignUp").click();
        }
    });
    $('#signup_user_confirm_password').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#clickSignUp").click();
        }
    });
    // Sign up Modal Code

    // Login Code in checkout page
    $('#signin_user_email').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#clickSignIn").click();
        }
    });
    $('#signin_user_password').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#clickSignIn").click();
        }
    });
    // Login Code in checkout page

    // Forget Password
    $('#user_email').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $("#forgetPassSendRequest").click();
        }
    });
});
// Enter Key Press Function End Here




// Email Address Validation Function Start
function validateEmail(email) {
    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    var valid = emailReg.test(email);
    if (!valid) {
        return false;
    } else {
        return true;
    }
}

// Email Address Validation Function End



//Getting values from URL
function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}
//Getting values from URL




$(document).ready(function () {
    $.extend(true, $.simplyToast.defaultOptions,
            {
                appendTo: "body",
                customClass: true,
                type: "info",
                offset:
                        {
                            from: "top",
                            amount: 20
                        },
                align: "right",
                minWidth: 250,
                maxWidth: 450,
                delay: 4000,
                allowDismiss: true,
                spacing: 10
            });
});
//dropdown message/error/warning message function


// Sava Registration Data Start

$(document).ready(function () {
    $("#clickSignUp").click(function () {
        var user_first_name = $("#user_first_name").val();
        var signup_user_email = $("#signup_user_email").val();
        var signup_user_password = $("#signup_user_password").val();
        var signup_user_confirm_password = $("#signup_user_confirm_password").val();


        var status = 0;

        if (user_first_name === "") {
            status++;
            error('<strong>Name required</strong>');
        }

        if (signup_user_email === "") {
            status++;
            error('<strong>Email address required</strong>');
        }

        if (signup_user_password === "") {
            status++;
            error('<strong>Password required</strong>');
        }

        if (signup_user_confirm_password === "") {
            status++;
            error('<strong>Confirm password required</strong>');
        }

        if (signup_user_password !== signup_user_confirm_password) {
            status++;
            error('<strong>Confirm password did not matched</strong>');
        }

        if (signup_user_email != "" && !validateEmail(signup_user_email)) {
            status++;
            error('<strong>Invalid email given</strong>');
        }

        if (status === 0) {

            $('.btn-signup').html('<i class="fa fa-spinner fa-spin"></i> Processing');
            $('.btn-signup').attr('disabled');

            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/saveRegistrationData.php",
                dataType: "json",
                data: {
                    user_first_name: user_first_name,
                    user_email: signup_user_email,
                    user_password: signup_user_password
                },
                success: function (response) {
                    var obj = response;
                    console.log(response.msg);
                    if (obj.output === "success") {
                        $('.btn-signup').html(' CREATE ACCOUNT ');
                        $('.btn-signup').removeAttr('disabled');

                        $('.btn-close').click();
                        $('#register_form').replaceWith('<a href="javascript:void();" onclick="javascript:userLogout();"><i class="fa fa-sign-out"></i>&nbsp;Logout</a>');
                        $('#login_form').replaceWith("<a href='" + baseUrl + "account'><i class='fa fa-user'></i>&nbsp;" + obj.user_first_name + '</a>');
                        success(obj.msg);
                        var pageName = document.location.pathname.match(/[^\/]+$/)[0];
                        if (typeof pageName != "undefined") {
                            if (pageName == "check") {
                                setTimeout(function () {
                                    document.location.href = (baseUrl + 'checkout-step-one');
                                }, 1500);
                            }
                        }
                    } else {
                        $('#clickSignIn').html(' CREATE ACCOUNT ');
                        $('#clickSignIn').removeAttr('disabled');
                        $('#clickSignIn').html(' CREATE ACCOUNT ');
                        error(obj.msg);
                    }
                }
            });
        }

    });
});
// Sava Registration Data End


// User Login Code Start

$(document).ready(function () {
    $("#clickLogin").click(function () {
        var login_user_email = $("#login_user_email").val();
        var login_user_password = $("#login_user_password").val();
        var status = 0;
        if (login_user_email === "") {
            status++;
            error('<strong>Enter email address</strong>');
        }

        if (login_user_password === "") {
            status++;
            error('<strong>Enter password</strong>');
        }

        if (login_user_email != "" && !validateEmail(login_user_email)) {
            status++;
            error('<strong>Invalid email given</strong>');
        }

        if (status == 0) {

            $('.btn-login').html('<i class="fa fa-spinner fa-spin"></i> Processing');
            $('.btn-login').attr('disabled');

            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/userLogin.php",
                dataType: "json",
                data: {
                    user_email: login_user_email,
                    user_password: login_user_password
                },
                success: function (response) {
                    var obj = response;
                    console.log(response.msg);
                    if (obj.output === "success") {
                        $('.btn-login').html(' Submit ');
                        $('.btn-login').removeAttr('disabled');
                        $('.btn-login').html('LOGIN');
                        $('.btn-close').click();
                        $('#register_form').replaceWith('<a href="javascript:void();" onclick="javascript:userLogout();"><i class="fa fa-sign-out"></i>&nbsp;Logout</a>');
                        $('#login_form').replaceWith("<a href='" + baseUrl + "account'><i class='fa fa-user'></i>&nbsp;" + obj.user_first_name + '</a>');
                        success(obj.msg);
                        var pageName = document.location.pathname.match(/[^\/]+$/)[0];
                        if (typeof pageName != "undefined") {
                            if (pageName == "check") {
                                setTimeout(function () {
                                    document.location.href = (baseUrl + 'checkout-step-one');
                                }, 1500);
                            }
                        }
                    } else {
                        $('#clickSignIn').html(' LOGIN ');
                        $('#clickSignIn').removeAttr('disabled');
                        $('#clickSignIn').html('LOGIN');
                        error(obj.msg);
                    }
                }
            });
        }
    });
});
// User Login Code End


// SignIn Code Start For User in signin-signup Page

$(document).ready(function () {
    $("#clickSignIn").click(function () {
        var signin_user_email = $("#signin_user_email").val();
        var signin_user_password = $("#signin_user_password").val();
        var status = 0;
        if (signin_user_email === "") {
            status++;
            error('<strong>Enter email address</strong>');
        }

        if (signin_user_password === "") {
            status++;
            error('<strong>Enter password</strong>');
        }

        if (signin_user_email != "" && !validateEmail(signin_user_email)) {
            status++;
            error('<strong>Invalid email given</strong>');
        }

        if (status === 0) {

            $('#clickSignIn').html('<i class="fa fa-spinner fa-spin"></i> Processing');
            $('#clickSignIn').attr('disabled');

            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/userSignin.php",
                dataType: "json",
                data: {
                    user_email: signin_user_email,
                    user_password: signin_user_password
                },
                success: function (response) {
                    var obj = response;
                    console.log(response.msg);
                    if (obj.output === "success") {
                        $('#clickSignIn').html(' Submit ');
                        $('#clickSignIn').removeAttr('disabled');

                        $('#register_form').replaceWith('<a href="javascript:void();" onclick="javascript:userLogout();"><i class="fa fa-sign-out"></i>&nbsp;Logout</a>');
                        $('#login_form').replaceWith("<a href='" + baseUrl + "account'><i class='fa fa-user'></i>&nbsp;" + obj.user_first_name + '</a>');
                        success(obj.msg);
                        var pageName = document.location.pathname.match(/[^\/]+$/)[0];
                        if (typeof pageName != "undefined") {
                            if (pageName == "check") {
                                setTimeout(function () {
                                    document.location.href = (baseUrl + 'checkout-step-one');
                                }, 1500);
                            }
                        }
                    } else {
                        $('#clickSignIn').html(' LOGIN ');
                        $('#clickSignIn').removeAttr('disabled');
                        $('#clickSignIn').html('LOGIN');
                        error(obj.msg);
                    }
                }
            });
        }
    });
});
// SignIn Code End For User in signin-signup Page


//User Logout Function Start

function userLogout() {
    jQuery.ajax({
        type: "POST",
        url: baseUrl + "ajax/ajaxLogout.php",
        dataType: "json",
        data: {
        },
        success: function (response) {
            var obj = response;
            success(obj.msg);
            setTimeout(function () {
                window.location.reload();
            }, 1500);
        }
    });
}
//User Logout Function End

// Save Account Settings Data Start


$(document).ready(function () {
    $("#saveAccountSettings").click(function () {
        var user_id = $("#user_id").val();
        var user_first_name = $("#user_first_name").val();
        var user_last_name = $("#user_last_name").val();
        var status = 0;
        if (user_first_name === "") {
            status++;
            error('<strong>First name is required</strong>');
        }


        if (status === 0) {

            $('.btn-change-account').html('<i class="fa fa-spinner fa-spin"></i> Processing');
            $('.btn-change-account').attr('disabled');

            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/saveAccountSettingsData.php",
                dataType: "json",
                data: {
                    user_id: user_id,
                    user_first_name: user_first_name,
                    user_last_name: user_last_name
                },
                success: function (response) {
                    $('.btn-change-account').html(' UPDATE ');
                    $('.btn-change-account').removeAttr('disabled');

                    var obj = response;
                    if (obj.output === "success") {
                        $('#first_name').text(user_first_name);
                        $('#user_last_name').text(user_last_name);
                        $('#login_form').replaceWith("<a href='" + baseUrl + "account'>" + user_first_name + '</a>');
                        success(obj.msg);
                    } else {
                        error(obj.msg);
                    }
                }
            });
        }
    });
});
// Save Account Settings Data End


//Add to Wishlist code start
function addToWishlist(id, type) {
    var itemID = id;
    var itemType = type;
    if (itemID > 0) {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxAddToWishlist.php",
            dataType: "json",
            data: {
                WL_product_id: itemID,
                WL_product_type: itemType
            },
            success: function (response) {
                var obj = response;
                if (obj.output == "login" && obj.msg == "login") {
                    $("#anchorSigninPopup").click();
                    error("You need to login first.");
                } else if (obj.output == "success") {
                    $("#wishlist_" + itemID).addClass("added");
                    success(obj.msg);
                } else if (obj.output == "info") {
                    info(obj.msg);
                } else {
                    error(obj.msg);
                }
            }
        });
    }
}
//Add to Wishlist code end




//Delete from Wishlist code start
function removeWishlist(id) {
    var WL_id = id;
    var status = confirm("This will permanently delete the product. Do you want to continue??");
    if (status == true) {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxDeleteWishlist.php",
            dataType: "json",
            data: {
                WL_id: WL_id
            },
            success: function (response) {
                var obj = response;
                if (obj.output == "success") {
                    $(".wishlist_row_" + WL_id).css('background-color', '#FFE0E0');
                    setTimeout(function () {
                        $(".wishlist_row_" + WL_id).fadeOut("slow");
                    }, 1500);
                    success(obj.msg);
                } else {
                    error(obj.msg);
                }
            }
        });
    }
}
//Delete from Wishlist code end




// User Address Delete Function Start

function deleteUserAddress(id) {
    var UA_id = id;
    // alert(UA_id);
    var c = confirm("Are you sure you want to delete this record?");
    if (c === true) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: baseUrl + "ajax/deleteUserAddress.php",
            data: {addressID: UA_id},
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    success(obj.msg);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else {
                    error(obj.msg);
                }
            }
        });
    }
}

// User Address Delete Function End

// function for delete item cart

function deleteItemCart(id1, id2) {
    var EITC_id = id1;
    var EITC_ETC_id = id2;
    var c = confirm("Are you sure you want to delete this item?");
    if (c === true) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: baseUrl + "ajax/deleteCartItem.php",
            data: {
                deleteItemID: EITC_id,
                deleteItemEventID: EITC_ETC_id
            },
            success: function (response) {
                var obj = response;
                var cartEmptyHtml = '<tr><td colspan="6" class="text-center"><h4>No item added to cart yet.</h4></td></tr>';
                if (obj.output === "success") {
                    success(obj.msg);
                    $("#cartItem_" + EITC_id).css('background-color', '#FFE0E0');
                    setTimeout(function () {
                        $("#cartItem_" + EITC_id).fadeOut("slow");
                    }, 1500);
                    $(".cart-Total").text(obj.totalPrice);
                    $(".sub-Total").text(obj.subTotal);
                    $(".total-Discount").text(obj.totalDiscount);
                    $(".chk-out-btn").attr("data-amount", obj.totalPrice);
                    if (obj.totalPrice == 0) {
                        $("#tblCartBody").html(cartEmptyHtml);
                    }
                } else {
                    error(obj.msg);
                }
            }
        });
    }
}


// function for delete item cart

// Forget Password Code Start

$(document).ready(function () {
    $("#forgetPassSendRequest").click(function () {
        var user_email = $("#user_email").val();
        var status = 0;
        if (user_email === "") {
            status++;
            error('<strong>Enter email address</strong>');
        }
        if (user_email != "" && !validateEmail(user_email)) {
            status++;
            error('<strong>Invalid email given</strong>');
        }
        if (status === 0) {
            $('.btn-forgetpass').html('<i class="fa fa-spinner fa-spin"></i> Processing');
            $('.btn-forgetpass').attr('disabled');

            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/forgetPassword.php",
                dataType: "json",
                data: {user_email: user_email},
                success: function (response) {
                    $('.btn-forgetpass').html(' Send Request ');
                    var obj = response;
                    if (obj.output === "success") {
                        success(obj.msg);
                    } else if (obj.output === "info") {
                        info(obj.msg);
                    } else {
                        error(obj.msg);
                    }
                }
            });
        }
    });
});
// Forget Password Code End


// Reset Password Code Start

$(document).ready(function () {
    $("#resetPassword").click(function () {
        var new_password = $("#new_password").val();
        var confirm_password = $("#confirm_password").val();
        var user_email = $("#user_email").val();
        var user_hash = $("#user_hash").val();
        var status = 0;
        if (new_password === "") {
            status++;
            error('<strong>Enter new password</strong>');
        }
        if (confirm_password === "") {
            status++;
            error('<strong>Enter confirm password</strong>');
        }
        if (new_password != confirm_password) {
            status++;
            error('<strong>Confirm password not matched</strong>');
        }
        if (status === 0) {

            $('.btn-reset').html('<i class="fa fa-spinner fa-spin"></i> Processing');
            $('.btn-reset').attr('disabled');
            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/resetPassword.php",
                dataType: "json",
                data: {
                    new_password: new_password,
                    user_email: user_email,
                    user_hash: user_hash
                },
                success: function (response) {
                    $('.btn-reset').html(' Change Password ');
                    var obj = response;
                    if (obj.output === "success") {
                        success(obj.msg);
                    } else {
                        error(obj.msg);
                    }
                }
            });
        }
    });
});
// Reset Password Code End










//ajax code for saving dynamic form data
function saveRegistration() {

    var checkStatus = 0;
    $.each($('input, select ,textarea', '#registration_form'), function (k) {
        var suffixForEmail = "email";
        var isRequired = $(this).attr('required');
        var inputValue = $(this).val();
        var inputName = $(this).attr('name');
        var inputID = $(this).attr('id');
        var inputFieldName = $(this).attr('data-name');
        if (typeof inputID != "undefined" && typeof inputName != "undefined") {
            if (inputID.indexOf(suffixForEmail) > -1 || inputName.indexOf(suffixForEmail) > -1) {
                if (!validateEmail(inputValue)) {
                    $(inputID).addClass("inputError");
                    error("<strong>Please put correct email format for " + inputFieldName + " field.</strong>");
                    checkStatus++;
                }
            }
        }

        if (typeof isRequired != 'undefined') {
            if (isRequired == "required") {
                if (inputValue == "") {
                    $(this).addClass("inputError");
                    error("<strong>" + inputFieldName + " field is required.</strong>");
                    checkStatus++;
                } else {
                    $(this).removeClass("inputError");
                }
            }
        }
    });
    //function after succesful file upload (when server response)
    if (checkStatus == 0) {

        var options = {
            url: baseUrl + 'ajax/ajaxSubmitEventRegistration.php',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    success(obj.msg);
                    $("#Registration-Form").fadeOut();
                    $("#All-Ticket-Types").fadeIn("slow");
                } else {
                    error(obj.msg);
                }
            }
        };
        $('#registration_form').ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation 
        return false;
    }
}
//ajax code for saving dynamic form data



// User Password Change Code Start
$(document).ready(function () {
    $("#passwordChange").click(function () {
        var user_old_password = $("#user_old_password").val();
        var user_new_password = $("#user_new_password").val();
        var user_confirm_password = $("#user_confirm_password").val();
        var status = 0;
        if (user_old_password === "") {
            status++;
            error('<strong>Enter old password</strong>');
        }
        if (user_new_password === "") {
            status++;
            error('<strong>Enter new password</strong>');
        }
        if (user_confirm_password === "") {
            status++;
            error('<strong>Confirm your password</strong>');
        }
        if (user_new_password != user_confirm_password) {
            status++;
            error('<strong>Confirm password not matched</strong>');
        }
        if (status === 0) {

            $('.btn-change-password').html('<i class="fa fa-spinner fa-spin"></i> Processing');
            $('.btn-change-password').attr('disabled');

            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/changePassword.php",
                dataType: "json",
                data: {
                    user_old_password: user_old_password,
                    user_new_password: user_new_password
                },
                success: function (response) {
                    $('.btn-change-password').html(' UPDATE ');
                    $('.btn-change-password').removeAttr('disabled');

                    var obj = response;
                    if (obj.output === "success") {
                        success(obj.msg);
                    } else {
                        error(obj.msg);
                    }
                }
            });
        }
    });
});
// User Password Change Code End






// User Address Add Code Start

$(document).ready(function () {
    $("#addNewUserAddress").click(function () {
//alert("Oh Yeah");
//        var UA_first_name = $("#UA_first_name").val();
//        var UA_last_name = $("#UA_last_name").val();
        var UA_phone = $("#UA_phone").val();
        var UA_address = $("#UA_address").val();
        var UA_zip = $("#UA_zip").val();
        var UA_city_id = $("#UA_city_id").val();
        var UA_country_id = $("#UA_country_id").val();
        var UA_user_id = $("#UA_user_id").val();
        var flag = 0;
        if (UA_phone === "") {
            error('<strong>Phone number required</strong>');
            $("#UA_phone").addClass("inputError");
            flag++;
        } else {
            $("#UA_phone").removeClass("inputError");
        }

        if (UA_address === "") {
            error('<strong>Address required</strong>');
            $("#UA_address").addClass("inputError");
            flag++;
        } else {
            $("#UA_address").removeClass("inputError");
        }

        if (UA_zip === "") {
            error('<strong>Zip/Postal code required</strong>');
            $("#UA_zip").addClass("inputError");
            flag++;
        } else {
            $("#UA_zip").removeClass("inputError");
        }

        if (UA_city_id === '0') {
            error('<strong>City name required</strong>');
            $("#UA_city_id").addClass("inputError");
            flag++;
        } else {
            $("#UA_city_id").removeClass("inputError");
        }

        if (UA_country_id === '0') {
            error('<strong>Country name required</strong>');
            $("#UA_country_id").addClass("inputError");
            flag++;
        } else {
            $("#UA_country_id").removeClass("inputError");
        }

        if (flag == 0) {
            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/addNewUserAddress.php",
                dataType: "json",
                data: {
                    UA_user_id: UA_user_id,
                    UA_phone: UA_phone,
                    UA_address: UA_address,
                    UA_zip: UA_zip,
                    UA_city_id: UA_city_id,
                    UA_country_id: UA_country_id
                },
                success: function (response) {
                    var obj = response;
                    if (obj.output === "success") {
                        success(obj.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    } else {
                        error(obj.msg);
                    }
                }
            });
        }

    });
});
// User Address Add Code End





// User Edit Address Code Start
$(document).ready(function () {
    $("#editUserAddress").click(function () {
        var UA_phone = $("#UA_phone").val();
        var UA_address = $("#UA_address").val();
        var UA_zip = $("#UA_zip").val();
        var UA_city_id = $("#UA_city_id").val();
        var UA_country_id = $("#UA_country_id").val();
        var UA_user_id = $("#UA_user_id").val();
        var UA_id = $("#UA_id").val();
        var flag = 0;
        if (UA_phone === "") {
            error('<strong>Phone number required</strong>');
            $("#UA_phone").addClass("inputError");
            flag++;
        } else {
            $("#UA_phone").removeClass("inputError");
        }

        if (UA_address === "") {
            error('<strong>Address required</strong>');
            $("#UA_address").addClass("inputError");
            flag++;
        } else {
            $("#UA_address").removeClass("inputError");
        }

        if (UA_zip === "") {
            error('<strong>Zip/Postal code required</strong>');
            $("#UA_zip").addClass("inputError");
            flag++;
        } else {
            $("#UA_zip").removeClass("inputError");
        }

        if (UA_city_id === '0') {
            error('<strong>City name required</strong>');
            $("#UA_city_id").addClass("inputError");
            flag++;
        } else {
            $("#UA_city_id").removeClass("inputError");
        }

        if (UA_country_id === '0') {
            error('<strong>Country name required</strong>');
            $("#UA_country_id").addClass("inputError");
            flag++;
        } else {
            $("#UA_country_id").removeClass("inputError");
        }

        if (flag == 0) {
            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/updateUserAddress.php",
                dataType: "json",
                data: {
                    UA_id: UA_id,
                    UA_user_id: UA_user_id,
                    UA_phone: UA_phone,
                    UA_address: UA_address,
                    UA_zip: UA_zip,
                    UA_city_id: UA_city_id,
                    UA_country_id: UA_country_id
                },
                success: function (response) {
                    var obj = response;
                    if (obj.output === "success") {
                        success(obj.msg);
                        setTimeout(function () {
                            document.location.href = (baseUrl + 'address');
                            //window.location.reload();
                        }, 1500);
                    } else {
                        error(obj.msg);
                    }
                }
            });
        }
    });
});
// User Edit Address Code End







//ADD TO CART SCRIPT
function addToCart(type, eventID, venueID, itemID) {

    var quantity = 0;
    if (type == "ticket") {
        quantity = $("#ticketQuantity_" + itemID + " option:selected").val();
    } else if (type == "include") {
        quantity = $("#includeQuantity_" + itemID + " option:selected").val();
    }

    $.ajax({
        type: "POST",
        url: baseUrl + "ajax/ajaxAddToCart.php",
        dataType: "json",
        data: {
            itemID: itemID,
            type: type,
            quantity: quantity,
            eventID: eventID,
            venueID: venueID
        },
        success: function (response) {
            var obj = response;
            // dropDownBox(obj.output, obj.msg);
            if (obj.output == "success") {
                var cartAmount = obj.totalCartAmount;
                var eventTmpCart = obj.arrTmpCartEvent;
                var itemTmpCart = obj.arrTmpCartItem;
                var objTicket = obj.arrSelectedTicket;
                var objInclude = obj.arrSelectedInclude;
                var objSeat = obj.arrSelectedSeat;
                generateMiniCart(eventTmpCart, itemTmpCart, objTicket, objInclude, objSeat);
                $("#cartAmount").text(obj.totalCartAmount);
                $('#notify').modal('show');
                $('#notify').removeClass('hide');
                success(obj.msg);
            } else {
                error(obj.msg);
            }

        }
    });
}
//ADD TO CART SCRIPT



//Generate Mini Cart Script

function generateMiniCart(eventTmpCartObj, itemTmpCartObj, ticketObj, includeObj, seatObj) {
    var cartHtml = '';
    var countEvent = eventTmpCartObj.length;
    var countItem = itemTmpCartObj.length;
    var sutTotal = 0;
    if (countEvent > 0) {
        $.each(eventTmpCartObj, function (key, Event) {
            cartHtml += '<li id="cartItemID_<?php echo $Event->ETC_id; ?>">';
            cartHtml += '<div class="basket-item">';
            cartHtml += '<div class="row">';
            cartHtml += '<div class="col-xs-12 col-sm-2 col-md-4">';
            cartHtml += '<div class="thumb"> ';
            if (Event.event_web_logo != '') {
                cartHtml += '<img src="' + baseUrl + 'upload/event_web_logo/' + Event.event_web_logo + '" alt="' + Event.event_title + '">';
            }
            cartHtml += '</div>';
            cartHtml += '</div>';
            cartHtml += '<div class="col-xs-12 col-sm-10 col-md-8">';
            cartHtml += '<div class="title"><a href="movie-details.html">' + Event.event_title + '</a></div>';
            if (itemTmpCartObj[Event.ETC_id].length > 0) {
                cartHtml += '<div class="col-xs-12">';
                cartHtml += '<table>';
                cartHtml += '<thead>';
                cartHtml += '<tr>';
                cartHtml += '<td style="width: 60%">Title</td>';
                cartHtml += '<td style="width: 20%">Qnt.</td>';
                cartHtml += '<td style="width: 20%">Price</td>';
                cartHtml += '</tr>';
                cartHtml += '</thead>';
                cartHtml += '<tbody>';
                $.each(itemTmpCartObj[Event.ETC_id], function (key, itemTmpCart) {
                    cartHtml += '<tr>';
                    if (itemTmpCart.EITC_item_type == 'ticket') {
                        cartHtml += '<td>' + ticketObj[itemTmpCart.EITC_item_id].TT_type_title + '</td>';
                    } else if (itemTmpCart.EITC_item_type == 'include') {
                        cartHtml += '<td>' + includeObj[itemTmpCart.EITC_item_id].EI_name + '</td>';
                    } else if (itemTmpCart.EITC_item_type == 'seat') {
                        cartHtml += '<td>' + seatObj[itemTmpCart.EITC_item_id].SPC_title + '</td>';
                    }
                    cartHtml += '<td>' + itemTmpCart.EITC_quantity + '</td>';
                    cartHtml += '<td>' + itemTmpCart.EITC_total_price + '</td>';
                    cartHtml += '</tr>';
                    sutTotal += parseInt(itemTmpCart.EITC_total_price);
                });
                cartHtml += '</tbody>';
                cartHtml += '</table>';
                cartHtml += '</div>';
            } else {
                cartHtml += '<div class="col-xs-12">';
                cartHtml += '<div class="price">No item added for this event.</div>';
                cartHtml += '</div>';
            }
            cartHtml += '</div>';
            cartHtml += '</div>';
            cartHtml += '<a href="#" class="close-btn"><i class="icon-cancel-circled"></i></a> </div>';
            cartHtml += '</li>';
        });
    } else {
        cartHtml += '<h4 style="margin: 15px 0px;" class="text-center">Cart is now empty.</h4>';
    }

    cartHtml += '<li class="checkout">';
    cartHtml += '<div class="merged-buttons"> <a class="btn btn-danger " href="' + baseUrl + 'cart">show cart</a> <a href="javascript:void(0);" onclick="javascript:goCheckout();" class="btn btn-info chk-out-btn" data-amount="' + sutTotal + '">checkout</a> </div>';
    cartHtml += '</li>';
    $('#wholeCart').html(cartHtml);
}
//Generate Mini Cart Script




//checking submitted address id in checkout step one
function verifyAddressID() {

    var flag = 0;
    var shippingCityID = $("#UA_shipping_city_id").val();
    var shippingCountryID = $("#UA_shipping_city_id").val();
    var billingCityID = $("#UA_shipping_city_id").val();
    var billingCountryID = $("#UA_shipping_city_id").val();
    if (validateInput("UA_shipping_title") == false) {
        flag++;
    }
    if (validateInput("UA_shipping_phone") == false) {
        flag++;
    }
    if (validateInput("UA_shipping_address") == false) {
        flag++;
    }
    if (validateInput("UA_shipping_city_id") == false || shippingCityID == 0) {
        flag++;
    }
    if (validateInput("UA_shipping_country_id") == false || shippingCountryID == 0) {
        flag++;
    }
    if (validateInput("UA_shipping_zip") == false) {
        flag++;
    }
    if (validateInput("UA_billing_title") == false) {
        flag++;
    }
    if (validateInput("UA_billing_phone") == false) {
        flag++;
    }
    if (validateInput("UA_billing_address") == false) {
        flag++;
    }
    if (validateInput("UA_billing_city_id") == false || billingCityID == 0) {
        flag++;
    }
    if (validateInput("UA_billing_country_id") == false || billingCountryID == 0) {
        flag++;
    }
    if (validateInput("UA_billing_zip") == false) {
        flag++;
    }

    if (flag == 0) {

        $('.btn-continue').html('<i class="fa fa-spinner fa-spin"></i> Processing');
        $('.btn-continue').attr('disabled');
        $("#addressForm").submit();
    }
}
//checking submitted address id in checkout step one



// Get Delivery City Cost
function getDeliveryCost(cityID) {
    var checkID = cityID;
    //alert(checkID);
    $.ajax({
        type: "POST",
        url: baseUrl + "ajax/getDeliveryCost.php",
        dataType: "json",
        data: {
            checkID: checkID
        },
        success: function (response) {
            var obj = response;
//            console.log(response.msg);
            if (obj.output === "success") {
                success(obj.msg);
                if (obj.delCost > 0) {
                    $("#deliveryCost").text(obj.delCost);
                } else {
                    $("#deliveryCost").text("FREE!!");
                }
                $("#showDelivery").show();
                $(".sub-Total").text(obj.subTotal);
            } else {
                error(obj.msg);
            }
        }
    });
}
// Get Delivery City Cost


//checking submitted payment method id in checkout step two
function verifyPayment() {
    var paymentMethod = $('input[name=payRadio]:checked', '#confirmOrder').val();
    var checkStat = 0;
    if (typeof paymentMethod == "undefined") {
        error("Please select a Payment Method.");
        checkStat++;
    }

    if (checkStat === 0) {

        $('.btn-confirm').html('<i class="fa fa-spinner fa-spin"></i> Processing');
        $('.btn-confirm').attr('disabled');

        $("form#confirmOrder").submit();
    }
}
//checking submitted payment method id in checkout step two





//function used for increasing product quantity in cart
function qntyIncrease(itemID) {
    var newQuantity = 0;
    var quantity = $("#txtItemQuantity_" + itemID).val();
    var limit = $("#txtItemQuantity_" + itemID).attr("data-limit");
    if (quantity < 1) {
        newQuantity = 1;
    } else {
        if (quantity < limit) {
            newQuantity = parseInt(quantity) + 1;
        } else if (quantity == limit) {
            info("<strong>Per user limit reached.</strong>");
            newQuantity = quantity;
        }
    }

    if (newQuantity > 0) {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxChngCartQnty.php",
            dataType: "json",
            data: {
                itemTmpCartID: itemID,
                qntyTmpCart: newQuantity
            },
            success: function (response) {
                var obj = response;
                console.log(response.msg);
                if (obj.output === "success") {
                    if (newQuantity != quantity) {
                        success(obj.msg);
                        $(".itemTotalPrice_" + itemID).text(obj.itemTotalPrice);
                        $(".cart-Total").text(obj.totalPrice);
                        $(".sub-Total").text(obj.subTotal);
                        $(".total-Discount").text(obj.totalDiscount);
                    }
                    $("#txtItemQuantity_" + itemID).val(newQuantity);
                } else {
                    error(obj.msg);
                }
            }
        });
    }
}
//function used for increasing product quantity in cart




//function used for decreasing product quantity in cart
function qntyDecrease(itemID) {
    var newQuantity = 0;
    var quantity = $("#txtItemQuantity_" + itemID).val();
    var limit = $("#txtItemQuantity_" + itemID).attr("data-limit");
    if (quantity <= 1) {
        newQuantity = 1;
    } else {
        if (quantity <= limit) {
            newQuantity = parseInt(quantity) - 1;
        }
    }

    if (newQuantity > 0) {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxChngCartQnty.php",
            dataType: "json",
            data: {
                itemTmpCartID: itemID,
                qntyTmpCart: newQuantity
            },
            success: function (response) {
                var obj = response;
                console.log(response.msg);
                if (obj.output === "success") {
                    if (newQuantity != quantity) {
                        success(obj.msg);
                        $(".itemTotalPrice_" + itemID).text(obj.itemTotalPrice);
                        $(".cart-Total").text(obj.totalPrice);
                        $(".sub-Total").text(obj.subTotal);
                        $(".total-Discount").text(obj.totalDiscount);
                    }
                    $("#txtItemQuantity_" + itemID).val(newQuantity);
                } else {
                    error(obj.msg);
                }
            }
        });
    }
}
//function used for decreasing product quantity in cart




//function used for checking if cart is empty or not before proceed to checkout
function goCheckout() {
    var subTotal = $(".chk-out-btn").attr("data-amount");
    subTotal = parseInt(subTotal);
    if (subTotal > 0) {
        success("<strong>Redirecting to checkout process .....</strong>");
        setTimeout(function () {
            document.location.href = (baseUrl + 'checkout-step-one');
            //window.location.reload();
        }, 1500);
    } else {
        info("<strong>Your cart is empty. Please add an item before checkout</strong>");
    }
}
//function used for checking if cart is empty or not before proceed to checkout






//function used for decreasing product quantity in cart
$(document).ready(function () {
    $('input.ship').on('ifChecked', function (event) {
        var addressID = $(this).val();
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxSetDefaultAddress.php",
            dataType: "json",
            data: {
                addressID: addressID,
                type: "shipping"
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    success(obj.msg);
                } else {
                    error(obj.msg);
                }
            }
        });
    });
});
//function used for decreasing product quantity in cart




//function used for decreasing product quantity in cart
$(document).ready(function () {
    $('input.bill').on('ifChecked', function (event) {
        var addressID = $(this).val();
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxSetDefaultAddress.php",
            dataType: "json",
            data: {
                addressID: addressID,
                type: "billing"
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    success(obj.msg);
                } else {
                    error(obj.msg);
                }
            }
        });
    });
});
//function used for decreasing product quantity in cart





//function used for making billing address as shipping address
$(document).ready(function () {
    var Html;
    var htmlString;
    var ShippingCity = $("#UA_shipping_city_id").val();
    $('input#makeBillingDefault').on('ifChecked', function () {

        $('#UA_billing_city_id').on('change', function () {
            $('option:selected', this).attr('selected', true).siblings().removeAttr('selected');
        });
        $('#UA_billing_country_id').on('change', function () {
            $('option:selected', this).attr('selected', true).siblings().removeAttr('selected');
        });
        Html = $('#shippingHtml').clone();
        htmlString = Html.html();
        var ShippingTitle = $("#UA_shipping_title").val();
        var ShippingPhone = $("#UA_shipping_phone").val();
        var ShippingAddress = $("#UA_shipping_address").val();
        var ShippingCountry = $("#UA_shipping_country_id").val();
        var ShippingZip = $("#UA_shipping_zip").val();
        var BillingTitle = $("#UA_billing_title").val();
        var BillingPhone = $("#UA_billing_phone").val();
        var BillingAddress = $("#UA_billing_address").val();
        var BillingCity = $("#UA_billing_city_id option:selected").val();
        var BillingCountry = $("#UA_billing_country_id option:selected").val();
        var BillingZip = $("#UA_billing_zip").val();
        if (BillingTitle == "" || BillingPhone == "" || BillingAddress == "" || BillingCity == 0 || BillingCountry == 0 || BillingZip == "") {
            error("Please provide complete Billing Address first.");
            setTimeout(function () {
                $('input#makeBillingDefault').iCheck('uncheck');
            }, 100);
        } else {
            var status = confirm("Do you want to make your Billing Address your Shipping Address for this order??");
            if (status == true) {
                $("#UA_shipping_title").val(BillingTitle);
                $("#UA_shipping_phone").val(BillingPhone);
                $("#UA_shipping_address").val(BillingAddress);
                $("#UA_shipping_city_id").val(BillingCity);
                $("#UA_shipping_country_id").val(BillingCountry);
                $("#UA_shipping_zip").val(BillingZip);
                getDeliveryCost(BillingCity);
                success("Your Billing Address is now your Shipping Address.")
            } else {
                $('input#makeBillingDefault').iCheck('uncheck');
            }
        }


    });
    $('input#makeBillingDefault').on('ifUnchecked', function () {
        $("#shippingHtml").html(htmlString);
        getDeliveryCost(ShippingCity);
    });
});
//function used for making billing address as shipping address


// Download Ticket

function downloadTicket(id) {
    var orderID = id;
    //alert(orderID);
    if (orderID > 0) {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxDownloadTicket.php",
            dataType: "json",
            data: {
                orderID: orderID
            }
        });
    }

}



// Coupon code start

function applyCoupon(totalCost) {
    var totalCost = totalCost;
    var coupon_code = $("#coupon_code").val();
    if (coupon_code === "") {
        error("<strong>Coupon code required</strong>");
    } else {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxCouponCode.php",
            dataType: "json",
            data: {
                totalCost: totalCost,
                coupon_code: coupon_code
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    success(obj.msg);
                    $("#paymethodShowCoupon").show();
                    $("#paymethodAmountCoupon").text(obj.amount.toFixed(2));
                    var newSubtotal = totalCost - obj.amount;
                    $("#paymentSubtotal").text(newSubtotal.toFixed(2));
                } else {
                    error(obj.msg);
                }
            }
        });
    }
}


/*
 * function for generating seat plan map
 */
function getPlaceMap(eventId, venueId) {

    var htmlPlan = "";
    $("#loaderScreen").show();
    $("#mainScreen").hide();
    $('#evntModal').modal('show');
    if (eventId > 0 && venueId > 0) {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxGetPlaceMapImg.php",
            dataType: "json",
            data: {
                eventId: eventId,
                venueId: venueId
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    $("#modalPlanTitle").html(obj.strPlaceTitle);
                    htmlPlan += '<img style="margin:0 auto;"  height="'+ obj.height +'" width="'+ obj.width +'"  src="' + baseUrl + 'upload/place_layout/' + obj.imgPlace + '" alt="' + obj.strPlaceTitle + '" usemap="#planMap"/>';

                    //generating area map for image
                    htmlPlan += '<map name="planMap">';
                    $.each(obj.objCoordinates, function (key, mapCoordinate) {
                        htmlPlan += '<area shape="' + mapCoordinate.SPC_shape_type + '" coords="' + mapCoordinate.SPC_coordinates + '" alt="' + mapCoordinate.SPC_title + '" onclick="javascript:getSeatPlan(' + mapCoordinate.SPC_id + ',' + mapCoordinate.SPC_SP_id + ',' + eventId + ',' + venueId + ');" title="' + mapCoordinate.SPC_title + '" style="color:red;" >';
                    });
                    htmlPlan += '</map>';

                    $("#modalPlanContent").html(htmlPlan);

                    //modifying html and positions
                    $("#loaderScreen").hide('slow');
                    $("#mainScreen").show('slow');
                } else {
                    error(obj.msg);
                }
            }
        });
    }
}


function getSeatPlan(coordinateId, placeId, eventId, venueId) {
    if (coordinateId > 0 && placeId > 0) {
        $("#loaderScreen").show('slow');
        $("#mainScreen").hide('slow');

        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/ajaxGetSeatMap.php",
            dataType: "json",
            data: {
                coordinateId: coordinateId,
                placeId: placeId,
                eventId: eventId,
                venueId: venueId
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    var count = 1;
                    var SeatCount = 1;
                    var column = obj.mapColumn;
                    var row = obj.mapRow;
                    var objDefaultSeatPlan = obj.arrDefaultSeatMap;
                    var objEventSeatPlan = obj.arrSelectedSeatMap;
                    var objSelectedSeat = obj.arrSelectedSeat;
                    var userLimit = obj.templateUserLimit;
                    var seatTicktPrice = obj.ticketPrice;
                    var selectedSeatQnty = objSelectedSeat.length;
                    var seatHtml = '';
                    var userLimitHtml = '';
                    var seatSelectedId = $("#seatSelected").val();
                    var showSelect = '';
                    
                    //getting ticket unit price and multiplying them with quantity to get total price
                    var totalSeatTicktPrice = seatTicktPrice * selectedSeatQnty;
                    $("#txtTotalPrice").text(totalSeatTicktPrice.toFixed(2));
                    
                    //generating selectbox for user limit
                    if (userLimit > 0) {
                        userLimitHtml += '<select class = "form-control" id = "selectQnty" style = "display: inline-block; width: auto;">';
                        for (var i = 1; i <= userLimit; i++) {
                            if (i == objSelectedSeat.length) {
                                showSelect = 'selected="selected"';
                            } else {
                                showSelect = '';
                            }
                            userLimitHtml += '<option ' + showSelect + '>' + i + '</option>';
                        }
                        userLimitHtml += '</select>';
                    }
                    $("#showUserLimit").html(userLimitHtml);
                    
                    //generating whole seat map
                    if (column > 0 && row > 0) {
                        for (var a = 0; a < row; a++) {
                            seatHtml += '<div class="seat-row row-a">';
                            seatHtml += '<ul>';
                            for (var b = 0; b < column; b++) {
                                if (objDefaultSeatPlan.hasOwnProperty(count)) {
                                    if (objEventSeatPlan.hasOwnProperty(count)) {
//                                        if (seatSelectedId != "") {
//                                            var editSeatSelectedId = seatSelectedId.replace(/^,|,$/g, '');
//                                            var objSeatSelectedId = editSeatSelectedId.split(',');
//                                        } else {
//                                            var objSeatSelectedId = '';
//                                        }
                                        var customSeatId = coordinateId + '-' + SeatCount;
                                        if (objSelectedSeat.length > 0) {
                                            if (objSelectedSeat.indexOf(customSeatId) >= 0) {
                                                seatHtml += '<li><a id="seatId_' + coordinateId + '_' + SeatCount + '" data-toggle="tooltip" data-placement="top" title="Price TK.' + obj.ticketPrice + '"  class="seatTooltip selected"><span>' + SeatCount + '</span></a> </li>';
                                                SeatCount++;
                                            } else {
                                                seatHtml += '<li><a id="seatId_' + coordinateId + '_' + SeatCount + '" onclick="javascript:selectSeat(' + coordinateId + ',' + SeatCount + ',' + placeId + ',' + eventId + ',' + venueId + ',' + obj.ticketPrice + ')" data-toggle="tooltip" data-placement="top" title="Price TK.' + obj.ticketPrice + '"  class="seatTooltip available"><span>' + SeatCount + '</span></a> </li>';
                                                SeatCount++;
                                            }
                                        } else {
                                            seatHtml += '<li><a id="seatId_' + coordinateId + '_' + SeatCount + '" onclick="javascript:selectSeat(' + coordinateId + ',' + SeatCount + ',' + placeId + ',' + eventId + ',' + venueId + ',' + obj.ticketPrice + ')" data-toggle="tooltip" data-placement="top" title="Price TK.' + obj.ticketPrice + '"  class="seatTooltip available"><span>' + SeatCount + '</span></a> </li>';
                                            SeatCount++;
                                        }
                                    } else {
                                        seatHtml += '<li><a data-toggle="tooltip" data-placement="top" class="seatTooltip bookedSeat"><span>' + SeatCount + '</span></a> </li>';
                                        SeatCount++;
                                    }
                                } else {
                                    seatHtml += '<li></li>';
                                }
                                count++;
                            }
                            seatHtml += '</ul>';
                            seatHtml += '</div>';
                        }
                    } else {
                        seatHtml += '<h5 class="text-center">No seat plan found for this section.</h5>';
                    }
                    $("#loaderScreen").hide('slow');
                    $("#showSeat").show('slow');
                    $("#divShowSeat").html(seatHtml);

                } else {
                    error(obj.msg);
                }
            }
        });
    }
}


function seatPlanGoBack() {
    $("#showSeat").hide('slow');
    $("#mainScreen").show('slow');
}



function selectSeat(coordinateId, seatId, placeId, eventId, venueId, ticktPrice) {
    var seatSelectedId = $("#seatSelected").val();
    var maxSeatSelect = $("#selectQnty").val();
    var currentTicktPrice = $("#txtTotalPrice").text();

    var countSelectedSeat = $(".seat-wrapper").find(".selected").length;
    if (countSelectedSeat < maxSeatSelect) {
        $("#seatId_" + coordinateId + "_" + seatId).addClass("selected");
        if (seatSelectedId == "") {
            $.ajax({
                type: "POST",
                url: baseUrl + "ajax/ajaxAddToCartSeat.php",
                dataType: "json",
                data: {
                    coordinateId: coordinateId,
                    seatId: seatId,
                    placeId: placeId,
                    eventId: eventId,
                    venueId: venueId
                },
                success: function (response) {
                    var obj = response;
                    // dropDownBox(obj.output, obj.msg);
                    if (obj.output == "success") {
                        var cartAmount = obj.totalCartAmount;
                        var eventTmpCart = obj.arrTmpCartEvent;
                        var itemTmpCart = obj.arrTmpCartItem;
                        var objTicket = obj.arrSelectedTicket;
                        var objInclude = obj.arrSelectedInclude;
                        var objSeat = obj.arrSelectedSeat;
                        generateMiniCart(eventTmpCart, itemTmpCart, objTicket, objInclude, objSeat);
                        $("#cartAmount").text(obj.totalCartAmount);
                        var newtotalPrice = parseFloat(currentTicktPrice) + parseFloat(ticktPrice);
                        $("#txtTotalPrice").text(newtotalPrice.toFixed(2));
                        success(obj.msg);
                    } else {
                        error(obj.msg);
                    }

                }
            });
            seatSelectedId = coordinateId + "-" + seatId + ",";
            $("#seatSelected").val(seatSelectedId);
        } else {

            var editSeatSelectedId = seatSelectedId.replace(/^,|,$/g, '');
            var objSeatSelectedId = editSeatSelectedId.split(',');
            var ifExist = 0;
            for (var i = 0; i < objSeatSelectedId.length; i++) {
                if (objSeatSelectedId[i] == coordinateId + "-" + seatId) {
                    ifExist++;
                }
            }
            if (ifExist == 0) {
                seatSelectedId += coordinateId + "-" + seatId + ",";
                $.ajax({
                    type: "POST",
                    url: baseUrl + "ajax/ajaxAddToCartSeat.php",
                    dataType: "json",
                    data: {
                        coordinateId: coordinateId,
                        seatId: seatId,
                        placeId: placeId,
                        eventId: eventId,
                        venueId: venueId
                    },
                    success: function (response) {
                        var obj = response;
                        // dropDownBox(obj.output, obj.msg);
                        if (obj.output == "success") {
                            var cartAmount = obj.totalCartAmount;
                            var eventTmpCart = obj.arrTmpCartEvent;
                            var itemTmpCart = obj.arrTmpCartItem;
                            var objTicket = obj.arrSelectedTicket;
                            var objInclude = obj.arrSelectedInclude;
                            var objSeat = obj.arrSelectedSeat;
                            generateMiniCart(eventTmpCart, itemTmpCart, objTicket, objInclude, objSeat);
                            $("#cartAmount").text(obj.totalCartAmount);
                            var newtotalPrice = parseFloat(currentTicktPrice) + parseFloat(ticktPrice);
                            $("#txtTotalPrice").text(newtotalPrice.toFixed(2));
                            success(obj.msg);
                        } else {
                            error(obj.msg);
                        }

                    }
                });
            }
            $("#seatSelected").val(seatSelectedId);
        }
    } else {
        error("You already choose " + maxSeatSelect + " seats. Change quantity to select more.");
    }
}