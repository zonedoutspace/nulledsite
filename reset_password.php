<?php
include './config/config.php';
$user_email = "";
$user_hash = "";
$return_array = array();
if (isset($_GET['m'])) {
    $user_email_sent = validateInput($_GET['m']);
    $user_email = base64_decode($user_email_sent);
}

if (isset($_GET['h'])) {
    $user_hash_sent = validateInput($_GET['h']);
    $user_hash = base64_decode($user_hash_sent);
}

if ($user_email != "" AND $user_hash != "") {
    $checkReturnUser = "SELECT * FROM users WHERE user_email = '$user_email' AND user_hash = '$user_hash'";
    $resultReturnUser = mysqli_query($con, $checkReturnUser);
    if ($resultReturnUser) {
        $countReturnUser = mysqli_num_rows($resultReturnUser);
    } else {
        $link = "index.php";
        redirect($link);
    }
} else {
    $link = "index.php";
    redirect($link);
}
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
                            <h1>Reset Password</h1>
                        </div>
                        <div class="sign-box-body">
                            <form class="sign-form">
                                <input type="hidden" id="user_email" name="user_email" value="<?php echo $user_email; ?>" />
                                <input type="hidden" id="user_hash" name="user_hash" value="<?php echo $user_hash; ?>" />
                                <div class="form-group-icon">
                                    <label><i class="fa fa-lock"></i></label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New password">
                                </div>
                                <div class="form-group-icon">
                                    <label><i class="fa fa-lock"></i></label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm password">
                                </div>
                                <a href="javascript:void(0);" id="resetPassword" class="btn btn-primary btn-lg btn-block btn-reset">Change Password</a>
                            </form>
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