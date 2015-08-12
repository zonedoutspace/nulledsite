<!--Google Login API Javascript functions-->
<?php
$Type = '';
if (isset($_GET['type'])) {
    $Type = $_GET['type'];
}
?>
<script type="text/javascript">

    function logout()
    {
        gapi.auth.signOut();
        location.reload();
    }


    function googleLogin()
    {
        var myParams = {
            'clientid': '948155867752-6kae5etea0qjpcpo2lcftkorv1kccgn3.apps.googleusercontent.com',
            'cookiepolicy': 'single_host_origin',
            'callback': 'loginCallback',
            'approvalprompt': 'force',
            'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email'
        };
        gapi.auth.signIn(myParams);
    }


//loginCallback() function is to check whether user is successfully logged in or not and if do then get response
    function loginCallback(result)
    {
        if (result['status']['signed_in'])
        {
            var request = gapi.client.plus.people.get(
                    {
                        'userId': 'me'
                    });
            request.execute(function (resp)
            {
                var email = '';
                if (resp['emails'])
                {
                    for (i = 0; i < resp['emails'].length; i++)
                    {
                        if (resp['emails'][i]['type'] == 'account')
                        {
                            email = resp['emails'][i]['value'];
                        }
                    }
                }


                var data = new Object();
                data.user_first_name = resp['name']['givenName'];
                data.user_last_name = resp['name']['familyName'];
                data.user_email = email;
                data.user_social_id = resp['id'];
                data.user_gender = resp['gender'];
                data.user_social_type = 'google';
                data.user_verification = 'yes';

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
                        alert("not working whole process");
                    }

                });
            });

        }

    }
    function onLoadCallback()
    {
        gapi.client.setApiKey('AIzaSyD53EmYL-9Tj9d6_jgcOHNHxYSEKPpYUaU');
        gapi.client.load('plus', 'v1', function () {
        });
    }

</script>


<script type="text/javascript">
//      Asynchronous call to google client api

    (function () {
        var po = document.createElement('script');
        po.type = 'text/javascript';
        po.async = true;
        po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);
    })();
</script>
<!--//Google Login API Javascript functions-->