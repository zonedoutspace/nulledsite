<?php
include './config/config.php';
include './lib/mpdf/mpdf.php';
if (isset($_GET['id'])) {
    $orderID = $_GET['id'];
}
$userID = 0;
$user_email = '';
//echo $orderID;


if (isset($_POST['btnDownload'])) {
    extract($_POST);
    $dateTimeNow = date("d-m-y H:i:s");
    $mpdf = new mPDF('c', 'A4', '', '', 15, 15, 15, 15, 16, 13);
    $mpdf->SetDisplayMode('fullpage');
    $stylesheet = file_get_contents(baseUrl() . "pdfticket/style.css");
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    $url = baseUrl() . "pdfticket/e-ticket-mini.php?id=" . $OI_id;
    $html = file_get_contents($url);
//    debug($html);
    $mpdf->WriteHTML($html, 2);
    $mpdf->Output('e-ticket-' . $dateTimeNow . '.pdf', 'D');
    exit();
}


if (!checkUserLogin()) {
    $link = baseUrl('index.php');
    redirect($link);
} else {
    $userID = getSession('user_id');
    $user_email = getSession('user_email');
}

$orderDetails = array();
$strSeatIds = "";
//$sqlOrderDetails = "SELECT order_events.*, order_items.*, events .*,categories.category_title,event_venues.*,orders.order_status,"
//        . " CASE OI_item_type WHEN 'ticket' THEN ' Ticket'"
//        . " ELSE CASE OI_item_type WHEN 'include' THEN 'Include' "
//        . " ELSE CASE OI_item_type WHEN 'seat' THEN 'Seat' "
//        . " ELSE 'Others' END END END AS item_type "
//        . " FROM order_items "
//        . " LEFT JOIN order_events ON order_events.OE_id = order_items.OI_OE_id "
//        . " LEFT JOIN events ON events.event_id = order_events.OE_event_id "
//        . " LEFT JOIN categories ON categories.category_id = events.event_category_id "
//        . " LEFT JOIN orders ON orders.order_id = order_items.OI_order_id "
//        . " LEFT JOIN event_venues ON events.event_id = event_venues .venue_event_id "
//        . " WHERE order_events.OE_order_id =$orderID";


$sqlOrderDetails = "SELECT order_events.OE_id,order_events.OE_order_id,order_events.OE_event_id,order_events.OE_session_id,"
        . "order_events.OE_user_id,order_items.OI_id,order_items.OI_unique_id,order_items.OI_OE_id,order_items.OI_order_id,order_items.OI_session_id,"
        . "order_items.OI_item_type,order_items.OI_venue_id,order_items.OI_item_id,order_items.OI_quantity,"
        . "order_items.OI_unit_price,order_items.OI_unit_discount,events.event_id,events.event_category_id,"
        . "events.event_title,events.event_web_logo,categories.category_id,categories.category_title,"
        . "event_venues.venue_id,event_venues.venue_title,event_venues.venue_event_id,orders.order_status,"
        . " CASE OI_item_type WHEN 'ticket' THEN ' Ticket'"
        . " ELSE CASE OI_item_type WHEN 'include' THEN 'Include' "
        . " ELSE CASE OI_item_type WHEN 'seat' THEN 'Seat' "
        . " ELSE 'Others' END END END AS item_type "
        . " FROM order_items "
        . " LEFT JOIN order_events ON order_events.OE_id = order_items.OI_OE_id "
        . " LEFT JOIN events ON events.event_id = order_events.OE_event_id "
        . " LEFT JOIN categories ON categories.category_id = events.event_category_id "
        . " LEFT JOIN orders ON orders.order_id = order_items.OI_order_id "
        . " LEFT JOIN event_venues ON events.event_id = event_venues .venue_event_id "
        . " WHERE order_events.OE_order_id =$orderID";



