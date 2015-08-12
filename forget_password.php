<?php
include './config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include basePath('header_script.php'); ?>
    </head>
    <body class="home">
        <header>
            <div class="header-wrapper">
                <?php include basePath('menu_top.php'); ?>
                <?php include basePath('navigation.php'); ?>
            </div>
        </header>
        <div class="main-container">
            <div class="container">
                <div class="sign-box-holder">
                    <div class="sign-box">
                        <div class="sign-box-header">
                            <h1>Forget Password</h1>
                        </div>
                        <div class="sign-box-body">
                            
                                <div class="form-group-icon">
                                    <label><i class="fa fa-envelope-o"></i></label>
                                    <input type="email" id="user_email" name="user_email" class="form-control" placeholder="Enter email address">
                                </div>
                                <a href="javascript:void(0);" onclick="return false;" id="forgetPassSendRequest" class="btn btn-primary btn-lg btn-block btn-forgetpass">Send Request</a>
                            
                        </div>
                    </div>
                    <div class="sign-extrabar">
                        <h5>Don't have a Ticketchai account? </h5>
                        <h4><a href="#">Sign Up !</a></h4>
                    </div>
                </div>

            </div>
        </div>
        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>
    </body>
</html>