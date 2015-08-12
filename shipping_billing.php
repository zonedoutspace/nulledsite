<?php
include "config/config.php";

$userID = 0;
$shipping = 0;
$billing = 0;

$UA_billing_first_name = "";
$UA_billing_last_name = "";
$UA_billing_phone = "";
$UA_billing_title = "";
$UA_billing_address = "";
$UA_billing_zip = "";
$UA_billing_country_id = "";
$UA_billing_city_id = "";
$UA_billing_user_id = "";
$UA_shipping_first_name = "";
$UA_shipping_last_name = "";
$UA_shipping_phone = "";
$UA_shipping_title = "";
$UA_shipping_address = "";
$UA_shipping_zip = "";
$UA_shipping_country_id = "";
$UA_shipping_city_id = "";
$UA_shipping_user_id = "";
$deliveryCharge = 0;
$defaultShipping = 0;
$defaultBilling = 0;
$chkFlag = 0;
$sameAsBilling = '';
$UA_city_id_shipping = 0;
$method = "";

if (checkUserLogin()) {
    $userID = getSession('user_id');
} else {
    $link = baseUrl() . 'signin-signup/check';
    redirect($link);
}


//get default address from user table
$sqlDefaultAdd = "SELECT user_default_shipping,user_default_billing,UA_city_id "
        . "FROM users "
        . "LEFT JOIN user_addresses ON UA_id=user_default_shipping "
        . "WHERE user_id=$userID";
$resultDefaultAdd = mysqli_query($con, $sqlDefaultAdd);
if ($resultDefaultAdd) {
    $resultDefaultAddObj = mysqli_fetch_object($resultDefaultAdd);
    if (isset($resultDefaultAddObj->user_default_shipping)) {
        $defaultShipping = $resultDefaultAddObj->user_default_shipping;
        $defaultBilling = $resultDefaultAddObj->user_default_billing;
        $UA_city_id_shipping = $resultDefaultAddObj->UA_city_id;

        if ($UA_city_id_shipping > 0) {
            $sqlGetDeliveryCost = "SELECT city_delivery_charge FROM cities WHERE city_id=$UA_city_id_shipping";
            $resultGetDeliveryCost = mysqli_query($con, $sqlGetDeliveryCost);

            if ($resultGetDeliveryCost) {
                $resultGetDeliveryCostObj = mysqli_fetch_object($resultGetDeliveryCost);
                if (isset($resultGetDeliveryCostObj->city_delivery_charge)) {
                    $deliveryCharge = $resultGetDeliveryCostObj->city_delivery_charge;
                }
            } else {
                
            }
        }
    }
} else {
    if (DEBUG) {
        $err = "getDefaultAdd error: " . mysqli_error($con);
    } else {
        $err = "getDefaultAdd query failed";
    }
}

if ($defaultShipping >= 0 AND $defaultBilling > 0) {
    $arrUserAddress = array();
    if ($userID > 0) {
        $sqlGetAddress = "SELECT user_addresses.*, cities.city_name,countries.country_name"
                . " FROM user_addresses"
                . " LEFT JOIN cities ON user_addresses.UA_city_id = cities.city_id"
                . " LEFT JOIN countries ON user_addresses.UA_country_id = countries.country_id"
                . " WHERE UA_id IN ($defaultShipping,$defaultBilling)";
        $resultGetAddress = mysqli_query($con, $sqlGetAddress);
        if ($resultGetAddress) {
            while ($resultGetAddressObj = mysqli_fetch_object($resultGetAddress)) {
                $arrUserAddress[$resultGetAddressObj->UA_id] = $resultGetAddressObj;
            }
        } else {
            if (DEBUG) {
                echo "resultGetAddress error: " . mysqli_error($con);
            } else {
                echo "resultGetAddress query failed.";
            }
        }
    }


    if (count($arrUserAddress[$defaultBilling]) > 0) {
        $UA_billing_phone = $arrUserAddress[$defaultBilling]->UA_phone;
        $UA_billing_title = $arrUserAddress[$defaultBilling]->UA_title;
        $UA_billing_address = $arrUserAddress[$defaultBilling]->UA_address;
        $UA_billing_zip = $arrUserAddress[$defaultBilling]->UA_zip;
        $UA_billing_country_id = $arrUserAddress[$defaultBilling]->UA_country_id;
        $UA_billing_city_id = $arrUserAddress[$defaultBilling]->UA_city_id;
        $UA_billing_user_id = $arrUserAddress[$defaultBilling]->UA_user_id;
    }


    if ($defaultShipping > 0) {
        if (count($arrUserAddress[$defaultShipping]) > 0) {
            $UA_shipping_phone = $arrUserAddress[$defaultShipping]->UA_phone;
            $UA_shipping_title = $arrUserAddress[$defaultShipping]->UA_title;
            $UA_shipping_address = $arrUserAddress[$defaultShipping]->UA_address;
            $UA_shipping_zip = $arrUserAddress[$defaultShipping]->UA_zip;
            $UA_shipping_country_id = $arrUserAddress[$defaultShipping]->UA_country_id;
            $UA_shipping_city_id = $arrUserAddress[$defaultShipping]->UA_city_id;
            $UA_shipping_user_id = $arrUserAddress[$defaultShipping]->UA_user_id;
        }
    }
}




