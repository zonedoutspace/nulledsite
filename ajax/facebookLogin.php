<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function () {
        var appId = '';
        if(baseUrl == "http://ticketchai.com/" || baseUrl == "http://www.ticketchai.com/"){
            appId = '419921891492167';
        } else {
            appId = '841801075860278';
        }
        FB.init({
            appId: appId,
            oauth: true,
            status: true, // check login status
            cookie: true, // enable cookies to allow the server to access the session
            xfbml: true // parse XFBML
        });

    };

    function facebookLogin() {
        FB.login(function (response) {

            if (response.authResponse) {

                FB.api('/me', function (response) {

                    var data = new Object();
                    data.user_first_name = response.first_name;
                    data.user_last_name = response.last_name;
                    data.user_email = response.email;
                    data.user_social_id = response.id;
                    data.user_gender = response.gender;
                    data.user_social_type = 'facebook';
                    data.user_verification = 'yes';
                    console.log(data);
                    var url = baseUrl + 'ajax/saveFacebookGoogleData.php';
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: data,
                        dataType: "json",
                        success: function (response) {
                            var obj = response;

                            if (obj.output === "success") {
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
                                error(obj.msg);
                            }

                        },
                        error: function (output) {
                            alert("Process Working Stopped");
                        }

                    });

                });
                access_token = response.authResponse.accessToken; //get access token
                user_id = response.authResponse.userID; //get FB UID

            } else {
                //user hit cancel button
                console.log('User cancelled login or did not fully authorize.');

            }
        }, {
            scope: 'email'
        });
    }
    (function () {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
    }());
</script>