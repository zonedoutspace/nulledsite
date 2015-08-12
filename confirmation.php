<?php
include './config/config.php';
include './lib/email/mail_helper_functions.php';
$userID = 0;

if (checkUserLogin()) {
    $userID = getSession('user_id');
} else {
    $link = baseUrl() . 'home';
    redirect($link);
}

$sessionID = session_id();
$orderStatus = '';
$paymentMethod = '';
$orderID = 0;
$statusMsg = '';
$status = 0;



if (isset($_GET['status']) AND isset($_GET['pay']) AND isset($_GET['oid'])) {
    unsetSession('COUPON_CODE');
    unsetSession('COUPON_CODE_NO');
    unsetSession('COUPON_CODE_AMOUNT');

    $orderStatus = validateInput($_GET['status']);
    $paymentMethod = validateInput($_GET['pay']);
    $orderID = validateInput($_GET['oid']);

    if ($orderStatus != "") {
        if ($orderStatus == "success") {

            //deleting all data in temp cart
            $sqlDeleteTmpEvent = "DELETE FROM event_temp_cart WHERE ETC_session_id='$sessionID'";
            $resultDeleteTmpEvent = mysqli_query($con, $sqlDeleteTmpEvent);
            if (!$resultDeleteTmpEvent) {
                $status++;
            }

            //deleting all data in temp cart items
            $sqlDeleteTmpItems = "DELETE FROM event_item_temp_cart WHERE EITC_session_id='$sessionID'";
            $resultDeleteTmpItems = mysqli_query($con, $sqlDeleteTmpItems);
            if (!$resultDeleteTmpItems) {
                $status++;
            }
            
            //deleting all data in temp cart item seats
            $sqlDeleteTmpItemSeats = "DELETE FROM event_item_seat_temp_cart WHERE EISTC_session_id='$sessionID'";
            $resultDeleteTmpItemSeats = mysqli_query($con, $sqlDeleteTmpItemSeats);
            if (!$resultDeleteTmpItemSeats) {
                $status++;
            }

            if ($paymentMethod == "card") {
                //updating status of the order
                $sqlUpdateOrder = "UPDATE orders SET order_status='approved' WHERE order_id=$orderID";
                $resultUpdateOrder = mysqli_query($con, $sqlUpdateOrder);

                if (!$resultUpdateOrder) {
                    $status++;
                }
            }
            
            //change ticket/include quantity
            $sqlGetItem = "SELECT * FROM order_items WHERE OI_order_id=$orderID";
            $resultGetItem = mysqli_query($con, $sqlGetItem);
            if ($resultGetItem) {
                while ($resultGetItemObj = mysqli_fetch_object($resultGetItem)) {
                    if ($resultGetItemObj->OI_item_type == 'ticket') {
                        $itemID = $resultGetItemObj->OI_item_id;

                        $sqlUpdateTicket = "UPDATE event_ticket_types SET TT_ticket_quantity = TT_ticket_quantity - 1 WHERE TT_id=$itemID";
                        $resultUpdateTicket = mysqli_query($con, $sqlUpdateTicket);

                        if (!$resultUpdateTicket) {
                            $err .= "Ticket quantity update failed.";
                        }
                    } elseif ($resultGetItemObj->OI_item_type == 'include') {
                        $itemID = $resultGetItemObj->OI_item_id;

                        $sqlUpdateInclude = "UPDATE event_includes SET EI_total_quantity = EI_total_quantity - 1 WHERE EI_id=$itemID";
                        $resultUpdateInclude = mysqli_query($con, $sqlUpdateInclude);

                        if (!$resultUpdateInclude) {
                            $err .= "Include quantity update failed.";
                        }
                    } elseif ($resultGetItemObj->OI_item_type == 'seat') {
                        $OI_id = $resultGetItemObj->OI_id;
                        $venueId = $resultGetItemObj->OI_venue_id;

                        $arrSeatInfo = array();
                        $sqlGetSeatInfo = "SELECT OS_seat_number,OS_place_id,OS_coordinate_id FROM order_seats WHERE OS_OI_id=$OI_id";
                        $resultGetSeatInfo = mysqli_query($con, $sqlGetSeatInfo);

                        if ($resultGetSeatInfo) {
                            while ($resultGetSeatInfoObj = mysqli_fetch_object($resultGetSeatInfo)) {
                                $arrSeatInfo[] = $resultGetSeatInfoObj;
                            }
                            if (!empty($arrSeatInfo)) {
                                foreach ($arrSeatInfo AS $SeatInfo) {
                                    $seatNumber = $SeatInfo->OS_seat_number;
                                    $placeId = $SeatInfo->OS_place_id;
                                    $coordinateId = $SeatInfo->OS_coordinate_id;

                                    $sqlUpdateSeatPlan = "UPDATE event_seat_plan "
                                            . "SET ESP_seats_booked=concat(ESP_seats_booked, '" . $seatNumber . ",') "
                                            . "WHERE ESP_venue_id=$venueId "
                                            . "AND ESP_place_id=$placeId "
                                            . "AND ESP_template_id=$coordinateId";
                                    $resultUpdateSeatPlan = mysqli_query($con, $sqlUpdateSeatPlan);

                                    if (!$resultUpdateSeatPlan) {
                                        $err .= "Include quantity update failed.";
                                    }
                                }
                            }
                        } else {
                            $err .= "Include quantity update failed.";
                        }

//                            if (!$resultUpdateInclude) {
//                                $err .= "Include quantity update failed.";
//                            }
                    }
                }
            } else {
                $status++;
            }

            if ($status > 0) {
                if (DEBUG) {
                    echo "Temp cart clearing query or Order status or ticket quantity update failed.";
                }
            }



            $statusMsg = '<h1 class="success-title" style="color:#005C00 !important;">Your order has been confirmed successfully.</h1>';
        } elseif ($orderStatus == "fail") {
            $statusMsg = '<h1 class="success-title" style="color:#990000 !important;">Oops!! somehow we failed to confirm your order. Please try again.</h1>';
        } elseif ($orderStatus == "cancel") {

            //deleting all data in temp cart
            $sqlDeleteTmpEvent = "DELETE FROM event_temp_cart WHERE ETC_session_id='$sessionID'";
            $resultDeleteTmpEvent = mysqli_query($con, $sqlDeleteTmpEvent);
            if (!$resultDeleteTmpEvent) {
                $status++;
            }

            //deleting all data in temp cart
            $sqlDeleteTmpItems = "DELETE FROM event_item_temp_cart WHERE EITC_session_id='$sessionID'";
            $resultDeleteTmpItems = mysqli_query($con, $sqlDeleteTmpItems);
            if (!$resultDeleteTmpItems) {
                $status++;
            }

            $sqlUpdateOrder = "UPDATE orders SET order_status='cancel' WHERE order_id=$orderID";
            $resultUpdateOrder = mysqli_query($con, $sqlUpdateOrder);

            if (!$resultUpdateOrder) {
                $status++;
            }

            if ($status > 0) {
                if (DEBUG) {
                    echo "Temp cart clearing query or Order update failed.";
                }
            }
            $statusMsg = '<h1 class="success-title" style="color:#CC7A29 !important;">Your order has been cancelled successfully. You can place order again.</h1>';
        }
    }

    $EmailSubject = "Your order details from TicketChai";
    $EmailBody = file_get_contents(baseUrl('email/body/order.php?order_id=' . $orderID));
    $sendMailStatus = sendEmailFunction(getSession('user_email'), getSession('user_first_name'), 'info@ticketchai.com', $EmailSubject, $EmailBody);

    $statusAdminEmail = 0;
    if (getConfig("NEW_ORDER_NOTIFY_EMAIL_ADDRESS") != ""):
        $arrAdminEmail = explode(',', getConfig("NEW_ORDER_NOTIFY_EMAIL_ADDRESS"));
        foreach ($arrAdminEmail AS $AdminEmail) {
            $EmailSubject = "New order notification from Ticketchai.com";
            $EmailBody = file_get_contents(baseUrl('email/body/order_admin.php?order_id=' . $orderID));
            $sendMailStatusAdmin = sendEmailFunction($AdminEmail, 'Ticketchai.com Admin', 'info@ticketchai.com', $EmailSubject, $EmailBody);
            if (!$sendMailStatusAdmin) {
                $statusAdminEmail++;
            }
        }
    endif;


    if (!$sendMailStatus OR $statusAdminEmail > 0) {
        if (DEBUG) {
            echo "Order confirmation email failed.";
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
                <div class="cart-page-head">
                    <h1><i class="fa fa-check"></i> Confirm Order</h1>
                </div>

                <ul class="nav nav-pills nav-justified checkout-bar">
                    <li><a href="<?php echo baseUrl(); ?>signin-signup/check"><span class="fa fa-check"></span> Signin/Signup</a> </li>
                    <li><a href="<?php echo baseUrl(); ?>checkout-step-one"><span class="fa fa-check"></span> Select Addresses</a> </li>
                    <li><a <a href="<?php echo baseUrl(); ?>checkout-step-two"><span class="fa fa-check"></span> Choose Payment</a> </li>
                    <li class="active"><a href="<?php echo baseUrl(); ?>checkout-step-three"><span>4</span> Confirm Order</a> </li>
                </ul>
                <div class="common-box">
                    <article class="success-content">
                        <?php echo $statusMsg; ?>
                        <?php if ($orderStatus == "success") : ?>
                            <p>We've sent a message to your email with order details.</p>
                            <p>Don't see it? Please check your spam folder.</p>
                            <a href="<?php echo baseUrl(); ?>home" class="btn btn-primary btn-sm margin-top-10 margin-bottom-20">Continue to Shopping</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo baseUrl(); ?>myorderlist" class="btn btn-primary btn-sm margin-top-10 margin-bottom-20">Go Order History</a>
                        <?php endif; ?>

                        <?php if ($orderStatus == "fail" OR $orderStatus == "cancel") : ?>
                            <p>We've are sorry for this inconvenience. But you can book tickets again.</p>
                            <p>Also you can check your order history for the record.</p>
                            <a href="<?php echo baseUrl(); ?>home" class="btn btn-primary btn-sm margin-top-10 margin-bottom-20">Continue to Shopping</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo baseUrl(); ?>myorderlist" class="btn btn-primary btn-sm margin-top-10 margin-bottom-20">Go Order History</a>
                        <?php endif; ?>

                    </article>
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