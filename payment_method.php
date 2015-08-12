<?php
include './config/config.php';

$shippingID = 0;
$billingID = 0;
$deliveryCost = 0;
$userID = 0;
$sessionID = session_id();
$payRadio = '';
$sslTotalAmount = 0;
$sslOrderID = 0;
$status = 0;
$couponDiscount = 0;
$couponCodeNo = '';
$orderMethod = "";

if (getSession('COUPON_CODE') == TRUE) {
    $couponDiscount = getSession('COUPON_CODE_AMOUNT');
    $couponCodeNo = getSession('COUPON_CODE_NO');
}

if (checkUserLogin()) {
    $userID = getSession('user_id');
} else {
    $link = baseUrl() . 'signin-signup/check';
    redirect($link);
}

//validating shipping and billing id and getting delivery cost from database using shipping id
if (isset($_GET['billing']) AND isset($_GET['shipping']) AND isset($_GET['method'])) {
    if ($_GET['billing'] > 0 AND $_GET['shipping'] >= 0) {
        $shippingID = validateInput($_GET['shipping']);
        $billingID = validateInput($_GET['billing']);
        $orderMethod = validateInput($_GET['method']);
        $orderMethod = base64_decode($orderMethod);

        $sqlGetDelCost = "SELECT city_delivery_charge FROM user_addresses "
                . "LEFT JOIN cities ON cities.city_id = user_addresses.UA_city_id "
                . "WHERE user_addresses.UA_id=$shippingID";
        $resultGetDelCost = mysqli_query($con, $sqlGetDelCost);
        if ($resultGetDelCost) {
            $resultGetDelCostObj = mysqli_fetch_object($resultGetDelCost);
            if (isset($resultGetDelCostObj->city_delivery_charge)) {
                $deliveryCost = $resultGetDelCostObj->city_delivery_charge;
            }
        } else {
            if (DEBUG) {
                echo "executeShippingAddress error: " . mysqli_error($con);
            } else {
                echo "executeShippingAddress query failed.";
            }
        }
    }
} else {
    $link = baseUrl() . "checkout-step-one";
    redirect($link);
}


