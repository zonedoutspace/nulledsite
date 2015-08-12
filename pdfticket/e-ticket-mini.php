<?php
include '../config/config.php';
//error_reporting(0);
if (isset($_GET['id']) && isset($_GET['type']) && isset($_GET['OS_id'])) {
    $orderID = validateInput($_GET['id']);
    $type = validateInput($_GET['type']);
    $OS_id = validateInput($_GET['OS_id']);
} else {
    echo "Wrong parameter";
}

$userID = 0;
$user_email = '';
//echo $orderID;
$orderDetails = array();
$sqlOrderDetails = "SELECT order_items.*,order_events.*,events.event_title,events.event_description,events.event_terms_conditions,"
        . "events.event_web_logo,categories.category_title,event_venues.venue_start_time,event_venues.venue_end_time,"
        . "event_venues.venue_id,event_venues.venue_title,event_venues.venue_event_id,"
        . "event_venues.venue_start_date,orders.order_payment_type,orders.order_billing_first_name,"
        . "orders.order_billing_last_name,orders.order_billing_phone,orders.order_billing_address,"
        . "users.user_first_name,users.user_last_name,users.user_email "
        . " FROM order_items "
        . " LEFT JOIN order_events ON order_events.OE_id = order_items.OI_OE_id "
        . " LEFT JOIN events ON events .event_id = order_events.OE_event_id "
        . " LEFT JOIN categories ON categories .category_id = events.event_category_id "
        . " LEFT JOIN event_venues ON events.event_id = event_venues .venue_event_id "
        . " LEFT JOIN orders ON orders.order_id = order_items.OI_order_id "
        . " LEFT JOIN users ON order_events.OE_user_id = users.user_id"
        . " WHERE order_items.OI_id =$orderID";
$resultOrderDetails = mysqli_query($con, $sqlOrderDetails);
if ($resultOrderDetails) {
    $resultOrderDetailsObj = mysqli_fetch_object($resultOrderDetails);
    $orderDetails[] = $resultOrderDetailsObj;
    $type = $resultOrderDetailsObj->OI_item_type;
} else {
    if (DEBUG) {
        $err = "resultOrderDetails error: " . mysqli_error($con);
    } else {
        $err = "resultOrderDetails query failed";
    }
}

//$arr = array();
if ($type == 'seat') {
    $sqlTicketSeat = "SELECT order_seats.OS_seat_number,order_seats.OS_unique_id,"
            . "order_seats.OS_coordinate_id,seat_place_coordinate.SPC_title "
            . " FROM order_seats "
            . " LEFT JOIN seat_place_coordinate ON order_seats.OS_coordinate_id =seat_place_coordinate.SPC_id "
            . " WHERE OS_id=$OS_id";
    $resultTicketSeat = mysqli_query($con, $sqlTicketSeat);
    if ($resultTicketSeat) {
        $resultTicketSeatObj = mysqli_fetch_object($resultTicketSeat);
//        $arr[] = $resultTicketSeatObj;
//        echo $seatNumber = $resultTicketSeatObj->OS_seat_number;
    } else {
        if (DEBUG) {
            $err = "resultTicketSeat error: " . mysqli_error($con);
        } else {
            $err = "resultTicketSeat query failed";
        }
    }
}
//debug($arr);
?>
<!DOCTYPE html>