$resultOrderDetails = mysqli_query($con, $sqlOrderDetails);
//echo $countRow = mysqli_num_rows($resultOrderDetails);
if ($resultOrderDetails) {
    while ($resultOrderDetailsObj = mysqli_fetch_object($resultOrderDetails)) {
        $orderDetails[] = $resultOrderDetailsObj;
        if ($resultOrderDetailsObj->item_type == "Seat") {
            $strSeatIds .= $resultOrderDetailsObj->OI_id . ",";
        }
    }
} else {
    if (DEBUG) {
        $err = "resultOrderDetails error: " . mysqli_error($con);
    } else {
        $err = "resultOrderDetails query failed";
    }
}

$arrSeats = array();
$strSeatIds = rtrim($strSeatIds, ",");
if (!empty($strSeatIds)) {
    $strSeatIds = rtrim($strSeatIds, ",");
    $sqlSelectSeats = "SELECT order_items.*,events.event_title,events.event_web_logo,event_venues.venue_id,"
            . "event_venues.venue_event_id,event_venues.venue_title,"
            . "categories.category_title "
            . "FROM order_items "
            . "LEFT JOIN order_seats ON order_seats.OS_OI_id = order_items.OI_id "
            . "LEFT JOIN events ON events.event_id = order_seats.OS_event_id "
            . " LEFT JOIN categories ON categories.category_id = events.event_category_id "
            . " LEFT JOIN orders ON orders.order_id = order_seats.OS_order_id "
            . " LEFT JOIN event_venues ON events.event_id = event_venues.venue_event_id "
            . " LEFT JOIN seat_place_coordinate ON seat_place_coordinate.SPC_id = order_seats.OS_place_id "
            . "WHERE order_items.OI_id in ($strSeatIds)";

//
//    $sqlSelectSeats = "SELECT order_seats.OS_id,order_seats.OS_OI_id,order_seats.OS_order_id,order_seats.OS_session_id,"
//            . "order_seats.OS_unique_id,order_seats.OS_event_id,order_seats.OS_venue_id,order_seats.OS_place_id,"
//            . "order_seats.OS_coordinate_id,order_seats.OS_seat_number,events.event_id,events.event_title,events.event_category_id,"
//            . "events.event_web_logo,categories.category_id,categories.category_title,orders.order_id,orders.order_user_id,"
//            . "orders.order_number,orders.order_status,event_venues.venue_id,event_venues.venue_event_id,event_venues.venue_title,"
//            . "seat_place_coordinate.SPC_id,seat_place_coordinate.SPC_SP_id,seat_place_coordinate.SPC_title,"
//            . "seat_place_coordinate.SPC_shape_type "
//            . "FROM order_items "
//            . "LEFT JOIN order_seats ON order_seats.OS_OI_id = order_items.OI_id "
//            . "LEFT JOIN events ON events.event_id = order_seats.OS_event_id "
//            . " LEFT JOIN categories ON categories.category_id = events.event_category_id "
//            . " LEFT JOIN orders ON orders.order_id = order_seats.OS_order_id "
//            . " LEFT JOIN event_venues ON events.event_id = event_venues.venue_event_id "
//            . " LEFT JOIN seat_place_coordinate ON seat_place_coordinate.SPC_id = order_seats.OS_place_id "
//            . "WHERE order_items.OI_id in ($strSeatIds)";

    $resultSelectSeats = mysqli_query($con, $sqlSelectSeats);
//    echo $countRow = mysqli_num_rows($resultSelectSeats);
    if ($resultSelectSeats) {
        while ($resultSelectSeatsObj = mysqli_fetch_object($resultSelectSeats)) {
            $arrSeats[] = $resultSelectSeatsObj;
        }
    } else {
        if (DEBUG) {
            $err = "resultSelectSeats error: " . mysqli_error($con);
        } else {
            $err = "resultSelectSeats query failed";
        }
    }
}
//echo $strSeatIds = rtrim($strSeatIds, ",");
//echo $strSeatIds;
//debug($arrSeats);
//debug($orderDetails);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include basePath('header_script.php'); ?>
        <!-- From customer order list page -->
        <link rel="stylesheet" type="text/css" href="<?php echo baseUrl('css/footable-0.1.css'); ?>">
        <link  rel="stylesheet" type="text/css" href="<?php echo baseUrl('css/footable.sortable-0.1.css'); ?>">
    </head>
    <body>
        <div id="wrapper">
            <header>
                <div class="header-wrapper">
                    <?php include basePath('menu_top.php'); ?>
                    <?php include basePath('navigation.php'); ?>
                </div>
            </header>
            <!-- /.header -->
            <div class="main-container">
                <div class="container">
                    <ul class="nav nav-pills nav-justified  nav-tab-bar">
                        <li><a href="<?php echo baseUrl(); ?>account"><i class="fa fa-dashboard"></i> User Settings</a> </li>
                        <li><a href="<?php echo baseUrl(); ?>address"><i class="fa fa-map-marker"></i> Default Address</a> </li>
                        <li ><a href="<?php echo baseUrl(); ?>mywishlist"><i class="fa fa-heart"></i> My Wishlist</a> </li>
                        <li class="active"><a href="<?php echo baseUrl(); ?>myorderlist"><i class="icon-doc-text"></i>Order History</a> </li>
                    </ul>
                    <div class="inner-box">
                        <h2 class="title-2"><i class="icon-doc-text"></i> Order Details </h2>
                        <div class="table-order">
                            <div class="col-xs-12 col-sm-12">
                                <div class="table-inner-o">
                                    <table class="footable">
                                        <thead>
                                            <tr>
                                                <th>Event Logo</th>
                                                <th data-class="expand" data-sort-initial="true"> <span title="table sorted by this column on load">Event Title</span> </th>
                                                <th data-hide="phone" data-sort-ignore="true">Category Title</th>
                                                <th data-hide="phone" data-sort-ignore="true">Unit Price</th>
                                                <th data-hide="phone,tablet"><strong>Total Quantity</strong></th>
                                                <th data-hide="phone,tablet"><strong>Item Type</strong></th>
                                                <th> Action </th>
                                                <th data-hide="default" data-type="numeric"></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($orderDetails) >= 1) : ?>
                                                <?php foreach ($orderDetails AS $Details) : ?>
                                                    <?php if ($Details->item_type != "Seat"): ?>
                                                        <tr>
                                                            <td><img style="width: 50px;" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $Details->event_web_logo; ?>" alt="img"></td>
                                                            <td><?php echo $Details->event_title; ?></td>
                                                            <td><?php echo $Details->category_title; ?></td>
                                                            <td><?php echo $Details->OI_unit_price; ?></td>
                                                            <td><?php echo $Details->OI_quantity; ?></td>
                                                            <td><?php echo $Details->item_type; ?></td>
                                                            <td style="color: navy;">View Ticket</td>
                                                            <!-- Event Ticket -->
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <h4 class="text-center">
                                                                        <?php if ($Details->order_status !== 'booking'): ?>
                                                                            <strong>PLEASE PRINT AND BRING THIS TICKET WITH YOU AT THE EVENT</strong>
                                                                        <?php else: ?>
                                                                            <strong>PLEASE COMPLETE PAYMENT PROCESS IN ORDER TO DOWNLOAD E-TICKET</strong>
                                                                        <?php endif; ?>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <form method="post">
                                                                            <input type="hidden" name="OI_id" value="<?php echo $Details->OI_id; ?>" />
                                                                            <?php if ($Details->order_status !== 'booking'): ?>
                                                                                <button type="submit" name="btnDownload" class="btn btn-success btn-lg">Download e-Ticket</button>
                                                                            <?php endif; ?>
                                                                        </form>
                                                                    </h4>
                                                                </div>
                                                                <div class="col-md-12" style="border-style: solid;border-width: 5px;border-color: darkgray;">
                                                                    <div class="col-md-3" style="margin-top: 10px;">
                                                                        <img class="thumbnail  img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $Details->event_web_logo; ?>" alt="Event Logo">
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <div class="col-md-12">
                                                                            <h3 class="text-center" style="margin-top: 10px;"><strong><?php echo $Details->event_title; ?></strong></h3>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                        <div class="col-md-12">
                                                                            <div class="col-md-9">

                                                                                <script type="text/javascript">
                                                                                    $(document).ready(function () {
                                                                                        $("#ticketBarcode_<?php echo $Details->OI_id; ?>").barcode(
                                                                                                "<?php echo $Details->OI_unique_id; ?>", // Value barcode (dependent on the type of barcode)
                                                                                                "code39" // type (string)
                                                                                                );
                                                                                    });

                                                                                </script>

                                                                                <table>
                                                                                    <tr>
                                                                                        <td><h5>CATEGORY</h5></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><h5><strong><?php echo $Details->category_title; ?></strong></h5></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><h5>EVENT DATE</h5></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><h5><strong><?php echo date("l, d F, Y", strtotime($Details->venue_start_date)); ?></strong></h5></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><h5>LOCATION</h5></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><h5><strong><?php echo $Details->venue_title; ?></strong></h5></td>
                                                                                    </tr>

                                                                                </table>
                                                                                <br/>
                                                                                <div id="ticketBarcode_<?php echo $Details->OI_id; ?>"></div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <img class="img-responsive" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $Details->OI_unique_id; ?>&choe=UTF-8" title="Link to Google.com" />
                                                                                <div class="clearfix"></div>
                                                                                <br/><br/>
                                                                                <h5><strong>Ticketing Partner</strong></h5>
                                                                                <img style="width: 60%" class="img-responsive" src="<?php echo baseUrl(); ?>images/ticketchai_logo.png">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="clearfix"></div>


                                                            </td>
                                                            <!-- Event Ticket -->
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <?php if (count($arrSeats) >= 1) : ?>
                                                <?php foreach ($arrSeats AS $Seat) : ?>
                                                    <tr>
                                                        <td><img style="width: 50px;" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $Seat->event_web_logo; ?>" alt="img"></td>
                                                        <td><?php echo $Seat->event_title; ?></td>
                                                        <td><?php echo $Seat->category_title; ?></td>
                                                        <td><?php echo $Seat->OI_unit_price; ?></td>
                                                        <td><?php echo $Seat->OI_quantity; ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $Seat->OI_item_type; ?></td>
                                                        <td style="color: navy;">View Ticket</td>
                                                        <!-- Event Ticket -->
                                                        <td>
                                                            <div class="col-md-12">
                                                                <h4 class="text-center">
                                                                    <?php if ($Seat->order_status !== 'booking'): ?>
                                                                        <strong>PLEASE PRINT AND BRING THIS TICKET WITH YOU AT THE EVENT</strong>
                                                                    <?php else: ?>
                                                                        <strong>PLEASE COMPLETE PAYMENT PROCESS IN ORDER TO DOWNLOAD E-TICKET</strong>
                                                                    <?php endif; ?>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <form method="post">
                                                                        <input type="hidden" name="OI_id" value="<?php echo $Seat->OI_id; ?>" />
                                                                        <?php if ($Seat->order_status !== 'booking'): ?>
                                                                            <button type="submit" name="btnDownload" class="btn btn-success btn-lg">Download e-Ticket</button>
                                                                        <?php endif; ?>
                                                                    </form>
                                                                </h4>
                                                            </div>
                                                            <div class="col-md-12" style="border-style: solid;border-width: 5px;border-color: darkgray;">
                                                                <div class="col-md-3" style="margin-top: 10px;">
                                                                    <img class="thumbnail  img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $Seat->event_web_logo; ?>" alt="Event Logo">
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="col-md-12">
                                                                        <h3 class="text-center" style="margin-top: 10px;"><strong><?php echo $Seat->event_title; ?></strong></h3>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <div class="col-md-12">
                                                                        <div class="col-md-9">

                                                                            <script type="text/javascript">
                                                                                $(document).ready(function () {
                                                                                    $("#ticketBarcode_<?php echo $Seat->OS_id; ?>").barcode(
                                                                                            "<?php echo $Seat->OS_unique_id; ?>", // Value barcode (dependent on the type of barcode)
                                                                                            "code39" // type (string)
                                                                                            );
                                                                                });

                                                                            </script>

                                                                            <table>
                                                                                <tr>
                                                                                    <td><h5>CATEGORY</h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5><strong><?php echo $Seat->category_title; ?></strong></h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5>EVENT DATE</h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5><strong><?php echo date("l, d F, Y", strtotime($Seat->venue_start_date)); ?></strong></h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5>LOCATION</h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5><strong><?php echo $Seat->venue_title; ?></strong></h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5>SEAT PLACE</h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5><strong><?php echo $Seat->SPC_title; ?></strong></h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5>SEAT NUMBER</h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><h5><strong><?php echo $Seat->OS_seat_number; ?></strong></h5></td>
                                                                                </tr>

                                                                            </table>
                                                                            <br/>
                                                                            <div id="ticketBarcode_<?php echo $Seat->OS_id; ?>"></div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <img class="img-responsive" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $Seat->OS_unique_id; ?>&choe=UTF-8" title="QRCode" />
                                                                            <div class="clearfix"></div>
                                                                            <br/><br/>
                                                                            <h5><strong>Ticketing Partner</strong></h5>
                                                                            <img style="width: 60%" class="img-responsive" src="<?php echo baseUrl(); ?>images/ticketchai_logo.png">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="clearfix"></div>


                                                        </td>
                                                        <!-- Event Ticket -->
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!--/.row-box End--> 
                        <div style="clear:both"></div>
                    </div>
                </div>
            </div>
            <?php include basePath('social_link.php'); ?>
            <?php include basePath('footer.php'); ?>
        </div>
        <?php include basePath('footer_script.php'); ?>
        <!-- From Customer Dashboard -->
        <script src="<?php echo baseUrl('js/jquery.matchHeight-min.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/hideMaxListItem.js'); ?>"></script>

        <script src="<?php echo baseUrl('js/footable.js?v=2-0-1'); ?>" type="text/javascript"></script> 
        <script src="<?php echo baseUrl('js/footable.filter.js?v=2-0-1'); ?>" type="text/javascript"></script>
        <script type="text/javascript">
                                                                        $(function () {
                                                                            $('#addManageTable').footable().bind('footable_filtering', function (e) {
                                                                                var selected = $('.filter-status').find(':selected').text();
                                                                                if (selected && selected.length > 0) {
                                                                                    e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                                                                                    e.clear = !e.filter;
                                                                                }
                                                                            });

                                                                            $('.clear-filter').click(function (e) {
                                                                                e.preventDefault();
                                                                                $('.filter-status').val('');
                                                                                $('table.demo').trigger('footable_clear_filter');
                                                                            });

                                                                        });
        </script> 
        <script>
            function checkAll(bx) {
                var chkinput = document.getElementsByTagName('input');
                for (var i = 0; i < chkinput.length; i++) {
                    if (chkinput[i].type == 'checkbox') {
                        chkinput[i].checked = bx.checked;
                    }
                }
            }
        </script> 
        <script src="<?php echo baseUrl('js/plugins/jquery.fs.scroller/jquery.fs.scroller.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/plugins/jquery.fs.selecter/jquery.fs.selecter.js'); ?>"></script>

        <!-- From customer order list page -->

        <script type="text/javascript" src="<?php echo baseUrl('js/footable.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo baseUrl('js/footable.sortable.js'); ?>"></script>
        <script type="text/javascript">
            $(function () {
                $('.footable').footable();
            });
        </script> 

        <!-- For Barcode Generate -->
        <script type="text/javascript" src="<?php echo baseUrl('js/barcode/jquery-barcode.js'); ?>"></script>
        <!-- For Barcode Generate -->
    </body>
</html>