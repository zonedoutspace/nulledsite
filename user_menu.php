
<?php
include './config/config.php';
$user_first_name = "";
$user_last_name = "";
$user_phone = "";
$user_street_address = "";
$user_zip = "";
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
                    <div class="row">
                        <div class="col-sm-3 page-sidebar">
                            <aside>
                                <div class="inner-box">
                                    <div class="user-panel-sidebar">
                                        <div class="collapse-box">
                                            <h5 class="collapse-title no-border"> My Account <a href="#MyClassified" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a></h5>
                                            <div class="panel-collapse collapse in" id="MyClassified">
                                                <ul class="acc-list">
                                                    <li><a class="active" href="<?php echo baseUrl('dashboard.php'); ?>"><i class="icon-home"></i> Personal Information </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- /.collapse-box  -->
                                        <div class="collapse-box">
                                            <h5 class="collapse-title"> My Events <a href="#MyAds" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a></h5>
                                            <div class="panel-collapse collapse in" id="MyAds">
                                                <ul class="acc-list">
                                                    <li><a href="account-myevents.html"><i class="icon-docs"></i> My Events <span class="badge">42</span> </a></li>
                                                    <li><a href="account-favourite-events.html"><i class="icon-heart"></i> Favourite Events <span class="badge">42</span> </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- /.collapse-box  -->
                                        <div class="collapse-box">
                                            <h5 class="collapse-title"> Terminate Account <a href="#TerminateAccount" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a></h5>
                                            <div class="panel-collapse collapse in" id="TerminateAccount">
                                                <ul class="acc-list">
                                                    <li><a href="account-close.html"><i class="icon-cancel-circled "></i> Close account </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- /.collapse-box  --> 
                                    </div>
                                </div>
                                <!-- /.inner-box  --> 
                            </aside>
                        </div>
                        <!--/.page-sidebar-->
                        <div class="col-sm-9 page-content">
                            <div class="inner-box">
                                <div class="welcome-msg">
                                    <h3 class="page-sub-header2 clearfix no-padding">Hello, <?php echo '<span id="first_name">' . $userInfo->user_first_name.'</span>';?></h3>
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
                                                        <label  class="col-sm-3 control-label">First Name</label>
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
                                                        <label for="Phone" class="col-sm-3 control-label">Phone</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="user_phone" name="user_phone" value="<?php echo $user_phone; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label  class="col-sm-3 control-label">Street Address</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="user_street_address" name="user_street_address" value="<?php echo $user_street_address; ?>">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label  class="col-sm-3 control-label">Postcode</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="user_zip" name="user_zip" value="<?php echo $user_zip; ?>">
                                                        </div>
                                                    </div>

                                                    <!--<div class="form-group hide">  remove it if dont need this part 
                                                        <label  class="col-sm-3 control-label">Facebook account map</label>
                                                        <div class="col-sm-9">
                                                            <div class="form-control"> <a class="link" href="fb.com">Jhone.doe</a> <a class=""> <i class="fa fa-minus-circle"></i></a> </div>
                                                        </div>
                                                    </div>-->
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-3 col-sm-9"> </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-3 col-sm-9">
                                                            <button type="submit" onclick="return false;" id="saveAccountSettings" class="btn btn-default">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"> <a href="#collapseB2"  data-toggle="collapse">Password Settings </a> </h4>
                                        </div>
                                        <div class="panel-collapse collapse" id="collapseB2">
                                            <div class="panel-body">
                                                <form class="form-horizontal" role="form">
                                                    <div class="form-group">
                                                        <label  class="col-sm-3 control-label">Old Password</label>
                                                        <div class="col-sm-9">
                                                            <input type="password" class="form-control"  placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label  class="col-sm-3 control-label">New Password</label>
                                                        <div class="col-sm-9">
                                                            <input type="password" class="form-control"  placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label  class="col-sm-3 control-label">Confirm Password</label>
                                                        <div class="col-sm-9">
                                                            <input type="password" class="form-control"  placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-3 col-sm-9">
                                                            <button type="submit" class="btn btn-default">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
            </div>
            <?php include basePath('social_link.php'); ?>
            <?php include basePath('footer.php'); ?>
            <?php include basePath('footer_script.php'); ?>
    </body>
</html>
