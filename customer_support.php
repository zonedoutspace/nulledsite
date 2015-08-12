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

        <div class="main-container page-container section-padd">
            <div class="container">
                <div class="sec-header" style="max-width: 700px;">
                    <h2 class=" wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.05s"> Customer Support</h2>
                    <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s">  Tell us more about your shopping needs to be connected with a sales specialist in the Country </p>
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
                                <li class="active"><a href="<?php echo baseUrl(); ?>customer-support">Customer Support</a> </li>
                                <li><a href="<?php echo baseUrl(); ?>sitemap">Sitemap</a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="pages-inner-content">
                            <div class="support-row dtable">
                                <div class="support-cell dtable-cell">
                                    <div class="support-icon wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.02s"><i class="icon-chat"></i> </div>
                                </div>
                                <div class="support-cell dtable-cell">
                                    <div class="support-info">
                                        <h4 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.05s"> Chat</h4>
                                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> Chat with an Ticketchai Home & Home Office sales specialist before You buy, check your order status or get help with an order you already placed.</p>
                                    </div>
                                </div>
                                <div class="support-cell dtable-cell">
                                    <div class="support-btn"><a href="javascript:void(0)" class="btn btn-primary">Chat</a> </div>
                                </div>
                            </div>
                            <div class="support-row dtable">
                                <div class="support-cell dtable-cell">
                                    <div class="support-icon wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.02s"><i class="icon-mail"></i> </div>
                                </div>
                                <div class="support-cell dtable-cell">
                                    <div class="support-info">
                                        <h4 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.05s"> Email</h4>
                                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> Ticketchai Home & Home Office Email team provides sales and order assistance for customers purchasing from within the United States.</p>
                                    </div>
                                </div>
                                <div class="support-cell dtable-cell">
                                    <div class="support-btn"><a href="javascript:void(0)" class="btn btn-primary">Email</a> </div>
                                </div>
                            </div>
                            <div class="support-row dtable">
                                <div class="support-cell dtable-cell">
                                    <div class="support-icon wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.02s"><i class="icon-phone-1"></i> </div>
                                </div>
                                <div class="support-cell dtable-cell">
                                    <div class="support-info">
                                        <h4 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.05s"> Call +8801971842538 </h4>
                                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> To speak with a sales specialist. Available 6 days a week, from 9 am to 6 pm Bangladeshi time.</p>
                                    </div>
                                </div>
                                <div class="support-cell dtable-cell">
                                    <div class="support-btn"><a href="javascript:void(0)" class="btn btn-primary">Call</a> </div>
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