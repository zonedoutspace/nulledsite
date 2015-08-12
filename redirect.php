<?php
include './config/config.php';

$totalAmount = 0;
$orderID = 0;

if (isset($_GET['total']) AND isset($_GET['oid'])) {
    $totalAmount = base64_decode($_GET['total']);
    $orderID = base64_decode($_GET['oid']);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="ico/favicon.png">

        <title>Ticket Chai | Buy Online Tickets... </title>

        <?php include basePath('header_script.php'); ?>
    </head>
    <body class="home">
        <header>
            <div class="header-wrapper">
                <?php include basePath('menu_top.php'); ?>
                <?php include basePath('navigation.php'); ?>
            </div>
        </header>


        <div class="main-container page-404">
            <div class="dtable hw100">
                <div class="dtable-cell hw100">
                    <div class="container" style="padding: 15px 0 !important;">
                        <div class="text-center">
                            <h4 style="text-transform: uppercase; font-weight: bold;">Please wait while we redirect you to payment gateway.</h4>
                            <h1 class="title-404"><img src="<?php echo baseUrl(); ?>images/redirect.gif"></h1>
                        </div>
                        <form action="https://www.sslcommerz.com.bd/process/index.php" method="post" name="form1" id="sslform">
                            <input type="hidden" name="store_id" value="ticketchailive001"> 
                            <?php
//https://www.sslcommerz.com.bd/process/index.php
//ticketchailive001
//systechunimaxtest001
                            ?>
                            <input type="hidden" id="total_amount_ssl" name="total_amount" value="<?php echo $totalAmount; ?>">
                            <input type="hidden" id="trans_id_ssl" name="tran_id" value="<?php echo $orderID; ?>">
                            <input type="hidden" id="notify_url" name="success_url" value="<?php echo baseUrl(); ?>confirmation/success/card/<?php echo $orderID; ?>">
                            <input type="hidden" id="fail_url" name="fail_url" value = "<?php echo baseUrl(); ?>confirmation/fail/card/<?php echo $orderID; ?>">
                            <input type="hidden" id="cancle_url" name="cancel_url" value = "<?php echo baseUrl(); ?>confirmation/cancel/card/<?php echo $orderID; ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <?php // include basePath('testimonial.php'); ?>
        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>
        <script>
        $(document).ready(function(){
           $('#sslform').submit(); 
        });
        </script>
    </body>
</html>