<?php
include "config/config.php";
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

        <div class="page-banner">
            <div class="page-banner-content">
                <div class="contact-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d912.9906364251227!2d90.40331199999999!3d23.748714999999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDQ0JzU1LjQiTiA5MMKwMjQnMTEuOSJF!5e0!3m2!1sbn!2sbd!4v1413197027945" width="800" height="600" frameborder="0" style="border:0"></iframe>
                </div>
            </div>
            <div class="page-breadbar">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="page-sub-title">Contact</h3>
                        </div>
                        <div class="col-sm-6 bread-hold">
                            <ol class="breadcrumb">
                                <li><a href="<?php echo baseUrl(); ?>home">Home</a></li>
                                <li class="active">Contact</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-container page-container section-padd">
            <div class="container">
                <div class="sec-header">
                    <h2>Please Get in Touch With Us</h2>
                    <p>
                        Please feel free to contact us any time we will get back to you asp.
                    </p>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="contact-info">
                            <h3 class="contact-sub-title">Contact Info</h3>
                            <p>You can contact or visit us in our office from Saturday to Thursday from 10:00 AM - 06:00 PM</p>
                            <ul class="list-unstyled contact-info-list">
                                <li><i class="fa fa-map-marker"></i>
                                    <span>Razzak Plaza (8th Floor),1 New Eskaton Road,
                                        Moghbazar Circle, Dhaka-1217 
                                    </span>
                                </li>
                                <li><i class="fa fa-phone"></i><span>+880-1971-842538</span><span>+880-447-8009569</span></li>
                                <li><i class="fa fa-envelope-o"></i><a href="mailto:support@ticketchai.com">support@ticketchai.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="contact-query">

                            <form class="form-horizontal" method="post">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <input type="text" id="CU_name"name="CU_name" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" id="CU_email" name="CU_email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12"><input id="CU_subject" name="CU_subject" type="text" class="form-control" placeholder="Subject"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12"><textarea rows="4" id="CU_message" name="CU_message" class="form-control" placeholder="Message"></textarea></div>
                                </div>
                                <div class=""><button type="button" return="false" id="btnSaveContactUs" name="btnSaveContactUs"  class="btn btn-primary">Submit</button> </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.container--> 

        </div>
        <!--/.main-container-->


        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>
    </body>
</html>