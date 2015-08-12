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



        <div class="main-container page-container">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <h2>Sell Tickets</h2>
                            <h4 style="text-align: justify;">Selling via Ticketchai.com is simple, hassle free and helps get your event noticed by thousands of potential customers.</h4>
                        </div>
                        <br/>
                        <div>
                            <p style="text-align: justify;">Want to cut out administration, call handling and have more free time to concentrate on building the perfect event? Our professional team can give you expert advice and insights to help make your event a success.</p>
                            <p style="text-align: justify;">Ticketchai.com has an expansive reach in Bangladesh. Listing your event with us guarantees that you get directly in front of your target audience. We are the no.1 online ticketing site in Bangladesh.</p>
                            <p style="text-align: justify;">Extra marketing and exposure through Ticketchai.com can include banner placement, individual event newsletters, flyer artwork by our expert team of designers, and more. For further information on any of the above please <a href="<?php echo baseUrl(); ?>contact-us">contact our team.</a></p>
                        </div>
                        <div>
                            <h2>Target Audience</h2>
                            <p style="text-align: justify;">Ticketchai.com can attract the right audience for you. Our core target audience are 18 – 45 year old Bangladeshi people. However, we have attracted people from all backgrounds due to the range of events we have on the site, from Nightlife to Theatre, Sports and Dating/Social Events.</p>
                        </div>
                        <div>
                            <h2>Brand Association</h2>
                            <p style="text-align: justify;">Ticketchai.com has worked with the most reputable brands in Bangladesh; having powered Online Event feeds for bangladesh Football Federation, Bangladesh Shilpakala Academy. We pride ourselves on working with the most professional companies in the industry.</p>
                        </div>
                        <div>
                            <h2>Event Organisers</h2>
                            <p style="text-align: justify;">We are ticketing partners to a number of skilled event organisers and have gained exclusive and/or official e-ticket agent status for the following clients:</p>
                            <ul>
                                <li>Bangladesh Football Federation</li>
                                <li>Bangladesh Shilpokala Academy</li>
                                <li>Dhaka Theatre</li>
                                <li>International Theatre Institute</li>
                            </ul>

                            <p>All you need to do is <a href="<?php echo baseUrl(); ?>merchant-form">fill out our simple event form</a> and a member of our team will be in touch to get your event listed as soon as possible. We take all the hard work out of promoting your event and only charge a small commission.</p>
                            <p>What are you waiting for?!</p>
                            <p class="text-center"><a href="<?php echo baseUrl(); ?>merchant-form" class="btn btn-primary btn-sm">List Your Event</a></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>
    </body>
</html>