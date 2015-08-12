<?php

include '../config/config.php';
$return_array = array();

$delCost = '';
$delCity = '';
$sessionID = session_id();
extract($_POST);
//debug($_POST);

if ($checkID != "") {
    $checkID = intval($checkID);
    $getDeliveryCostSql = "SELECT cities.city_delivery_charge, cities.city_name FROM cities WHERE cities.city_id= $checkID";
    $getDeliveryCostResult = mysqli_query($con, $getDeliveryCostSql);

    if ($getDeliveryCostResult) {
        $row = mysqli_fetch_object($getDeliveryCostResult);
        if (isset($row->city_delivery_charge)) {
            $delCost = $row->city_delivery_charge;
            $delCity = $row->city_name;

            $sqlGetCartSum = "SELECT SUM(EITC_total_price) AS TotalPrice, SUM(EITC_total_discount) AS TotalDiscount FROM event_item_temp_cart WHERE EITC_session_id='$sessionID'";
            $resultGetCartSum = mysqli_query($con, $sqlGetCartSum);
            if ($resultGetCartSum) {
                $resultGetCartSumObj = mysqli_fetch_object($resultGetCartSum);
                if (isset($resultGetCartSumObj->TotalPrice)) {
                    $return_array = array("output" => "success",
                        "msg" => "Delivery charge " . $config['CURRENCY_SIGN'] . " " . $delCost . " added for " . $delCity . " city.",
                        "itemTotalPrice" => number_format($resultGetCartSumObj->TotalPrice, 2),
                        "subTotal" => number_format(($resultGetCartSumObj->TotalPrice + $delCost), 2),
                        "delCost" => $delCost);
                    echo json_encode($return_array);
                    exit();
                }
            } else {
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultUpdateTmpCartItem error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultUpdateTmpCartItem query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        }
//        $return_array = array("output" => "success", "msg" => "Delivery charge " . $config['CURRENCY_SIGN'] . " " . $delCost . " added for " . $delCity . " city.", "delCost" => $delCost);
//        echo json_encode($return_array);
//        exit();
    } else {
        if (DEBUG) {
            $err = "getDeliveryCostResult error: " . mysqli_error($con);
        } else {
            $err = "getDeliveryCostResult query failed";
        }
    }
}
?>