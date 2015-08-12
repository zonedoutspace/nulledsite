<?php

include '../config/config.php';

$itemTmpCartID = 0;
$qntyTmpCart = 0;
$sessionID = session_id();
$unitPrice = 0;
$unitDiscount = 0;
$newTotalPrice = 0;
$newDiscountPrice = 0;
extract($_POST);

if ($itemTmpCartID > 0 AND $qntyTmpCart > 0) {
    $sqlGetTmpCartItem = "SELECT EITC_id,EITC_unit_price,EITC_unit_discount FROM event_item_temp_cart WHERE EITC_id=$itemTmpCartID";
    $resultGetTmpCartItem = mysqli_query($con, $sqlGetTmpCartItem);
    if ($resultGetTmpCartItem) {
        $resultGetTmpCartItemObj = mysqli_fetch_object($resultGetTmpCartItem);
        if (isset($resultGetTmpCartItemObj->EITC_id)) {
            $unitPrice = $resultGetTmpCartItemObj->EITC_unit_price;
            $unitDiscount = $resultGetTmpCartItemObj->EITC_unit_discount;

            if ($unitPrice > 0) {
                $newTotalPrice = $unitPrice * $qntyTmpCart;
                $newDiscountPrice = $unitDiscount * $qntyTmpCart;

                $updateTmpCartItem = '';
                $updateTmpCartItem .=' EITC_quantity = "' . intval($qntyTmpCart) . '"';
                $updateTmpCartItem .=', EITC_total_price = "' . floatval($newTotalPrice) . '"';
                $updateTmpCartItem .=', EITC_total_discount = "' . floatval($newDiscountPrice) . '"';

                $sqlUpdateTmpCartItem = "UPDATE event_item_temp_cart SET $updateTmpCartItem WHERE EITC_id=$itemTmpCartID";
                $resultUpdateTmpCartItem = mysqli_query($con, $sqlUpdateTmpCartItem);

                if ($resultUpdateTmpCartItem) {

                    $sqlGetCartSum = "SELECT SUM(EITC_total_price) AS TotalPrice, SUM(EITC_total_discount) AS TotalDiscount FROM event_item_temp_cart WHERE EITC_session_id='$sessionID'";
                    $resultGetCartSum = mysqli_query($con, $sqlGetCartSum);
                    if ($resultGetCartSum) {
                        $resultGetCartSumObj = mysqli_fetch_object($resultGetCartSum);
                        if (isset($resultGetCartSumObj->TotalPrice)) {
                            $return_array = array("output" => "success", 
                                                "msg" => "Quantity & Price updated successfully.", 
                                                "itemTotalPrice" => number_format($newTotalPrice, 2),
                                                "totalPrice" => number_format(($resultGetCartSumObj->TotalPrice + $resultGetCartSumObj->TotalDiscount),2),
                                                "totalDiscount" => number_format($resultGetCartSumObj->TotalDiscount,2),
                                                "subTotal" => number_format($resultGetCartSumObj->TotalPrice,2));
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
        }
    } else {
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "resultGetTmpCartItem error: " . mysqli_error($con));
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "error", "msg" => "resultGetTmpCartItem query failed.");
            echo json_encode($return_array);
            exit();
        }
    }
}
