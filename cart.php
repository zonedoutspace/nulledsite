<?php
include './config/config.php';

$sessionID = session_id();
$totalItemCount = 0;

$arrTmpCartBigItem = array();
$strTicketID = '';
$strIncludeID = '';
$strSeatID = '';
$totalCartAmount = 0;
$totalDiscount = 0;
$sqlGetTmpCartItem = "SELECT * FROM event_item_temp_cart "
        . "WHERE EITC_session_id='$sessionID'";
$resultGetTmpCartItem = mysqli_query($con, $sqlGetTmpCartItem);
if ($resultGetTmpCartItem) {
    while ($resultGetTmpCartItemObj = mysqli_fetch_object($resultGetTmpCartItem)) {
        $arrTmpCartBigItem[] = $resultGetTmpCartItemObj;
        $totalCartAmount += $resultGetTmpCartItemObj->EITC_total_price;
        $totalDiscount += $resultGetTmpCartItemObj->EITC_total_discount;
        $totalItemCount += $resultGetTmpCartItemObj->EITC_quantity;
        if ($resultGetTmpCartItemObj->EITC_item_type == 'ticket') {
            $strTicketID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
        } elseif ($resultGetTmpCartItemObj->EITC_item_type == 'include') {
            $strIncludeID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
        } elseif ($resultGetTmpCartItemObj->EITC_item_type == 'seat') {
            $strSeatID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
        }
    }
} else {
    if (DEBUG) {
        echo "resultGetTmpCartItem error: " . mysqli_error($con);
    } else {
        echo "resultGetTmpCartItem query failed.";
    }
}

$strTicketID = trim($strTicketID, ',');
$strIncludeID = trim($strIncludeID, ',');
$strSeatID = trim($strSeatID, ',');

if ($strTicketID != "") {
    $arrCartSelectedTicket = array();
    $sqlGetSelectedTicket = "SELECT * FROM event_ticket_types "
            . "LEFT JOIN event_venues ON venue_id=TT_venue_id "
            . "LEFT JOIN events ON event_id=venue_event_id "
            . "WHERE TT_id IN ($strTicketID)";
    $resultGetSelectedTicket = mysqli_query($con, $sqlGetSelectedTicket);
    if ($resultGetSelectedTicket) {
        while ($resultGetSelectedTicketObj = mysqli_fetch_object($resultGetSelectedTicket)) {
            $arrCartSelectedTicket[$resultGetSelectedTicketObj->TT_id] = $resultGetSelectedTicketObj;
        }
    } else {
        if (DEBUG) {
            echo "resultGetSelectedTicket error: " . mysqli_error($con);
        } else {
            echo "resultGetSelectedTicket query failed.";
        }
    }
}


if ($strIncludeID != "") {
    $arrCartSelectedInclude = array();
    $sqlGetSelectedInclude = "SELECT * FROM event_includes "
            . "LEFT JOIN event_venues ON venue_id=EI_venue_id "
            . "LEFT JOIN events ON event_id=EI_event_id "
            . "WHERE EI_id IN ($strIncludeID)";
    $resultGetSelectedInclude = mysqli_query($con, $sqlGetSelectedInclude);
    if ($resultGetSelectedInclude) {
        while ($resultGetSelectedIncludeObj = mysqli_fetch_object($resultGetSelectedInclude)) {
            $arrCartSelectedInclude[$resultGetSelectedIncludeObj->EI_id] = $resultGetSelectedIncludeObj;
        }
    } else {
        if (DEBUG) {
            echo "resultGetSelectedInclude error: " . mysqli_error($con);
        } else {
            echo "resultGetSelectedInclude query failed.";
        }
    }
}