if (isset($_POST['payRadio'])) {

    extract($_POST);

    //getting billing and shipping address details from database and updating database accordingly
    $arrayShippingAddresses = array();
    if ($shippingID >= 0 AND $billingID > 0) {
        $arrayShippingAddresses = array();
        $sqlShippingAddresses = "SELECT 
               user_addresses.UA_id,user_addresses.UA_title,user_addresses.UA_first_name,user_addresses.UA_middle_name,user_addresses.UA_last_name,user_addresses.UA_phone,user_addresses.UA_zip,user_addresses.UA_address,
               cities.city_name,
               countries.country_name
               
               FROM user_addresses
               
               LEFT JOIN cities ON cities.city_id = user_addresses.UA_city_id
               LEFT JOIN countries ON countries.country_id = user_addresses.UA_country_id
               WHERE user_addresses.UA_id IN ($shippingID,$billingID)";
        $executeShippingAddress = mysqli_query($con, $sqlShippingAddresses);
        if ($executeShippingAddress) {
            while ($executeShippingAddressObj = mysqli_fetch_object($executeShippingAddress)) {
                $arrayShippingAddresses[$executeShippingAddressObj->UA_id] = $executeShippingAddressObj;
            }
        } else {
            if (DEBUG) {
                echo "executeShippingAddress error: " . mysqli_error($con);
            } else {
                echo "executeShippingAddress query failed.";
            }
        }


        //getting shipping and billing address separated
        $shippingName = '';
        $shippingAddress = '';
        $shippingCity = '';
        $shippingZip = '';
        $shippingCountry = '';
        $shippingPhone = '';
        $billingName = '';
        $billingAddress = '';
        $billingCity = '';
        $billingZip = '';
        $billingCountry = '';
        $billingPhone = '';

        $countAddressArray = count($arrayShippingAddresses);
        if ($countAddressArray > 0) {
            for ($i = 0; $i < $countAddressArray; $i++) {
                if ($shippingID > 0) {
                    if (count($arrayShippingAddresses[$shippingID]) > 0) {
                        $shippingAddress = $arrayShippingAddresses[$shippingID]->UA_address;
                        $shippingCity = $arrayShippingAddresses[$shippingID]->city_name;
                        $shippingZip = $arrayShippingAddresses[$shippingID]->UA_zip;
                        $shippingCountry = $arrayShippingAddresses[$shippingID]->country_name;
                        $shippingPhone = $arrayShippingAddresses[$shippingID]->UA_phone;
                    }
                } else {
                    $shippingAddress = $arrayShippingAddresses[$billingID]->UA_address;
                    $shippingCity = $arrayShippingAddresses[$billingID]->city_name;
                    $shippingZip = $arrayShippingAddresses[$billingID]->UA_zip;
                    $shippingCountry = $arrayShippingAddresses[$billingID]->country_name;
                    $shippingPhone = $arrayShippingAddresses[$billingID]->UA_phone;
                }
                if (count($arrayShippingAddresses[$billingID]) > 0) {
                    $billingAddress = $arrayShippingAddresses[$billingID]->UA_address;
                    $billingCity = $arrayShippingAddresses[$billingID]->city_name;
                    $billingZip = $arrayShippingAddresses[$billingID]->UA_zip;
                    $billingCountry = $arrayShippingAddresses[$billingID]->country_name;
                    $billingPhone = $arrayShippingAddresses[$billingID]->UA_phone;
                }
            }
        }

        //getting all event information from event temp cart
        $arrEventTmpCart = array();
        $sqlGetTmpEvent = "SELECT * FROM event_temp_cart WHERE ETC_session_id='$sessionID'";
        $resultGetTmpEvent = mysqli_query($con, $sqlGetTmpEvent);

        if ($resultGetTmpEvent) {
            while ($resultGetTmpEventObj = mysqli_fetch_object($resultGetTmpEvent)) {
                $arrEventTmpCart[] = $resultGetTmpEventObj;
            }
        } else {
            if (DEBUG) {
                echo "resultGetTmpEvent error: " . mysqli_error($con);
            } else {
                echo "resultGetTmpEvent query failed.";
            }
        }


        //getting all event item information from event item temp cart
        $arrItemTmpCart = array();
        $intTotalItem = 0;
        $intCartTotal = 0;
        $intCartDiscount = 0;
        $intSubTotal = 0;
        $sqlGetTmpItem = "SELECT * FROM event_item_temp_cart WHERE EITC_session_id='$sessionID'";
        $resultGetTmpItem = mysqli_query($con, $sqlGetTmpItem);

        if ($resultGetTmpItem) {
            while ($resultGetTmpItemObj = mysqli_fetch_object($resultGetTmpItem)) {
                $arrItemTmpCart[$resultGetTmpItemObj->EITC_ETC_id][] = $resultGetTmpItemObj;
                $intTotalItem++;
                $intCartTotal += ($resultGetTmpItemObj->EITC_total_price + $resultGetTmpItemObj->EITC_total_discount);
                $intCartDiscount += $resultGetTmpItemObj->EITC_total_discount;
                $intSubTotal += $resultGetTmpItemObj->EITC_total_price;
            }
        } else {
            if (DEBUG) {
                echo "resultGetTmpItem error: " . mysqli_error($con);
            } else {
                echo "resultGetTmpItem query failed.";
            }
        }


        //getting all event item information from event item temp cart
        $arrItemSeatTmpCart = array();
        $sqlGetSeatTmpItem = "SELECT * FROM event_item_seat_temp_cart WHERE EISTC_session_id='$sessionID'";
        $resultGetSeatTmpItem = mysqli_query($con, $sqlGetSeatTmpItem);

        if ($resultGetSeatTmpItem) {
            while ($resultGetSeatTmpItemObj = mysqli_fetch_object($resultGetSeatTmpItem)) {
                $arrItemSeatTmpCart[$resultGetSeatTmpItemObj->EISTC_EITC_id][] = $resultGetSeatTmpItemObj;
            }
        } else {
            if (DEBUG) {
                echo "resultGetSeatTmpItem error: " . mysqli_error($con);
            } else {
                echo "resultGetSeatTmpItem query failed.";
            }
        }

        if ($intCartTotal > 0 AND $intSubTotal > 0) {
            $getLastOrderID = getMaxValue('orders', 'order_id');
            $orderDBID = $getLastOrderID + 1;
            $orderPublicID = '[' . date("dmy", time()) . '-' . $orderDBID . ']';
            $OrderPlaced = date("Y-m-d H:i:s", time());




            $placeNewOrder = '';
            $placeNewOrder .= ' order_id = "' . intval($orderDBID) . '"';
            $placeNewOrder .= ', order_user_id = "' . intval($userID) . '"';
            $placeNewOrder .= ', order_created = "' . mysqli_real_escape_string($con, $OrderPlaced) . '"';
            $placeNewOrder .= ', order_number = "' . mysqli_real_escape_string($con, $orderPublicID) . '"';
            $placeNewOrder .= ', order_status = "' . mysqli_real_escape_string($con, 'booking') . '"';
            //payment
            if ($payRadio == "card"):
                $placeNewOrder .= ', order_payment_type = "' . mysqli_real_escape_string($con, 'Card') . '"';
            elseif ($payRadio == "cod"):
                $placeNewOrder .= ', order_payment_type = "' . mysqli_real_escape_string($con, 'COD') . '"';
            endif;
            $placeNewOrder .= ', order_method = "' . mysqli_real_escape_string($con, $orderMethod) . '"';
            $placeNewOrder .= ', order_shipment_charge = "' . floatval($deliveryCost) . '"';
            $placeNewOrder .= ', order_total_item = "' . intval($intTotalItem) . '"';
            $placeNewOrder .= ', order_total_amount = "' . floatval($intSubTotal) . '"';
            $placeNewOrder .= ', order_discount_amount = "' . floatval($intCartDiscount) . '"';
            $placeNewOrder .= ', order_promotion_codes = "' . validateInput($couponCodeNo) . '"';
            $placeNewOrder .= ', order_promotion_discount_amount = "' . floatval($couponDiscount) . '"';
            $placeNewOrder .= ', order_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
            //Billing Address Insertion
            $placeNewOrder .= ', order_billing_phone = "' . mysqli_real_escape_string($con, $billingPhone) . '"';
            $placeNewOrder .= ', order_billing_country = "' . mysqli_real_escape_string($con, $billingCountry) . '"';
            $placeNewOrder .= ', order_billing_city = "' . mysqli_real_escape_string($con, $billingCity) . '"';
            $placeNewOrder .= ', order_billing_zip = "' . mysqli_real_escape_string($con, $billingZip) . '"';
            $placeNewOrder .= ', order_billing_address = "' . mysqli_real_escape_string($con, $billingAddress) . '"';

            //shipping address
            $placeNewOrder .= ', order_shipping_phone = "' . mysqli_real_escape_string($con, $shippingPhone) . '"';
            $placeNewOrder .= ', order_shipping_country = "' . mysqli_real_escape_string($con, $shippingCountry) . '"';
            $placeNewOrder .= ', order_shipping_city = "' . mysqli_real_escape_string($con, $shippingCity) . '"';
            $placeNewOrder .= ', order_shipping_zip = "' . mysqli_real_escape_string($con, $shippingZip) . '"';
            $placeNewOrder .= ', order_shipping_address = "' . mysqli_real_escape_string($con, $shippingAddress) . '"';

            $sqlPlaceOrder = "INSERT INTO orders SET $placeNewOrder";
            $executePlaceOrder = mysqli_query($con, $sqlPlaceOrder);

            if ($executePlaceOrder) {
                foreach ($arrEventTmpCart AS $OrderEvents) {
                    $insertOrderEvent = '';
                    $insertOrderEvent .= ' OE_order_id = "' . intval($orderDBID) . '"';
                    $insertOrderEvent .= ', OE_event_id = "' . intval($OrderEvents->ETC_event_id) . '"';
                    $insertOrderEvent .= ', OE_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
                    $insertOrderEvent .= ', OE_user_id = "' . intval($userID) . '"';

                    $sqlInsertOrderEvent = "INSERT INTO order_events SET $insertOrderEvent";
                    $resultInsertOrderEvent = mysqli_query($con, $sqlInsertOrderEvent);

                    if ($resultInsertOrderEvent) {
                        $OE_id = mysqli_insert_id($con);

                        if (isset($arrItemTmpCart[$OrderEvents->ETC_id])) {
                            foreach ($arrItemTmpCart[$OrderEvents->ETC_id] AS $OrderItems) {
                                if ($OrderItems->EITC_item_type == "seat") {

                                    $insertOrderItem = '';
                                    $insertOrderItem .= ' OI_OE_id = "' . intval($OE_id) . '"';
                                    $insertOrderItem .= ', OI_order_id = "' . intval($orderDBID) . '"';
                                    $insertOrderItem .= ', OI_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
                                    $insertOrderItem .= ', OI_unique_id = "' . mysqli_real_escape_string($con, '') . '"';
                                    $insertOrderItem .= ', OI_item_type = "' . mysqli_real_escape_string($con, $OrderItems->EITC_item_type) . '"';
                                    $insertOrderItem .= ', OI_venue_id = "' . intval($OrderItems->EITC_venue_id) . '"';
                                    $insertOrderItem .= ', OI_item_id = "' . intval($OrderItems->EITC_item_id) . '"';
                                    $insertOrderItem .= ', OI_quantity = "' . intval($OrderItems->EITC_quantity) . '"';
                                    $insertOrderItem .= ', OI_unit_price = "' . floatval($OrderItems->EITC_unit_price) . '"';
                                    $insertOrderItem .= ', OI_unit_discount = "' . floatval($OrderItems->EITC_unit_discount) . '"';

                                    $sqlInsertOrderItems = "INSERT INTO order_items SET $insertOrderItem";
                                    $resultInsertOrderItems = mysqli_query($con, $sqlInsertOrderItems);

                                    if ($resultInsertOrderItems) {
                                        $countQuantity = $OrderItems->EITC_quantity;
                                        $OI_id = mysqli_insert_id($con);

                                        foreach ($arrItemSeatTmpCart[$OrderItems->EITC_id] AS $orderItemSeat) {
                                            $insertOrderItemSeat = '';
                                            $insertOrderItemSeat .= ' OS_OI_id = "' . intval($OI_id) . '"';
                                            $insertOrderItemSeat .= ', OS_order_id = "' . intval($orderDBID) . '"';
                                            $insertOrderItemSeat .= ', OS_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
                                            $insertOrderItemSeat .= ', OS_unique_id = "' . mysqli_real_escape_string($con, randCode(29)) . '"';
                                            $insertOrderItemSeat .= ', OS_event_id = "' . intval($orderItemSeat->EISTC_event_id) . '"';
                                            $insertOrderItemSeat .= ', OS_venue_id = "' . intval($orderItemSeat->EISTC_venue_id) . '"';
                                            $insertOrderItemSeat .= ', OS_place_id = "' . intval($orderItemSeat->EISTC_place_id) . '"';
                                            $insertOrderItemSeat .= ', OS_coordinate_id = "' . intval($orderItemSeat->EISTC_coordinate_id) . '"';
                                            $insertOrderItemSeat .= ', OS_seat_number = "' . intval($orderItemSeat->EISTC_seat_number) . '"';

                                            $sqlInsertOrderItemSeats = "INSERT INTO order_seats SET $insertOrderItemSeat";
                                            $resultInsertOrderItemSeats = mysqli_query($con, $sqlInsertOrderItemSeats);

                                            if (!$resultInsertOrderItemSeats) {
                                                if (DEBUG) {
                                                    echo "resultInsertOrderItemSeats error: " . mysqli_error($con);
                                                    $status++;
                                                } else {
                                                    $status++;
                                                }
                                            }
                                        }
                                    } else {
                                        if (DEBUG) {
                                            echo "resultInsertOrderItems error: " . mysqli_error($con);
                                        } else {
                                            echo "resultInsertOrderItems query failed.";
                                        }
                                    }
                                } else {
                                    $countQuantity = $OrderItems->EITC_quantity;

                                    for ($i = 1; $i <= $countQuantity; $i++) {

                                        $insertOrderItem = '';
                                        $insertOrderItem .= ' OI_OE_id = "' . intval($OE_id) . '"';
                                        $insertOrderItem .= ', OI_order_id = "' . intval($orderDBID) . '"';
                                        $insertOrderItem .= ', OI_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
                                        $insertOrderItem .= ', OI_unique_id = "' . mysqli_real_escape_string($con, randCode(29)) . '"';
                                        $insertOrderItem .= ', OI_item_type = "' . mysqli_real_escape_string($con, $OrderItems->EITC_item_type) . '"';
                                        $insertOrderItem .= ', OI_venue_id = "' . intval($OrderItems->EITC_venue_id) . '"';
                                        $insertOrderItem .= ', OI_item_id = "' . intval($OrderItems->EITC_item_id) . '"';
                                        $insertOrderItem .= ', OI_quantity = "' . intval(1) . '"';
                                        $insertOrderItem .= ', OI_unit_price = "' . floatval($OrderItems->EITC_unit_price) . '"';
                                        $insertOrderItem .= ', OI_unit_discount = "' . floatval($OrderItems->EITC_unit_discount) . '"';

                                        $sqlInsertOrderItems = "INSERT INTO order_items SET $insertOrderItem";
                                        $resultInsertOrderItems = mysqli_query($con, $sqlInsertOrderItems);

                                        if (!$resultInsertOrderItems) {
                                            if (DEBUG) {
                                                echo "resultInsertOrderItems error: " . mysqli_error($con);
                                                $status++;
                                            } else {
                                                $status++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $sqlGetSeatTmpItem = "DELETE FROM event_item_seat_temp_cart WHERE EISTC_session_id='$sessionID'";
                        $resultGetSeatTmpItem = mysqli_query($con, $sqlGetSeatTmpItem);
                    } else {
                        if (DEBUG) {
                            echo "resultInsertOrderEvent error: " . mysqli_error($con);
                            $status++;
                        } else {
                            $status++;
                        }
                    }
                }
            } else {
                if (DEBUG) {
                    echo "resultInsertOrderEvent error: " . mysqli_error($con);
                    $status++;
                } else {
                    $status++;
                }
            }

            if ($status == 0) {
                if ($payRadio == "card") {
                    $sslTotalAmount = base64_encode($intSubTotal + $deliveryCost - $couponDiscount);
                    $sslOrderID = base64_encode($orderDBID);
                    $link = baseUrl() . "redirect/" . $sslTotalAmount . "/" . $sslOrderID;
                    redirect($link);
                } elseif ($payRadio == "cod") {
                    $link = baseUrl() . "confirmation/success/cod/" . $orderDBID;
                    redirect($link);
                }
            } else {
                if (DEBUG) {
                    $err = "Order saving process failed. Please check all queries.";
                } else {
                    $err = "Sorry, but somehow your order didn't saved successfully. Please try the process again.";
                }
            }
        }
    }
//    debug($arrayShippingAddresses);
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
                <div class="cart-page-head">
                    <h1><i class="fa fa-credit-card"></i> Payment Method</h1>
                </div>

                <ul class="nav nav-pills nav-justified checkout-bar">
                    <li><a href="<?php echo baseUrl(); ?>signin-signup/check"><span class="fa fa-check"></span> Signin/Signup</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>checkout-step-one"><span class="fa fa-check"></span> Select Addresses</a> </li>
                    <li class="active"><a href="<?php echo baseUrl(); ?>checkout-step-two"><span>3</span> Choose Payment</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>checkout-step-three"><span>4</span> Confirm Order</a> </li>
                </ul>


                <?php include basePath('admin/message.php'); ?>
                <form id="confirmOrder" method="post" action="<?php echo baseUrl(); ?>checkout-step-two/<?php echo $billingID; ?>/<?php echo $shippingID; ?>/<?php echo base64_encode($orderMethod); ?>">
                    <div class="row">
                        <div class="col-md-9 col-sm-9">
                            <div class="common-box">
                                <div class="">
                                    <h3 class="col-title-h3">Payment Method</h3>
                                    <div class="address-bock">
                                        <div class="row">

                                            <div class="col-md-6 col-sm-6 pull-left" style="padding: 15px;">
                                                <div class="col-pay">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="payRadio" value="card">&nbsp;&nbsp;<i style="font-size: large;" class="fa fa-credit-card"></i>&nbsp;&nbsp;Online Payment
                                                        </label>
                                                    </div>
                                                    <hr>
                                                    <div>
                                                        The easiest and safest way to send or receive money instantly on your mobile
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 pull-left" style="padding: 15px;">
                                                <div class="col-pay">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="payRadio" value="cod">&nbsp;&nbsp;<img src="<?php echo baseUrl(); ?>images/cash_on_delivery.png" alt="img">&nbsp;&nbsp;Cash on Delivery
                                                        </label>
                                                    </div>
                                                    <hr>
                                                    <div>
                                                        Cash on Delivery is one of the payment methods for making purchases on ticketchai.com.
                                                    </div>
                                                </div>
                                            </div>
                                            <br/><br/>
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

                                            <?php if ($deliveryCost > 0): ?>    
                                                <tr>
                                                    <td>Delivery Charge</td>
                                                    <td><?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<?php echo $deliveryCost; ?></td>
                                                </tr>
                                            <?php endif; ?>   

                                            <tr id="paymethodShowCoupon" <?php
                                            if (getSession('COUPON_CODE') != TRUE): echo 'style="display: none;"';
                                            endif;
                                            ?>>
                                                <td style="color: #900;">Coupon Discount</td>
                                                <td style="color: #900;">- <?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<span id="paymethodAmountCoupon"><?php echo number_format(getSession('COUPON_CODE_AMOUNT'), 2); ?></span></td>
                                            </tr>

                                            <tr class="cartTotal" style="font-weight: bold; font-size: medium;">
                                                <td>Subtotal</td>
                                                <td><?php echo $config['CURRENCY_SIGN']; ?>&nbsp;<span id="paymentSubtotal"><?php echo number_format($totalCartAmount + $deliveryCost - $couponDiscount, 2); ?></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="cart-summry-btm">
                                        <div class="input-group">
                                            <input type="text" placeholder="Coupon Code" class="form-control" id="coupon_code" name="coupon_code">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default" type="button" onclick="javascript:applyCoupon(<?php echo $totalCartAmount; ?>);">Apply</button>
                                            </div>
                                        </div>
                                        <p class="text-center"><small>Use above form to apply <strong>Coupon Code</strong>.</small></p>
                                        <h3><button onclick="javscript:verifyPayment();" name="submitAddress" type="button" class="btn btn-default btn-primary btn-lg btn-block btn-confirm">Confrim Order&nbsp;&nbsp;<i class="fa fa-angle-right"></i></button></h3>
                                        <p class="text-center"><small>Click <strong><a href="<?php echo baseUrl() . 'cart'; ?>">here</a></strong> to edit this order before confirm.</small></p>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </form>
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