<?php
include "config/config.php";
$announceTitle = '';
$announceShortDesc = '';
$announceLongDesc = '';
$announceDate = '';

$announceID = 0;
if (isset($_GET['id']) AND $_GET['id'] > 0) {
    $announceID = validateInput($_GET['id']);

    $sqlGetAnnounce = "SELECT * FROM announcements WHERE announcement_id=$announceID";
    $resultGetAnnounce = mysqli_query($con, $sqlGetAnnounce);
    if ($resultGetAnnounce) {
        $resultGetAnnounceObj = mysqli_fetch_object($resultGetAnnounce);
        if (isset($resultGetAnnounceObj->announcement_id)) {
            $announceTitle = $resultGetAnnounceObj->announcement_title;
            $announceShortDesc = $resultGetAnnounceObj->announcement_short_desc;
            $announceLongDesc = $resultGetAnnounceObj->announcement_long_desc;
            $announceDate = $resultGetAnnounceObj->announcement_created_on;
        }
    } else {
        if (DEBUG) {
            $err = "resultGetAnnounce error: " . mysqli_error($con);
        } else {
            $err = "resultGetAnnounce query failed.";
        }
    }
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

                <div class="content-box">
                    <div class="content-row">
                        <div class="content-row row">
                            <div class="col-md-9 col-sm-8 left-siderbar">
                                <div class="blog-item blog-item-single">

                                    <div class="blog-info">

                                        <h1 class="title"><a><?php echo $announceTitle; ?></a> </h1>
                                        <ul class="list-inline tag-list">
                                            <li><i class="icon-clock"></i> <a href=""><?php echo date("d M, Y", strtotime($announceDate)); ?></a> </li>
                                        </ul>
                                        <div class="b-desc">
                                            <p>
                                                <?php echo $announceLongDesc; ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            

                        </div>
                        <!--/.featured-row--> 
                    </div>
                    <!--/.content-row--> 

                </div>
                <!--/.content-box-->


            </div>
            <!--/.container--> 

        </div>
        <!--/.main-container--> 



        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>
    </body>
</html>