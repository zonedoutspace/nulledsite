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
    </div>

    <div class="main-container page-container section-padd">
        <div class="container">
            <div class="sec-header" style="max-width: 700px;">
                <h2>Ticketchai Sitemap</h2>
                <p>
                    Welcome to Ticketchai. Ticketchai enables people all over the world to plan, promote, and sell tickets to any event. And we make it easy for everyone to discover events, and to share the events they are attending with the people they know. The following pages contain our Terms of Service, which govern all use of our Services.
                </p>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="sidebar-list-holder">
                        <ul class="list-unstyled page-link-list">
                            <li><a href="<?php echo baseUrl(); ?>terms-of-service">Terms &amp; Condition</a> </li>
                            <li><a href="<?php echo baseUrl(); ?>privacy-policy">Privacy &amp; Policy</a> </li>
                            <li><a href="<?php echo baseUrl(); ?>how-to-buy">How to Buy</a> </li>
                            <li><a href="<?php echo baseUrl(); ?>customer-support">Customer Support</a> </li>
                            <li class="active"><a href="<?php echo baseUrl(); ?>sitemap">Sitemap</a> </li>

                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="pages-inner-content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="sitemap-title">Company Information</h4>
                                <ul class="list-unstyled sitemap-list">
                                    <li><a href="<?php echo baseUrl(); ?>home">Ticketchai Home</a> </li>
                                    <li><a href="<?php echo baseUrl(); ?>about-us">About</a> </li>
                                    <li><a href="<?php echo baseUrl(); ?>contact-us">Contact</a> </li>
                                </ul>
                                <h4 class="sitemap-title">Community</h4>
                                <ul class="list-unstyled sitemap-list">
                                    <li><a href="<?php echo baseUrl(); ?>terms-of-service">Terms &amp; Condition</a> </li>
                                    <li><a href="<?php echo baseUrl(); ?>how-to-buy">How to Buy</a> </li>
                                    <li><a href="<?php echo baseUrl(); ?>customer-support">Customer Support</a> </li>
                                    <!--<li><a href="<?php // echo baseUrl();  ?>our-sponsor">Our Sponsor </a> </li>-->
                                    <li><a href="<?php echo baseUrl(); ?>sitemap">Sitemap</a> </li>

                                </ul>
                            </div>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="sitemap-title">Choose Your Category</h4>
                                        <ul class="list-unstyled sitemap-list sitemap-list-inline">
                                            <li><a href="<?php echo baseUrl(); ?>home">Events</a> </li>
                                            <li><a href="javascript:void(0);">Movies</a> </li>
                                            <li><a href="javascript:void(0);">Plays</a> </li>
                                            <li><a href="javascript:void(0);">Sports</a> </li>
                                            <li><a href="javascript:void(0);">Offers</a> </li>

                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
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