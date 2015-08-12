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

        <div class="main-container cart-container">
            <div class="container">
                <h1><i class="fa fa-shopping-cart"></i> Cart</h1>
                <ul class="nav nav-pills nav-justified checkout-bar">
                    <li class="active"><a href="<?php echo baseUrl(); ?>checkout"><span>1</span> Checkout Method</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>shipping-billing"><span>2</span> Billing &amp; Shipping</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>payment"><span>3</span> Your Order &amp; Payment</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>confirmation"><span>4</span> Confirmation</a> </li>
                </ul>

                <div class="row">
                    <div class="col-md-9 col-sm-9">
                        <div class="common-box">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="col-inside">
                                        <h3 class="col-title-h3">Returning customers</h3>
                                        <p>If you have shopped with us before, please enter your details in the boxes below.</p>

                                        <form class="sign-form">
                                            <div class="form-group-icon">
                                                <label for="uname"><i class="fa fa-user"></i></label>
                                                <input type="text" placeholder="Username" class="form-control" id="uname">
                                            </div>
                                            <div class="form-group-icon">
                                                <label for="pass"><i class="fa fa-lock"></i></label>
                                                <input type="text" placeholder="Password" class="form-control" id="pass">
                                            </div>
                                            <a class="btn btn-primary btn-lg btn-block" href="">Login</a>
                                        </form>
                                        <div class="remind-bar clearfix">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> Keep me login
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 text-right">
                                                    <a href="">Lost your password?</a>
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
                                        <a class="btn btn-default btn-lg" href="<?php echo baseUrl(); ?>signin-signup">Create Account</a>
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
                                            <td>Total Items</td>
                                            <td>8</td>
                                        </tr>
                                        <tr>
                                            <td>Total Price</td>
                                            <td>TK. 1280</td>
                                        </tr>
                                        <tr>
                                            <td>Discount</td>
                                            <td>TK. 200</td>
                                        </tr>
                                        <tr>
                                            <td>Tax</td>
                                            <td>TK. 100</td>
                                        </tr>
                                        <tr>
                                            <td>Shipping</td>
                                            <td><strong class="free">FREE!</strong></td>
                                        </tr>
                                        <tr class="cartTotal">
                                            <td>Subtotal</td>
                                            <td>TK. 980</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="cart-summry-btm">
                                    <div class="input-group">
                                        <input type="text" placeholder="Copne Code" class="form-control">
                                        <div class="input-group-btn">
                                            <button class="btn btn-default " type="button">Apply </button>
                                        </div>
                                        <!-- /btn-group -->
                                    </div>
                                    <h3>
                                        <a class="btn btn-default btn-primary btn-lg btn-block" href="<?php echo baseUrl(); ?>shipping-billing">Place Order <i class="fa fa-angle-right"></i></a>
                                    </h3>
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