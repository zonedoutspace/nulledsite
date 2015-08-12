<?php
$sessionID = session_id();
$totalEventCount = 0;
$totalItemCount = 0;
$subTotalPopup = 0;
$arrTmpCartEvent = array();
$sqlGetTmpCartEvent = "SELECT event_id,event_title,event_web_logo,ETC_id FROM event_temp_cart "
        . "LEFT JOIN events ON event_id=ETC_event_id "
        . "WHERE ETC_session_id='$sessionID'";
$resultGetTmpCartEvent = mysqli_query($con, $sqlGetTmpCartEvent);
if ($resultGetTmpCartEvent) {
    while ($resultGetTmpCartEventObj = mysqli_fetch_object($resultGetTmpCartEvent)) {
        $arrTmpCartEvent[] = $resultGetTmpCartEventObj;
        $totalEventCount = mysqli_num_rows($resultGetTmpCartEvent);
    }
} else {
    if (DEBUG) {
        echo "resultGetTmpCartEventObj error: " . mysqli_error($con);
    } else {
        echo "resultGetTmpCartEventObj query failed.";
    }
}

$arrTmpCartItem = array();
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
        $arrTmpCartItem[$resultGetTmpCartItemObj->EITC_ETC_id][] = $resultGetTmpCartItemObj;
        $totalCartAmount += $resultGetTmpCartItemObj->EITC_total_price;
        $totalDiscount += $resultGetTmpCartItemObj->EITC_total_discount;
        $totalItemCount += $resultGetTmpCartItemObj->EITC_quantity;
        $subTotalPopup += $resultGetTmpCartItemObj->EITC_total_price;
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
    $arrSelectedTicket = array();
    $sqlGetSelectedTicket = "SELECT * FROM event_ticket_types WHERE TT_id IN ($strTicketID)";
    $resultGetSelectedTicket = mysqli_query($con, $sqlGetSelectedTicket);
    if ($resultGetSelectedTicket) {
        while ($resultGetSelectedTicketObj = mysqli_fetch_object($resultGetSelectedTicket)) {
            $arrSelectedTicket[$resultGetSelectedTicketObj->TT_id] = $resultGetSelectedTicketObj;
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
    $arrSelectedInclude = array();
    $sqlGetSelectedInclude = "SELECT * FROM event_includes WHERE EI_id IN ($strIncludeID)";
    $resultGetSelectedInclude = mysqli_query($con, $sqlGetSelectedInclude);
    if ($resultGetSelectedInclude) {
        while ($resultGetSelectedIncludeObj = mysqli_fetch_object($resultGetSelectedInclude)) {
            $arrSelectedInclude[$resultGetSelectedIncludeObj->EI_id] = $resultGetSelectedIncludeObj;
        }
    } else {
        if (DEBUG) {
            echo "resultGetSelectedInclude error: " . mysqli_error($con);
        } else {
            echo "resultGetSelectedInclude query failed.";
        }
    }
}

$arrSelectedSeat = array();
if ($strSeatID != "") {

    $sqlGetSelectedSeat = "SELECT * FROM seat_place_coordinate WHERE SPC_id IN ($strSeatID)";
    $resultGetSelectedSeat = mysqli_query($con, $sqlGetSelectedSeat);
    if ($resultGetSelectedSeat) {
        while ($resultGetSelectedSeatObj = mysqli_fetch_object($resultGetSelectedSeat)) {
            $arrSelectedSeat[$resultGetSelectedSeatObj->SPC_id] = $resultGetSelectedSeatObj;
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
//debug($arrSelectedInclude);
?>


<ul class="nav navbar-nav navbar-right has-cartmenu">
    <li class="dropdown"> <a href="<?php echo baseUrl(); ?>merchant-form" class="btn btn-primary btn-sm"><i class="fa fa-check-square-o"></i> &nbsp;&nbsp;List Your Event</a> </li>
    <li class="dropdown"> <a class="dropdown-toggle nav-cart"  data-toggle="dropdown"><i class="icon-grocery-store"></i> Cart <span class="total-price"> <?php echo $config['CURRENCY_SIGN']; ?><span id="cartAmount"><?php echo number_format($totalCartAmount, 2); ?></span> </span></a>
        <ul id="wholeCart" class="dropdown-menu" style="width: 400px !important; max-height: 350px !important; overflow-x: hidden; overflow-y:scroll;">

            <?php if (count($arrTmpCartEvent) > 0): ?>
                <?php foreach ($arrTmpCartEvent AS $Event): ?>
                    <li id="cartItemID_<?php echo $Event->ETC_id; ?>">
                        <div class="basket-item">
                            <div class="row">
                                <div class="col-xs-12 col-sm-2 col-md-4">
                                    <div class="thumb"> 
                                        <?php if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/event_web_logo/' . $Event->event_web_logo) AND $Event->event_web_logo != ''): ?>
                                            <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $Event->event_web_logo; ?>" alt="<?php echo $Event->event_title; ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-10 col-md-8">
                                    <div class="title"><a target="_blank" href="<?php echo baseUrl(); ?>details/<?php echo $Event->event_id; ?>/<?php echo clean($Event->event_title); ?>"><?php echo $Event->event_title; ?></a></div>

                                    <?php if (count($arrTmpCartItem[$Event->ETC_id])): ?>

                                        <div class="col-xs-12">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style="width: 60%">Title</td>
                                                        <td style="width: 20%">Qnt.</td>
                                                        <td style="width: 20%">Price</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($arrTmpCartItem[$Event->ETC_id] AS $Items): ?>
                                                        <tr>
                                                            <?php if ($Items->EITC_item_type == 'ticket'): ?>
                                                                <td><?php echo $arrSelectedTicket[$Items->EITC_item_id]->TT_type_title; ?></td>
                                                            <?php elseif ($Items->EITC_item_type == 'include'): ?>
                                                                <td><?php echo $arrSelectedInclude[$Items->EITC_item_id]->EI_name; ?></td>
                                                            <?php elseif ($Items->EITC_item_type == 'seat'): ?>
                                                                <td><?php echo $arrSelectedSeat[$Items->EITC_item_id]->SPC_title; ?></td>
                                                            <?php endif; ?>
                                                            <td><?php echo $Items->EITC_quantity; ?></td>
                                                            <td><?php echo $config['CURRENCY_SIGN'] . '' . $Items->EITC_total_price; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    <?php else: ?>
                                        <div class="col-xs-12 col-sm-10 col-md-8">
                                            <div class="price">No item added for this event.</div>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                            <!--<a href="javascript:void(0);" onclick="javascript:deleteItemCart();" class="close-btn"><i class="icon-cancel-circled"></i></a> </div>-->
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <h4 style="margin: 15px 0px;" class="text-center">Cart is now empty.</h4>
            <?php endif; ?>
            <li class="checkout">
                <div class="merged-buttons"> 
                    <a class="btn btn-danger" href="<?php echo baseUrl(); ?>cart">show cart</a> 
                    <a href="javascript:void(0);" onclick="javascript:goCheckout();" class="btn btn-info chk-out-btn" data-amount="<?php echo $subTotalPopup; ?>">checkout</a> </div>
            </li>
        </ul>
    </li>
</ul>