if ($strSeatID != "") {
    $arrCartSelectedSeat = array();
    $sqlGetSelectedSeat = "SELECT * FROM seat_place_coordinate "
            . "LEFT JOIN event_seat_plan ON ESP_template_id=SPC_id "
            . "LEFT JOIN event_venues ON venue_id=ESP_venue_id "
            . "LEFT JOIN events ON event_id=ESP_event_id "
            . "WHERE SPC_id IN ($strSeatID)";
    $resultGetSelectedSeat = mysqli_query($con, $sqlGetSelectedSeat);
    if ($resultGetSelectedSeat) {
        while ($resultGetSelectedSeatObj = mysqli_fetch_object($resultGetSelectedSeat)) {
            $arrCartSelectedSeat[$resultGetSelectedSeatObj->SPC_id] = $resultGetSelectedSeatObj;
        }
    } else {
        $checkStatus++;
        if (DEBUG) {
            echo "resultGetSelectedSeat error: " . mysqli_error($con);
        } else {
            echo "resultGetSelectedSeat query failed.";
        }
    }
}
//debug($arrCartSelectedTicket);
//debug($arrCartSelectedSeat);
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
                <div class="row">
                    <div class="col-md-9 col-sm-9">
                        <div class="common-box">
                            <table class="table table-cart table-custom-padd">
                                <thead>
                                    <tr>
                                        <th colspan="2">Item Details </th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>SubTotal</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody id="tblCartBody">

                                    <?php if (count($arrTmpCartBigItem) > 0): ?>
                                        <?php foreach ($arrTmpCartBigItem AS $CartItem): ?>
                                            <tr id="cartItem_<?php echo $CartItem->EITC_id; ?>">

                                                <?php if ($CartItem->EITC_item_type == "ticket"): ?>
                                                    <td style="width: 10%"><a target="_blank" href="<?php echo baseUrl(); ?>details/<?php echo $arrCartSelectedTicket[$CartItem->EITC_item_id]->event_id; ?>/<?php echo clean($arrCartSelectedTicket[$CartItem->EITC_item_id]->event_title); ?>" class="cart-thumb"><img alt="<?php echo $arrCartSelectedTicket[$CartItem->EITC_item_id]->event_title; ?>" src="<?php echo $config['IMAGE_UPLOAD_URL']; ?>/event_web_logo/<?php echo $arrCartSelectedTicket[$CartItem->EITC_item_id]->event_web_logo; ?>"></a></td>
                                                    <td style="width: 40%">
                                                        <p class="h5 top">
                                                        <h4><?php echo ucfirst($CartItem->EITC_item_type); ?>: &nbsp;<?php echo $arrCartSelectedTicket[$CartItem->EITC_item_id]->TT_type_title; ?></h4>
                                                        <em><a target="_blank" href="<?php echo baseUrl(); ?>details/<?php echo $arrCartSelectedTicket[$CartItem->EITC_item_id]->event_id; ?>/<?php echo clean($arrCartSelectedTicket[$CartItem->EITC_item_id]->event_title); ?>"><?php echo $arrCartSelectedTicket[$CartItem->EITC_item_id]->event_title; ?></a></em>
                                                        <p><small><i class="fa fa-map-marker"></i>&nbsp;<?php echo $arrCartSelectedTicket[$CartItem->EITC_item_id]->venue_title; ?><br/><i class="fa fa-calendar"></i>&nbsp;<?php echo date("d M, Y", strtotime($arrCartSelectedTicket[$CartItem->EITC_item_id]->venue_start_date)); ?></small></p>
                                                        </p>
                                                    </td>
                                                    <td style="width: 15%"><?php echo $config['CURRENCY_SIGN']; ?> <?php echo $CartItem->EITC_unit_price; ?></td>
                                                    <td style="width: 15%">
                                                        <div class="product-qty">
                                                            <span class="qty-block">
                                                                <button type="button" class="btn btn-default btn-sm pull-left" onclick="javascript:qntyDecrease(<?php echo $CartItem->EITC_id; ?>);"><i class="fa fa-minus"></i></button>
                                                                <input data-limit="<?php echo $arrCartSelectedTicket[$CartItem->EITC_item_id]->TT_per_user_limit; ?>" id="txtItemQuantity_<?php echo $CartItem->EITC_id; ?>" style="height: 28px; width: 28px; border:1px #79b92d solid ;" class="pull-left text-center" type="text" value="<?php echo $CartItem->EITC_quantity; ?>">
                                                                <button type="button" class="btn btn-default btn-sm pull-left" onclick="javascript:qntyIncrease(<?php echo $CartItem->EITC_id; ?>);"><i class="fa fa-plus"></i></button>
                                                            </span>
                                                        </div>

                                                    </td>
                                                <?php elseif ($CartItem->EITC_item_type == "include"): ?>
                                                    <td><a href="<?php echo baseUrl(); ?>details/<?php echo $arrCartSelectedInclude[$CartItem->EITC_item_id]->event_id; ?>/<?php echo clean($arrCartSelectedInclude[$CartItem->EITC_item_id]->event_title); ?>" class="cart-thumb"><img alt="<?php echo $arrCartSelectedInclude[$CartItem->EITC_item_id]->event_title; ?>" src="<?php echo $config['IMAGE_UPLOAD_URL']; ?>/event_web_logo/<?php echo $arrCartSelectedInclude[$CartItem->EITC_item_id]->event_web_logo; ?>"></a></td>
                                                    <td>
                                                        <p class="h5 top">
                                                        <h4><?php echo ucfirst($CartItem->EITC_item_type); ?>: &nbsp;<?php echo $arrCartSelectedInclude[$CartItem->EITC_item_id]->EI_name; ?></h4>
                                                        <em><a target="_blank" href="<?php echo baseUrl(); ?>details/<?php echo $arrCartSelectedInclude[$CartItem->EITC_item_id]->event_id; ?>/<?php echo clean($arrCartSelectedInclude[$CartItem->EITC_item_id]->event_title); ?>"><?php echo $arrCartSelectedInclude[$CartItem->EITC_item_id]->event_title; ?></a></em>
                                                        <p><small><i class="fa fa-map-marker"></i>&nbsp;<?php echo $arrCartSelectedInclude[$CartItem->EITC_item_id]->venue_title; ?><br/><i class="fa fa-calendar"></i>&nbsp;<?php echo date("d M, Y", strtotime($arrCartSelectedInclude[$CartItem->EITC_item_id]->venue_start_date)); ?></small></p>
                                                        </p>
                                                    </td>
                                                    <td><?php echo $config['CURRENCY_SIGN']; ?> <?php echo number_format($CartItem->EITC_unit_price, 2); ?></td>
                                                    <td>
                                                        <div class="product-qty">
                                                            <span class="qty-block">
                                                                <button type="button" class="btn btn-default btn-sm pull-left" onclick="javascript:qntyDecrease(<?php echo $CartItem->EITC_id; ?>);"><i class="fa fa-minus"></i></button>
                                                                <input data-limit="<?php echo $arrCartSelectedInclude[$CartItem->EITC_item_id]->EI_limit; ?>" id="txtItemQuantity_<?php echo $CartItem->EITC_id; ?>" style="height: 28px; width: 28px; border:1px #79b92d solid ;" class="pull-left text-center" type="text" value="<?php echo $CartItem->EITC_quantity; ?>">
                                                                <button type="button" class="btn btn-default btn-sm pull-left" onclick="javascript:qntyIncrease(<?php echo $CartItem->EITC_id; ?>);"><i class="fa fa-plus"></i></button>
                                                            </span>
                                                        </div>

                                                    </td>
                                                <?php elseif ($CartItem->EITC_item_type == "seat"): ?>
                                                    <td><a href="<?php echo baseUrl(); ?>details/<?php echo $arrCartSelectedSeat[$CartItem->EITC_item_id]->event_id; ?>/<?php echo clean($arrCartSelectedSeat[$CartItem->EITC_item_id]->event_title); ?>" class="cart-thumb"><img alt="<?php echo $arrCartSelectedSeat[$CartItem->EITC_item_id]->event_title; ?>" src="<?php echo $config['IMAGE_UPLOAD_URL']; ?>/event_web_logo/<?php echo $arrCartSelectedSeat[$CartItem->EITC_item_id]->event_web_logo; ?>"></a></td>
                                                    <td>
                                                        <p class="h5 top">
                                                        <h4><?php echo ucfirst($CartItem->EITC_item_type); ?>: &nbsp;<?php echo $arrCartSelectedSeat[$CartItem->EITC_item_id]->SPC_title; ?></h4>
                                                        <em><a target="_blank" href="<?php echo baseUrl(); ?>details/<?php echo $arrCartSelectedSeat[$CartItem->EITC_item_id]->event_id; ?>/<?php echo clean($arrCartSelectedSeat[$CartItem->EITC_item_id]->event_title); ?>"><?php echo $arrCartSelectedSeat[$CartItem->EITC_item_id]->event_title; ?></a></em>
                                                        <p><small><i class="fa fa-map-marker"></i>&nbsp;<?php echo $arrCartSelectedSeat[$CartItem->EITC_item_id]->venue_title; ?><br/><i class="fa fa-calendar"></i>&nbsp;<?php echo date("d M, Y", strtotime($arrCartSelectedSeat[$CartItem->EITC_item_id]->venue_start_date)); ?></small></p>
                                                        </p>
                                                    </td>
                                                    <td><?php echo $config['CURRENCY_SIGN']; ?> <?php echo number_format($CartItem->EITC_unit_price, 2); ?></td>
                                                    <td>
                                                        <div class="product-qty">
                                                            <span class="qty-block">
                                                                <!--<button type="button" class="btn btn-default btn-sm pull-left" onclick="javascript:qntyDecrease(<?php echo $CartItem->EITC_id; ?>);"><i class="fa fa-minus"></i></button>-->
                                                                <input disabled="disabled" style="height: 28px; width: 28px; border:1px #79b92d solid ;" class="pull-left text-center" type="text" value="<?php echo $CartItem->EITC_quantity; ?>">
                                                                <!--<button type="button" class="btn btn-default btn-sm pull-left" onclick="javascript:qntyIncrease(<?php echo $CartItem->EITC_id; ?>);"><i class="fa fa-plus"></i></button>-->
                                                            </span>
                                                        </div>

                                                    </td>
                                                <?php endif; ?>    

                                                <td style="width: 10%"><?php echo $config['CURRENCY_SIGN']; ?> <span class="itemTotalPrice_<?php echo $CartItem->EITC_id; ?>"><?php echo number_format($CartItem->EITC_total_price, 2); ?></span></td>
                                                <td style="width: 10%"><a href="javascript:void(0);" onclick="javascript:deleteItemCart(<?php echo $CartItem->EITC_id; ?>,<?php echo $CartItem->EITC_ETC_id; ?>);" class="c-delete"><span class="icon-cancel-circled fa-2x"></span></a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center"><h4>No item added to cart yet.</h4></td>
                                        </tr>
                                    <?php endif; ?>

                                </tbody>
                            </table>
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
                                            <td>Total Price</td>
                                            <td><?php echo $config['CURRENCY_SIGN']; ?> <span class="cart-Total"><?php echo number_format(($totalCartAmount + $totalDiscount), 2); ?></span></td>
                                        </tr>

                                        <?php if ($totalDiscount != 0): ?>
                                            <tr style="color: #900;">
                                                <td>Discount</td>
                                                <td><?php echo $config['CURRENCY_SIGN']; ?> <span class="total-Discount"><?php echo number_format($totalDiscount, 2); ?></span></td>
                                            </tr>
                                        <?php endif; ?>

                                        <tr class="cartTotal" style="font-weight: bold; font-size: medium;">
                                            <td>Subtotal</td>
                                            <td><?php echo $config['CURRENCY_SIGN']; ?> <span class="sub-Total"><?php echo number_format($totalCartAmount, 2); ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="cart-summry-btm">
                                    <!--                                    <div class="input-group">
                                                                            <input type="text" placeholder="Copne Code" class="form-control">
                                                                            <div class="input-group-btn">
                                                                                <button class="btn btn-default " type="button">Apply </button>
                                                                            </div>
                                                                             /btn-group 
                                                                        </div>-->
                                    <h3><a onclick="javascript:goCheckout();" data-amount="<?php echo $totalCartAmount; ?>" class="btn btn-default btn-primary btn-lg btn-block chk-out-btn" href="javascript:void(0);">Checkout <i class="fa fa-angle-right"></i></a></h3>
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