// Get All Countries For User Address

$countryArray = array();
$countrySql = "SELECT * FROM countries WHERE country_status = 'allow'";
$resultCountry = mysqli_query($con, $countrySql);
if ($resultCountry) {
    while ($rowCountry = mysqli_fetch_object($resultCountry)) {
        $countryArray[] = $rowCountry;
    }
} else {
    if (DEBUG) {
        $err = "resultCountry error: " . mysqli_error($con);
    } else {
        $err = "resultCountry query failed";
    }
}

// Get All Cities For User Address
$cityArray = array();
$citySql = "SELECT * FROM cities WHERE city_status = 'allow'";
$resultCity = mysqli_query($con, $citySql);
if ($resultCity) {
    while ($rowCity = mysqli_fetch_object($resultCity)) {
        $cityArray[] = $rowCity;
    }
} else {
    if (DEBUG) {
        $err = "resultCity error: " . mysqli_error($con);
    } else {
        $err = "resultCity query failed";
    }
}


if (isset($_POST['type']) AND $_POST['type'] == true) {
    extract($_POST);
//    debug($_POST);

    //query for billing address
    $makeQueryBilling = '';
    $makeQueryBilling .= ' UA_title = "' . mysqli_real_escape_string($con, $UA_billing_title) . '"';
    $makeQueryBilling .= ', UA_user_id = "' . intval($userID) . '"';
    $makeQueryBilling .= ', UA_phone = "' . mysqli_real_escape_string($con, $UA_billing_phone) . '"';
    $makeQueryBilling .= ', UA_country_id = "' . intval($UA_billing_country_id) . '"';
    $makeQueryBilling .= ', UA_city_id = "' . intval($UA_billing_city_id) . '"';
    $makeQueryBilling .= ', UA_zip = "' . mysqli_real_escape_string($con, $UA_billing_zip) . '"';
    $makeQueryBilling .= ', UA_address = "' . mysqli_real_escape_string($con, $UA_billing_address) . '"';

    if ($defaultBilling > 0) {
        //updating billing address
        $sqlUpdateBilling = "UPDATE user_addresses SET $makeQueryBilling WHERE UA_id=$defaultBilling";
        $resultUpdateBilling = mysqli_query($con, $sqlUpdateBilling);

        if (!$resultUpdateBilling) {
            $chkFlag++;
            if (DEBUG) {
                echo "resultUpdateBilling error: " . mysqli_error($con);
            }
        }
    } else {
        //inserting billing address
        $sqlUpdateBilling = "INSERT INTO user_addresses SET $makeQueryBilling";
        $resultUpdateBilling = mysqli_query($con, $sqlUpdateBilling);

        if (!$resultUpdateBilling) {
            $chkFlag++;
            if (DEBUG) {
                echo "resultUpdateBilling error: " . mysqli_error($con);
            }
        } else {
            $defaultBilling = mysqli_insert_id($con);
        }
    }

    if (isset($method) AND $method == "deliver") {
        if (!isset($_POST['sameAsBilling'])) {
            //query for billing address
            $makeQueryShipping = '';
            $makeQueryShipping .= ' UA_title = "' . mysqli_real_escape_string($con, $UA_shipping_title) . '"';
            $makeQueryShipping .= ', UA_user_id = "' . intval($userID) . '"';
            $makeQueryShipping .= ', UA_phone = "' . mysqli_real_escape_string($con, $UA_shipping_phone) . '"';
            $makeQueryShipping .= ', UA_country_id = "' . intval($UA_shipping_country_id) . '"';
            $makeQueryShipping .= ', UA_city_id = "' . intval($UA_shipping_city_id) . '"';
            $makeQueryShipping .= ', UA_zip = "' . mysqli_real_escape_string($con, $UA_shipping_zip) . '"';
            $makeQueryShipping .= ', UA_address = "' . mysqli_real_escape_string($con, $UA_shipping_address) . '"';

            if ($defaultShipping > 0) {
                //updating existing default shipping address
                $sqlUpdateShipping = "UPDATE user_addresses SET $makeQueryShipping WHERE UA_id=$defaultShipping";
                $resultUpdateShipping = mysqli_query($con, $sqlUpdateShipping);

                if (!$resultUpdateShipping) {
                    $chkFlag++;
                    if (DEBUG) {
                        echo "resultUpdateShipping error: " . mysqli_error($con);
                    }
                }
            } else {
                //adding new shipping address
                $sqlUpdateShipping = "INSERT INTO user_addresses SET $makeQueryShipping";
                $resultUpdateShipping = mysqli_query($con, $sqlUpdateShipping);

                if (!$resultUpdateShipping) {
                    $chkFlag++;
                    if (DEBUG) {
                        echo "resultUpdateShipping error: " . mysqli_error($con);
                    }
                } else {
                    $defaultShipping = mysqli_insert_id($con);
                }
            }
        }
    }

    if (isset($method) AND $method == "deliver") {
        //updating user table with default billing & shipping id
        $sqlUpdateDefaultAddressDeliver = "UPDATE users SET user_default_shipping=$defaultShipping,user_default_billing=$defaultBilling WHERE user_id=$userID";
        $resultUpdateDefaultAddressDeliver = mysqli_query($con, $sqlUpdateDefaultAddressDeliver);
        if (!$resultUpdateDefaultAddressDeliver) {
            $chkFlag++;
            if (DEBUG) {
                echo "resultUpdateDefaultAddressDeliver error: " . mysqli_error($con);
            }
        }
    } else {
        //updating user table with default billing & shipping id
        $sqlUpdateDefaultAddress = "UPDATE users SET user_default_billing=$defaultBilling WHERE user_id=$userID";
        $resultUpdateDefaultAddress = mysqli_query($con, $sqlUpdateDefaultAddress);
        if (!$resultUpdateDefaultAddress) {
            $chkFlag++;
            if (DEBUG) {
                echo "resultUpdateDefaultAddress error: " . mysqli_error($con);
            }
        }
    }

    //redirecting to next step
    if ($chkFlag == 0) {
        if (isset($method) AND $method == "deliver") {
            if (!isset($_POST['sameAsBilling'])) {
                $link = baseUrl() . "checkout-step-two/" . $defaultBilling . "/" . $defaultShipping . "/" . base64_encode($method);
            } else {
                $link = baseUrl() . "checkout-step-two/" . $defaultBilling . "/" . $defaultBilling . "/" . base64_encode($method);
            }
        } else {
            $link = baseUrl() . "checkout-step-two/" . $defaultBilling . "/0/" . base64_encode($method);
        }
        redirect($link);
    }
}
//debug($arrUserAddress);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include basePath('header_script.php'); ?>

        <style>
            #map-canvas {
                width: 350px;
                height: 250px;
                margin-bottom: 20px;
            }
        </style>
        <script src="https://maps.googleapis.com/maps/api/js"></script>
        <script>
            function initialize() {
                var myLatlng = new google.maps.LatLng(23.748329, 90.403665);
                var mapOptions = {
                    zoom: 16,
                    center: myLatlng
                }
                var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title: 'Hello World!'
                });
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
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
                <div class="cart-page-head">
                    <h1><i class="fa fa-map-marker"></i> Select Addresses</h1>
                </div>

                <ul class="nav nav-pills nav-justified checkout-bar">
                    <li><a href="<?php echo baseUrl(); ?>signin-signup/check"><span class="fa fa-check"></span> Signin/Signup</a> </li>
                    <li class="active"><a href="<?php echo baseUrl(); ?>checkout-step-one"><span>2</span> Select Addresses</a> </li>
                    <li><a <a href="<?php echo baseUrl(); ?>checkout-step-two"><span>3</span> Choose Payment</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>checkout-step-three"><span>4</span> Confirm Order</a> </li>
                </ul>

                <div class="row">
                    <div class="col-md-9 col-sm-9">
                        <div class="common-box">
                            <div class="">
                                <form id="addressForm" method="post" action="<?php echo baseUrl(); ?>checkout-step-one">
                                    <h1 class="col-title-h3">I want to...</h1>
                                    <div class="row" style="margin-bottom: 80px; margin-top: 40px;">
                                        <div class="col-md-6 col-sm-12 text-left">
                                            <input name="method" value="pickup" id="radioPickup" type="radio" <?php if($method == "pickup"){ echo "checked='checked'"; } ?>>&nbsp;&nbsp;<span class="txtMethod" style="font-size: medium; color: #599b09;"><strong>Pick from Ticketchai office.</strong></span>
                                        </div>
                                        <div class="col-md-6 col-sm-12 text-left">
                                            <input name="method" value="deliver" id="radioDeliver" type="radio" <?php if($method == "deliver"){ echo "checked='checked'"; } ?>>&nbsp;&nbsp;<span class="txtMethod" style="font-size: medium; color: #599b09;"><strong>Have my ticket(s) delivered at home.</strong></span>
                                        </div>
                                    </div>
                                    <hr class="site">
                                    <h3 class="col-title-h3">Select Delivery &amp; Billing Address</h3>
                                    <div class="address-bock">
                                        <div class="row">

                                        <!--<form id="addressForm" method="post" action="<?php echo baseUrl(); ?>checkout-step-one">-->

                                            <div class="col-md-6 col-sm-12 col-xs-12 pull-left" style="border-right: #599b09 1px dotted !important;">

                                                <h4>Your Billing Address</h4>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Address:</label>
                                                    <textarea name="UA_billing_address" data-field-name="Billing Address Details" id="UA_billing_address" type="text" class="form-control" id="exampleInputEmail1"/><?php echo $UA_billing_address; ?></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label>City: </label>
                                                    <select name="UA_billing_city_id" data-field-name="Billing City" class="form-control" id="UA_billing_city_id">
                                                        <option value="0">Select City</option>
                                                        <?php if (count($cityArray) >= 1): ?>
                                                            <?php foreach ($cityArray as $city): ?>
                                                                <option 
                                                                    value="<?php echo $city->city_id; ?>"
                                                                    <?php
                                                                    if ($city->city_id == $UA_billing_city_id) {
                                                                        echo ' selected="selected"';
                                                                    }
                                                                    ?>>
                                                                        <?php echo $city->city_name; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Zip/Postal Code: </label>
                                                    <input name="UA_billing_zip" data-field-name="Billing Zip/Postal Code" id="UA_billing_zip" value="<?php echo $UA_billing_zip; ?>" type="text" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label>Country: </label>
                                                    <select name="UA_billing_country_id" data-field-name="Billing Country" id="UA_billing_country_id" class="form-control">
                                                        <option value="0">Select Country</option>
                                                        <?php if (count($countryArray) >= 1): ?>
                                                            <?php foreach ($countryArray as $country): ?>
                                                                <option 
                                                                    value="<?php echo $country->country_id; ?>"
                                                                    <?php
                                                                    if ($country->country_id == $UA_billing_country_id) {
                                                                        echo ' selected="selected"';
                                                                    }
                                                                    ?>>
                                                                        <?php echo $country->country_name; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Phone: </label>
                                                    <input name="UA_billing_phone" data-field-name="Billing Phone" id="UA_billing_phone" value="<?php echo $UA_billing_phone; ?>" type="text" class="form-control">
                                                </div>
                                                <br/>

                                                <div class="form-group" id="checkBillToShip" style="display: none;">
                                                    <input name="sameAsBilling" id="makeBillingDefault" type="checkbox" >&nbsp;&nbsp;<span style="color: #599b09"><strong>Make this my delivery address</strong></span>
                                                </div>


                                            </div>





                                            <div class="col-md-6 col-sm-12 col-xs-12 pull-right" id="shippingHtml">

                                                <div id="pickupAddress" style="display: none;">
                                                    <div id="map-canvas"></div>
                                                    <h4>Our Office Address</h4>
                                                    <ul class="list-unstyled footer-list">
                                                        <li><i class="fa fa-map-marker"></i>
                                                            <span>Razzak Plaza (8th Floor),1 New Eskaton Road,
                                                                Moghbazar Circle, Dhaka-1217 
                                                            </span>
                                                        </li>
                                                        <li><i class="fa fa-phone"></i> <span>+880-1971-842538</span>,<span>+880-447-8009569</span></li>
                                                    </ul>
                                                </div>

                                                <div id="shippingAddress" style="display: none;">
                                                    <h4>Your Delivery Address</h4>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Address:</label>
                                                        <textarea name="UA_shipping_address" data-field-name="Shipping Address Details" id="UA_shipping_address" type="text" class="form-control" id="exampleInputEmail1"><?php echo $UA_shipping_address; ?></textarea>
                                                    </div>


                                                    <div class="form-group">
                                                        <label>City: </label>
                                                        <select onchange="javascript:getDeliveryCost(this.value);" name="UA_shipping_city_id" data-field-name="Shipping City" id="UA_shipping_city_id" class="form-control">
                                                            <option value="0">Select City</option>
                                                            <?php if (count($cityArray) >= 1): ?>
                                                                <?php foreach ($cityArray as $city): ?>
                                                                    <option 
                                                                        value="<?php echo $city->city_id; ?>"
                                                                        <?php
                                                                        if ($city->city_id == $UA_shipping_city_id) {
                                                                            echo ' selected="selected"';
                                                                        }
                                                                        ?>>
                                                                            <?php echo $city->city_name; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Zip/Postal Code: </label>
                                                        <input name="UA_shipping_zip" data-field-name="Shipping Zip/Postal Code" id="UA_shipping_zip" value="<?php echo $UA_shipping_zip; ?>" type="text" class="form-control">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Country: </label>
                                                        <select name="UA_shipping_country_id" data-field-name="Shipping Country" id="UA_shipping_country_id" class="form-control">
                                                            <option value="0">Select Country</option>
                                                            <?php if (count($countryArray) >= 1): ?>
                                                                <?php foreach ($countryArray as $country): ?>
                                                                    <option 
                                                                        value="<?php echo $country->country_id; ?>"
                                                                        <?php
                                                                        if ($country->country_id == $UA_shipping_country_id) {
                                                                            echo ' selected="selected"';
                                                                        }
                                                                        ?>>
                                                                            <?php echo $country->country_name; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Phone: </label>
                                                        <input name="UA_shipping_phone" data-field-name="Shipping Phone" id="UA_shipping_phone" value="<?php echo $UA_shipping_phone; ?>" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div><br/>
                                                <input type="hidden" name="type" value="true"/> 

                                            </div>
                                            <!--</form>-->
                                        </div>
                                    </div>

                                </form>
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

                                        <tr style="color: #900; <?php
                                        if ($totalDiscount == 0) {
                                            echo "display: none";
                                        }
                                        ?>">
                                            <td>Discount</td>
                                            <td><?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<?php echo number_format($totalDiscount, 2); ?></td>
                                        </tr>

                                        <tr id="showDelivery" style="<?php
                                        if ($deliveryCharge == 0) {
                                            echo "display: none";
                                        }
                                        ?>">
                                            <td>Delivery Charge</td>
                                            <td><?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<span id="deliveryCost"><?php echo number_format($deliveryCharge, 2); ?></span></td>
                                        </tr>
                                        <tr class="cartTotal" style="font-weight: bold; font-size: medium;">
                                            <td>Subtotal</td>
                                            <td><?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<span class="sub-Total"><?php echo number_format($totalCartAmount + $deliveryCharge, 2); ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="cart-summry-btm">
                                    <h3><button onclick="javscript:verifyAddressID();" name="submitAddress" type="button" class="btn btn-default btn-primary btn-lg btn-block btn-continue">Continue <i class="fa fa-angle-right"></i></button></h3>
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