<html>
    <head>
        <script type="text/javascript" src="jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="jquery-barcode.js"></script>
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <div class="top_text">
            Please print and bring this ticket with you.
        </div>
        <br />
        <table class="main">
            <tr>
                <td width="139" style="height:50px;" bgcolor="#FFFFFF"><h3>EVENT</h3></td>
                <td colspan="5" bgcolor="#FFFFFF"><h4><strong><?php echo $resultOrderDetailsObj->event_title; ?></strong></h4></td>
                <td colspan="4" rowspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
                    <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $resultOrderDetailsObj->event_web_logo; ?>" class="event_logo" alt=""/>
                </td>
            </tr>
            <tr>
                <td style="height:50px;"><h4>VENUE</h4></td>
                <td colspan="5" bgcolor="#FFFFFF"><h4><?php echo $resultOrderDetailsObj->venue_title; ?></h4></td>
            </tr>
            <tr>
                <td bgcolor="#FFFFFF"><h4>DATE</h4></td>
                <td colspan="4" bgcolor="#FFFFFF"><?php echo date("l, d F, Y", strtotime($resultOrderDetailsObj->venue_start_date)); ?></td>
                <td  bgcolor="#FFFFFF"><h4>TIME</h4></td>
                <td colspan="4" bgcolor="#FFFFFF">
                    <h4>
                        Start Time : <?php echo date("h:i A", strtotime($resultOrderDetailsObj->venue_start_time)); ?> &nbsp;&nbsp;
                        <?php if ($resultOrderDetailsObj->venue_end_time): ?>
                            End Time: <?php echo date("h:i A", strtotime($resultOrderDetailsObj->venue_end_time)); ?>
                        <?php endif; ?>
                    </h4>
                </td>
            </tr>
            <tr>
                <td bgcolor="#FFFFFF"><h4>DELIVERY METHOD:</h4></td>
                <td colspan="4" bgcolor="#FFFFFF">
                    <h4>
                        <?php if ($resultOrderDetailsObj->order_payment_type == "COD"): ?>
                            <h4>Cash On Delivery</h4>
                        <?php elseif ($resultOrderDetailsObj->order_payment_type == "Card"): ?>
                            <h4>Online Payment</h4>
                        <?php endif; ?>
                    </h4>

                </td>
                <td bgcolor="#FFFFFF"><h4>CATAGORY</h4></td>
                <td colspan="4" bgcolor="#FFFFFF"><h4><?php echo $resultOrderDetailsObj->category_title; ?></h4></td>
            </tr>
            <tr>
                <td colspan="5" valign="top" bgcolor="#FFFFFF" style="font-size: 11px;">
                    <p style="text-align: center; font-size: 16px; font-weight: bold; margin-top: 4px;">CUSTOMER INFO</p>
                    <p> <strong>Name:</strong><?php echo $resultOrderDetailsObj->user_first_name; ?>&nbsp; <?php echo $resultOrderDetailsObj->user_last_name; ?></p>
                    <p> <strong>Address:</strong><?php echo $resultOrderDetailsObj->order_billing_address; ?></p>
                    <p> <strong>Email:</strong><?php echo $resultOrderDetailsObj->user_email; ?></p>
                    <p> <strong>Phone Number:</strong><?php echo $resultOrderDetailsObj->order_billing_phone; ?></p>
                </td>
                <td colspan="5" bgcolor="#FFFFFF" valign="top">
                    <table style="width: 100%; margin-left: -8px; font-size: 12px;">
                        <tr>
                            <td colspan="2" style="text-align: center; font-size: 16px; font-weight: bold; border-style: none;">TICKET DETAILS (BDT)</td>
                        </tr>
                        <tr>
                            <td style="border-style: none;">Ticket Qty:</td>
                            <td style="border-style: none; text-align: right"><?php echo $resultOrderDetailsObj->OI_quantity; ?></td>
                        </tr>
                        <tr>
                            <td style="border-style: none;">Ticket Price:</td>
                            <td style="border-style: none; text-align: right"><?php echo $resultOrderDetailsObj->OI_unit_price; ?></td>
                        </tr>
                        <?php if ($type == "seat"): ?>
                            <tr>
                                <td style="border-style: none;">Seat Number:</td>
                                <td style="border-style: none; text-align: right"><?php echo $resultTicketSeatObj->OS_seat_number; ?></td>
                            </tr>
                            <tr>
                                <td style="border-style: none;">Seat Type:</td>
                                <td style="border-style: none; text-align: right"><?php echo $resultTicketSeatObj->SPC_title; ?></td>
                            </tr>
                        <?php endif; ?>

                    </table>
                </td>
            </tr>
            <?php if ($resultOrderDetailsObj->event_description != ""): ?>
                <tr>
                    <td colspan="10" align="center" bgcolor="#FFFFFF" style="border-bottom-color: white;">EVENT DETAILS</td>
                </tr>
                <tr>
                    <td colspan="10" bgcolor="#FFFFFF" style=" text-align: justify;">
                        <?php echo html_entity_decode(html_entity_decode($resultOrderDetailsObj->event_description)); ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($resultOrderDetailsObj->OI_item_type == "ticket"): ?>
                <tr>
                    <td colspan="10" align="center" bgcolor="#FFFFFF" style="padding-top: 15px;" >
                        <div class="barcodecell"><barcode code="<?php echo $resultOrderDetailsObj->OI_unique_id; ?>" type="C39" height="1.5" text="1"/></div>
                        <div style="text-align:center; font-size: 10px;"><?php echo $resultOrderDetailsObj->OI_unique_id; ?>
                        </div> 
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($type == 'seat'): ?>
                <tr>
                    <td colspan="10" align="center" bgcolor="#FFFFFF" style="padding-top: 15px;" >
                        <div class="barcodecell"><barcode code="<?php echo $resultTicketSeatObj->OS_unique_id; ?>" type="C39" height="1.5" text="1"/></div>
                        <div style="text-align:center; font-size: 10px;"><?php echo $resultTicketSeatObj->OS_unique_id; ?>
                        </div> 
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($resultOrderDetailsObj->event_terms_conditions != ""): ?>
                <tr>
                    <td colspan="10" align="center" bgcolor="#FFFFFF" style="border-bottom-color: white;">TERMS &amp; CONDITIONS</td>
                </tr>
                <tr>
                    <td colspan="10" bgcolor="#FFFFFF" style="text-align: justify; font-size: 11px;">
                        <?php echo html_entity_decode(html_entity_decode($resultOrderDetailsObj->event_terms_conditions)); ?>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="10" align="center" bgcolor="#FFFFFF" style="border-bottom-style: none;">TICKETING PARTNER</td>
            </tr>
            <tr>
                <td style="border-top-style: none;" colspan="10" bgcolor="#FFFFFF">
                    <table style="width: 100%; margin-left: -8px;">
                        <tr>
                            <td style="border-style: none; width: 46%"> <h5>Hot Line Number:  +8801971842538,+8804478009569 </h5></td>
                            <td style="border-style: none;"><img src="<?php echo baseUrl(); ?>images/ticketchai_logo.png" width="100" height="50" alt=""/></td>
                            <td style="border-style: none; text-align: right"></td>
                        </tr>              
                    </table>
                </td>
            </tr>
        </table> 
    </body>
</html>
