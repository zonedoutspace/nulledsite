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
                    <h2 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.05s"> How to Buy</h2>
                    <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> 
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
                                <li class="active"><a href="<?php echo baseUrl(); ?>how-to-buy">How to Buy</a> </li>
                                <li><a href="<?php echo baseUrl(); ?>customer-support">Customer Support</a> </li>
                                <li><a href="<?php echo baseUrl(); ?>sitemap">Sitemap</a> </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="pages-inner-content">
                            <div class="buy-row dtable wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> 
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-sell-icon"><img src="images/how-to-buy/1.png" alt="Add to cart your item"> </div>
                                </div>
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-cell-info">
                                        <h4>Add to cart your item</h4>
                                        <p>
                                            First choose your item and add to cart.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="buy-row dtable wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> 
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-sell-icon"><img src="images/how-to-buy/2.png" alt="Register and process to cart"> </div>
                                </div>
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-cell-info">
                                        <h4>Register and process to cart</h4>
                                        <p>
                                            After adding item in your cart if you are not signed in the system then sign in or sign up and proceed to checkout.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="buy-row dtable  wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> 
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-sell-icon"><img src="images/how-to-buy/3.png" alt="Checkout"> </div>
                                </div>
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-cell-info">
                                        <h4>Fill up Billing & delivery address</h4>
                                        <p>
                                            Provided your billing & delivery address.
                                        </p>
                                    </div>
                                </div>
                            </div>
                                <div class="buy-row dtable  wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> 
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-sell-icon"><img src="images/how-to-buy/4.png" alt="Checkout"> </div>
                                </div>
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-cell-info">
                                        <h4>Select payment method</h4>
                                        <p>
                                            Select payment method and "Proceed to Payment" & "Checkout".
                                        </p>
                                    </div>
                                </div>
                            </div>
                                <div class="buy-row dtable  wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s"> 
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-sell-icon"><img src="images/how-to-buy/5.png" alt="Checkout"> </div>
                                </div>
                                <div class="buy-sell dtable-cell">
                                    <div class="buy-cell-info">
                                        <h4>Place order/confirmation</h4>
                                        <p>
                                            Finally click on "Place Order" or Confirmation payment.
                                        </p>
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