<?php
include './config/config.php';
$user_first_name = "";
$user_last_name = "";
$user_phone = "";
$user_street_address = "";
$user_zip = "";
$user_social_type = "";

if (!checkUserLogin()) {
    redirect('index.php');
} else {
    $userID = getSession('user_id');
    $user_email = getSession('user_email');

    $userInfoQuery = "SELECT * FROM users WHERE user_id = $userID AND user_email = '$user_email'";
    $userInfoResult = mysqli_query($con, $userInfoQuery);
    if ($userInfoResult) {
        $userInfo = mysqli_fetch_object($userInfoResult);
        $user_first_name = $userInfo->user_first_name;
        $user_last_name = $userInfo->user_last_name;
        $user_phone = $userInfo->user_phone;
        $user_street_address = $userInfo->user_street_address;
        $user_zip = $userInfo->user_zip;
        $user_social_type = $userInfo->user_social_type;
        $user_verification = $userInfo->user_verification;
    } else {
        if (DEBUG) {
            $err = "userInfoResult error: " . mysqli_error($con);
        } else {
            $err = "userInfoResult query failed";
        }
    }
}
//debug($userInfo);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include basePath('header_script.php'); ?>
    </head>
    <body>
        <div id="wrapper">
            <header>
                <div class="header-wrapper">
                    <?php include basePath('menu_top.php'); ?>
                    <?php include basePath('navigation.php'); ?>
                </div>
            </header>
            <!-- /.header -->
            <div class="main-container">
                <div class="container">
                    <ul class="nav nav-pills nav-justified  nav-tab-bar">
                        <li class="active"><a href="<?php echo baseUrl(); ?>account"><i class="fa fa-dashboard"></i> User Settings</a> </li>
                        <li><a href="<?php echo baseUrl(); ?>address"><i class="fa fa-map-marker"></i> Default Address</a> </li>
                        <li><a href="<?php echo baseUrl(); ?>mywishlist"><i class="fa fa-heart"></i> My Wishlist</a> </li>
                        <li><a href="<?php echo baseUrl(); ?>myorderlist"><i class="fa fa-heart"></i> Order History</a> </li>
                    </ul>


                    <div class="nav-tab-content">
                        <div class="welcome-msg">
                            <h3 class="page-sub-header2 clearfix no-padding">
                                Hello, <?php echo '<span id="first_name">' . $userInfo->user_first_name . '</span>'; ?>
                                &nbsp;&nbsp;
                                <?php if ($user_verification === "yes"): ?>
                                    <span>
                                        <img src="<?php echo baseUrl(); ?>images/Verified-Account-Logo.png" height="30px" alt="Verified Account" title="Verified Account" />
                                    </span>
                                <?php endif; ?>
                            </h3>
                            <span class="page-sub-header-sub small">You last logged in at: <?php echo $userInfo->user_last_login; ?></span> </div>
                        <div id="accordion" class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"> <a href="#collapseB1"  data-toggle="collapse"> My details </a> </h4>
                                </div>
                                <div class="panel-collapse collapse in" id="collapseB1">
                                    <div class="panel-body">
                                        <form class="form-horizontal" role="form" id="updatePersonalInfo">
                                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $userInfo->user_id; ?>">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">First Name <span style="color: red;">*</span></label>

                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="user_first_name" name="user_first_name" value="<?php echo $user_first_name; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-sm-3 control-label">Last name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="user_last_name" name="user_last_name" value="<?php echo $user_last_name; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-sm-3 control-label">Email</label>
                                                <div class="col-sm-9">
                                                    <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo $user_email; ?>" disabled="disabled">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9"> </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="submit" onclick="return false;" id="saveAccountSettings" class="btn btn-default btn-change-account">UPDATE</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Change Div Start -->
                            <?php if ($user_social_type === ''): ?>
                                <div class="panel panel-default" id="showPasswordDiv">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"> <a href="#collapseB2"  data-toggle="collapse">Password Settings </a> </h4>
                                    </div>
                                    <div class="panel-collapse collapse" id="collapseB2">
                                        <div class="panel-body">
                                            <form class="form-horizontal" role="form">
                                                <div class="form-group">
                                                    <label  class="col-sm-3 control-label">Old Password <span style="color: red;">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" id="user_old_password" name="user_old_password">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-sm-3 control-label">New Password <span style="color: red;">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" id="user_new_password" name="user_new_password">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-sm-3 control-label">Confirm Password <span style="color: red;">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" id="user_confirm_password" name="user_confirm_password">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-3 col-sm-9">
                                                        <button type="submit" onclick="return false;" id="passwordChange" class="btn btn-default btn-change-password">UPDATE</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                            <?php endif; ?>
                            <!-- Password Change Div End -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"> <a href="#collapseB3"  data-toggle="collapse"> Preferences </a> </h4>
                                </div>
                                <div class="panel-collapse collapse" id="collapseB3">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox">
                                                        I want to receive newsletter. 
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox">
                                                        I want to receive advice on buying and selling. 
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('footer_script.php'); ?>

        <!-- From Customer Dashboard -->
        <script src="<?php echo baseUrl('js/jquery.matchHeight-min.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/hideMaxListItem.js'); ?>"></script>

        <script src="<?php echo baseUrl('js/footable.js?v=2-0-1'); ?>" type="text/javascript"></script> 
        <script src="<?php echo baseUrl('js/footable.filter.js?v=2-0-1'); ?>" type="text/javascript"></script>
        <script type="text/javascript">
                    $(function () {
                        $('#addManageTable').footable().bind('footable_filtering', function (e) {
                            var selected = $('.filter-status').find(':selected').text();
                            if (selected && selected.length > 0) {
                                e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                                e.clear = !e.filter;
                            }
                        });

                        $('.clear-filter').click(function (e) {
                            e.preventDefault();
                            $('.filter-status').val('');
                            $('table.demo').trigger('footable_clear_filter');
                        });

                    });
        </script> 
        <script>
            function checkAll(bx) {
                var chkinput = document.getElementsByTagName('input');
                for (var i = 0; i < chkinput.length; i++) {
                    if (chkinput[i].type == 'checkbox') {
                        chkinput[i].checked = bx.checked;
                    }
                }
            }
        </script> 
        <script src="<?php echo baseUrl('js/plugins/jquery.fs.scroller/jquery.fs.scroller.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/plugins/jquery.fs.selecter/jquery.fs.selecter.js'); ?>"></script>
    </body>
</html>
