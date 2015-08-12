<?php
include "config/config.php";

if (checkUserLogin()) {
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
        if ($type == "check") {
            $link = baseUrl() . "checkout-step-one";
            redirect($link);
        } else {
            
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

        <div class="main-container cart-container">
            <div class="container">
                <h1><i class="fa fa-sign-in"></i> Signin/Signup</h1>
                <ul class="nav nav-pills nav-justified checkout-bar">
                    <li class="active"><a href="<?php echo baseUrl(); ?>signin-signup"><span>1</span> Signin/Signup</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>checkout-step-one"><span>2</span> Select Addresses</a> </li>
                    <li><a <a href="<?php echo baseUrl(); ?>checkout-step-two"><span>3</span> Choose Payment</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>checkout-step-three"><span>4</span> Confirm Order</a> </li>
                </ul>

                <div class="row">
                    <div class="col-md-9 col-sm-9">
                        <div class="common-box">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group-icon col-md-6 pull-left">
                                        <a href="javascript:void(0);" onclick="facebookLogin();" class="btn btn-lg btn-block btn-facebook">
                                            <i class="fa fa-facebook"></i> Sign In with Facebook
                                        </a>
                                    </div>
                                    <div class="form-group-icon col-md-6 pull-left">
                                        <a href="javascript:void(0);" onclick="googleLogin();" style="background-color: #dd4b39;" class="btn btn-lg btn-block btn-facebook">
                                            <i class="fa fa-google-plus"></i> Sign In with Google+
                                        </a>
                                    </div>
                                </div> 
                                <br/><br/><br/>
                                <h4 class="text-center">--------- OR ---------</h4>
                                <div class="col-md-6 col-sm-6">
                                    <div class="col-inside">
                                        <h3 class="col-title-h3">Returning customers</h3>
                                        <p>If you have shopped with us before, please enter your details in the boxes below.</p>

                                        <form class="sign-form">
                                            <div class="form-group-icon">
                                                <label><i class="fa fa-user"></i></label>
                                                <input type="text" id="signin_user_email" name="user_email" placeholder="Email address" class="form-control" id="uname">
                                            </div>
                                            <div class="form-group-icon">
                                                <label><i class="fa fa-lock"></i></label>
                                                <input type="password" id="signin_user_password" name="user_password" placeholder="Password" class="form-control" id="pass">
                                            </div>
                                            <a class="btn btn-primary btn-lg btn-block" href="javascript:void(0);" id="clickSignIn">Login</a>
                                        </form>
                                        <div class="remind-bar clearfix">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <!--<div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> Keep me login
                                                        </label>
                                                    </div>-->
                                                </div>
                                                <div class="col-md-6 col-sm-6 text-right">
                                                    <a  href="<?php echo baseUrl('forget_password.php'); ?>">Lost your password?</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6">
                                    <div class="col-inside">
                                        <h3 class="col-title-h3">New customers</h3>
                                        <p>
                                            You can checkout without creating an account. You will have a chance to create an account later.
                                        </p>
                                        <a class="btn btn-default btn-lg" href="javascript:void(0);" data-toggle="modal" data-target="#signup">Create Account</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3 col-sm-3 right-siderbar">
                        <div class="common-box">
                            <div class="sidebar-cart">

                                <h4 class="sidebar-title cart-summary">Cart Summary

                                </h4>


                                <table class="table table-cart-summary table-custom-padd">
                                    <tbody>
                                        <tr>
                                            <td>Total Event</td>
                                            <td><?php echo $totalEventCount; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Total Event Items</td>
                                            <td><?php echo $totalItemCount; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Total Price</td>
                                            <td><?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<?php echo number_format(($totalCartAmount + $totalDiscount), 2); ?></td>
                                        </tr>
                                        <?php if ($totalDiscount > 0): ?>
                                            <tr>
                                                <td style="color: #900;">Discount</td>
                                                <td><?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<?php echo number_format($totalDiscount, 2); ?></td>
                                            </tr>
                                        <?php endif; ?>
<!--                                        <tr>
                                            <td>Shipping</td>
                                            <td><strong class="free">FREE!</strong></td>
                                        </tr>-->
                                        <tr class="cartTotal" style="font-weight: bold; font-size: medium;">
                                            <td>Subtotal</td>
                                            <td><?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<?php echo number_format($totalCartAmount, 2); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                                                <div class="cart-summry-btm">
                                                                    <!--<h3><a class="btn btn-default btn-primary btn-lg btn-block" href="billing_shipping.html">Place Order <i class="fa fa-angle-right"></i></a></h3>-->
                                                                    <p class="text-center"><small>You can review this order before it's final</small></p>
                                                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div><!-- main-container-->
        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>
    </body>    
</html>