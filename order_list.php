<?php
include './config/config.php';

$userID = 0;
$user_email = '';
//echo $orderID;

if (!checkUserLogin()) {
    redirect('index.php');
} else {
    $userID = getSession('user_id');
    $user_email = getSession('user_email');
}

// Get all order according to user id
$orderListArray = array();
$sqlOrderListRow = "SELECT CONCAT(order_billing_first_name, ' ', order_billing_last_name) as billingName,"
        . " CONCAT(order_shipping_first_name, ' ', order_shipping_last_name) as shippingName,"
        . " CASE order_payment_type WHEN 'Card' THEN' Online Payment'"
        . " ELSE CASE order_payment_type WHEN 'COD' THEN 'Cash On Delivery' "
        . " ELSE 'Pick From Office' END END AS payment_method,"
        . " orders.*,(SELECT COUNT(OE_event_id) FROM order_events WHERE"
        . " orders.order_id = order_events.OE_order_id) AS totalEvent FROM orders"
        . " WHERE orders.order_user_id=$userID ORDER BY order_id DESC";
$resultOrderListRow = mysqli_query($con, $sqlOrderListRow);

if ($resultOrderListRow) {
    while ($resultOrderListObj = mysqli_fetch_object($resultOrderListRow)) {
        $orderListArray[] = $resultOrderListObj;
    }
} else {
    if (DEBUG) {
        $err = "resultOrderListRow error: " . mysqli_error($con);
    } else {
        $err = "resultOrderListRow query failed";
    }
}
//debug($orderListArray);
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
                        <li class="active"><a href="<?php echo baseUrl(); ?>myorderlist"><i class="icon-doc-text"></i> Order History</a> </li>
                    </ul>
                    <div class="inner-box">
                        <h2 class="title-2"><i class="icon-doc-text"></i> Order List </h2>
                        <div class="table-order">
                            <div class="col-xs-12 col-sm-12">
                                <div class="table-inner-o">
                                    <table class="footable">
                                        <thead>
                                            <tr>
                                                <th data-class="expand" data-sort-initial="true"> <span title="table sorted by this column on load">Order ID</span> </th>
                                                <th data-hide="phone,tablet" data-sort-ignore="true">No. Of Events</th>
                                                <th data-hide="phone,tablet" data-sort-ignore="true">Total Amount</th>
                                                <th data-hide="phone,tablet"><strong>Payment Method</strong></th>
                                                <th data-hide="phone" data-type="numeric"> Status </th>
                                                <th data-hide="phone" data-type="numeric"> Action</th>
                                                <th data-hide="default"><strong>Billing Details</strong></th>
                                                <th data-hide="default"><strong>Shipping Details</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($orderListArray) >= 1): ?>
                                                <?php foreach ($orderListArray AS $orderList) : ?>
                                                    <tr>
                                                        <td><?php echo $orderList->order_number; ?></td>
                                                        <td><?php echo $orderList->totalEvent; ?></td>
                                                        <td>
                                                           <strong> <?php echo number_format((($orderList->order_total_amount - $orderList->order_promotion_discount_amount) - $orderList->order_discount_amount) + $orderList->order_shipment_charge , 2); ?></strong>
                                                            <?php if($orderList->order_promotion_discount_amount > 0): ?><br/>Coupon discount:<?php echo $orderList->order_promotion_discount_amount; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $orderList->payment_method; ?></td>
                                                        <td data-value="3">
                                                            <span class="label label-success"><?php echo $orderList->order_status; ?></span>
                                                        </td>
                                                        <td data-value="3">
                                                            <span class="label"><a href="<?php echo baseUrl(); ?>order-details/<?php echo $orderList->order_id; ?>">View Details</a></span>
                                                        </td>
                                                        <td ><address>
                                                                Name: <?php echo $orderList->billingName; ?>
                                                                <br/>
                                                                Phone: <?php echo $orderList->order_billing_phone; ?>
                                                                <br/>
                                                                Address: <?php echo $orderList->order_billing_address; ?>
                                                                <br/>
                                                                Post Code: <?php echo $orderList->order_billing_zip; ?>
                                                                <br/>
                                                                City: <?php echo $orderList->order_billing_city; ?>
                                                                <br/>
                                                                Country: <?php echo $orderList->order_billing_country; ?>
                                                            </address>
                                                        </td>
                                                        <td ><address>
                                                                Name: <?php echo $orderList->shippingName; ?>
                                                                <br/>
                                                                Phone: <?php echo $orderList->order_shipping_phone; ?>
                                                                <br/>
                                                                Address: <?php echo $orderList->order_shipping_address; ?>
                                                                <br/>
                                                                Post Code: <?php echo $orderList->order_shipping_zip; ?>
                                                                <br/>
                                                                City: <?php echo $orderList->order_shipping_city; ?>
                                                                <br/>
                                                                Country: <?php echo $orderList->order_shipping_country; ?>
                                                            </address>
                                                        </td>
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

        </div>
        <?php include basePath('footer_script.php'); ?>
    </body>
</html>
