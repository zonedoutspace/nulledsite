<?php

include '../../../config/config.php';

$adminEventPermission = '';
$adminID = 0;
$adminEventID = '';
if ((getSession('admin_event_permission')) AND ( getSession('admin_id'))) {
    $adminEventPermission = getSession('admin_event_permission');
    $adminID = getSession('admin_id');
    $adminEventID = getSession('admin_event_id');
}

header("Content-type: application/json");
$verb = $_SERVER["REQUEST_METHOD"];
if ($verb == "GET") {
    $arrayEvent = array();
    $get_sql = "SELECT orders.order_id,orders.order_read,orders.order_updated_by,order_billing_phone,"
            . "order_user_id,CONCAT(users.user_first_name,' ',users.user_last_name) as name,"
            . "orders.order_updated_on,orders.order_number,orders.order_created,"
            . "orders.order_status,orders.order_payment_type,"
            . "(((orders.order_total_amount-orders.order_promotion_discount_amount)-orders.order_discount_amount)+orders.order_shipment_charge) as total,admins.admin_full_name "
            . "FROM orders "
            . "LEFT JOIN order_events ON `order_events`.OE_order_id = orders.order_id "
            . "LEFT JOIN users ON orders.order_user_id = users.user_id "
            . "LEFT JOIN admins ON orders.order_updated_by = admins.admin_id "
            . "LEFT JOIN events ON `order_events`.OE_event_id = events.event_id ";
    if ($adminEventPermission == "created") {
        $get_sql .= "WHERE event_created_by=$adminID ";
    } elseif ($adminEventPermission == "selected") {
        if ($adminEventID != "") {
            $get_sql .= "WHERE event_id IN ($adminEventID) ";
        } else {
            $get_sql .= "WHERE event_id IN (0) ";
        }
    }
    $get_sql .= "GROUP BY orders.order_id "
            . "ORDER BY orders.order_read DESC, orders.order_id DESC";
    $resultEventList = mysqli_query($con, $get_sql);
    if ($resultEventList) {
        while ($obj = mysqli_fetch_object($resultEventList)) {
            $arrayEvent[] = $obj;
        }
    } else {
        if (DEBUG) {
            echo $err = "resultEventList error: " . mysqli_error($con);
        } else {
            echo $err = "resultEventList query failed";
        }
    }
    echo "{\"data\":" . json_encode($arrayEvent) . "}";
}