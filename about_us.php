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

        <div class="page-banner" style="background-image: url(images/bg/aboutbg.jpg); background-size: cover; background-position: center top;">
            <div class="page-banner-content">
                <div class="dtable hw100">
                    <div class="dtable-cell hw100">
                        <div class="container text-center">
                            <h1 class="page-head-title wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.05s">About Us</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-breadbar">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="page-sub-title">About</h3>
                        </div>
                        <div class="col-sm-6 bread-hold">
                            <ol class="breadcrumb">
                                <li><a href="<?php echo baseUrl(); ?>home">Home</a></li>
                                <li class="active">About</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sec-overlay"></div>
        </div>

        <div class="main-container page-container section-padd">
            <div class="container">
                <div class="sec-header">
                    <h2 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.15s">Something more to know about us</h2>
                    <p class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.28s">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    </p>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-3 col-xs-6 col-xxs-12">
                        <div class="col-about-in wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.05s">
                            <div class="about-thumb"><img class="img-responsive" src="images/about/1.jpg" alt="img"></div>
                            <div class="col-about-info">
                                <h3><a href="">ABOUT US</a> </h3>
                                <p style="text-align: justify;">
                                    Ticket Chai - which means “I want ticket” in Bengali. Ticketchai.com is a service of Ticket Chai Limited (TCL) is created based on a simple idea – to allow the consumer’s book their tickets at a click from the convenience of their homes or offices and make them free from the journey of traffic dense streets & long queues for booking tickets.
                                </p>
                                <p style="text-align: justify;">
                                    Ticketchai.com offers consumers easy access to all forms of ticketed entertainment, sports, movies, and transport with multiple payment options. Ticketchai.com is committed to enhancing the purchase experience while democratizing access for consumers.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-3 col-xs-6 col-xxs-12">
                        <div class="col-about-in wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.15s">
                            <div class="about-thumb"><img class="img-responsive" src="images/about/2.jpg" alt="img"></div>
                            <div class="col-about-info">
                                <h3><a href="">MISSION</a> </h3>
                                <p style="text-align: justify;">
                                    Remain reliable, efficient and at the forefront of technology in all kind of ticketing service to provide customer services of high quality, to approach actively to resolution of any customer needs, to face any challenges automated ticketing in Bangladesh and abroad, as well as to increase efficiency and effectiveness of all activities performed in favor of fulfillment of common goals set by our shareholders, management and employees.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-3 col-xs-6 col-xxs-12">
                        <div class="col-about-in wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.25s">
                            <div class="about-thumb"><img class="img-responsive" src="images/about/3.jpg" alt="img"></div>
                            <div class="col-about-info">
                                <h3><a href="">VISION</a> </h3>
                                <p style="text-align: justify;">
                                    We want to be a dynamic, modern and reputable ticketing service provider of any kind of event or transport with an increasing share in the tech industry, ensuring constant customer satisfaction and performance improvement with respect to our environment and safety while delivering our services. Our corporate vision is to become the best company providing ticketing services.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.container--> 

        </div>
        <!--/.main-container-->

        <div class="section-padd">
            <?php include basePath('partner.php'); ?>
            <?php include basePath('our_featured.php'); ?>
        </div>
        <?php //include basePath('testimonial.php'); ?>
        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>
    </body>
</html>