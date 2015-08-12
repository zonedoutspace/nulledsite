<?php

include '../config/config.php';
$return_array = array();
$countCartItem = 0;
extract($_POST);
//debug($_POST);
$delCartID = intval($deleteItemID);
$delEventItemCartID = intval($deleteItemEventID);

$delCartSession = session_id();

$deleteItemCartSql = "DELETE FROM event_item_temp_cart WHERE EITC_id=$delCartID AND EITC_ETC_id=$delEventItemCartID";
$resultdeleteItemCart = mysqli_query($con, $deleteItemCartSql);
if ($resultdeleteItemCart) {

    $checkItemCartSql = "SELECT * FROM event_item_temp_cart WHERE EITC_ETC_id=$delEventItemCartID AND EITC_session_id='$delCartSession'";
    $resultCheckItemCart = mysqli_query($con, $checkItemCartSql);
    if ($resultCheckItemCart) {
        $countCartItem = mysqli_num_rows($resultCheckItemCart);
        if ($countCartItem == 0) {
            $deleteEventItemSql = "DELETE FROM event_temp_cart WHERE ETC_id=$delEventItemCartID";
            $resultDeleteEventItem = mysqli_query($con, $deleteEventItemSql);

            if ($resultDeleteEventItem) {

                $sqlGetCartSum = "SELECT SUM(EITC_total_price) AS TotalPrice, SUM(EITC_total_discount) AS TotalDiscount FROM event_item_temp_cart WHERE EITC_session_id='$delCartSession'";
                $resultGetCartSum = mysqli_query($con, $sqlGetCartSum);

                if ($resultGetCartSum) {
                    $resultGetCartSumObj = mysqli_fetch_object($resultGetCartSum);
                    if ($resultGetCartSumObj->TotalPrice == '') {
                        $return_array = array("output" => "success",
                            "msg" => "Item deleted successfully.",
                            "totalPrice" => number_format(0, 2),
                            "totalDiscount" => number_format(0, 2),
                            "subTotal" => number_format(0, 2));
                        echo json_encode($return_array);
                        exit();
                    } else {
                        $return_array = array("output" => "success",
                            "msg" => "Item deleted successfully.",
                            "totalPrice" => number_format(($resultGetCartSumObj->TotalPrice + $resultGetCartSumObj->TotalDiscount), 2),
                            "totalDiscount" => number_format($resultGetCartSumObj->TotalDiscount, 2),
                            "subTotal" => number_format($resultGetCartSumObj->TotalPrice, 2));
                        echo json_encode($return_array);
                        exit();
                    }
                } else {
                    if (DEBUG) {
                        $err = "resultCheckItemCart error: " . mysqli_error($con);
                        $return_array = array("output" => "error", "msg" => $err);
                        echo json_encode($return_array);
                        exit();
                    } else {
                        $err = "resultCheckItemCart query failed";
                        $return_array = array("output" => "error", "msg" => $err);
                        echo json_encode($return_array);
                        exit();
                    }
                }
            } else {
                if (DEBUG) {
                    $err = "resultDeleteEventItem error: " . mysqli_error($con);
                    $return_array = array("output" => "error", "msg" => $err);
                    echo json_encode($return_array);
                    exit();
                } else {
                    $err = "resultDeleteEventItem query failed";
                    $return_array = array("output" => "error", "msg" => $err);
                    echo json_encode($return_array);
                    exit();
                }
            }
        } else {

            $sqlGetCartSum = "SELECT SUM(EITC_total_price) AS TotalPrice, SUM(EITC_total_discount) AS TotalDiscount FROM event_item_temp_cart WHERE EITC_session_id='$delCartSession'";
            $resultGetCartSum = mysqli_query($con, $sqlGetCartSum);

            if ($resultGetCartSum) {
                $resultGetCartSumObj = mysqli_fetch_object($resultGetCartSum);
                if (isset($resultGetCartSumObj->TotalPrice)) {
                    $return_array = array("output" => "success",
                        "msg" => "Item deleted successfully.",
                        "totalPrice" => number_format(($resultGetCartSumObj->TotalPrice + $resultGetCartSumObj->TotalDiscount), 2),
                        "totalDiscount" => number_format($resultGetCartSumObj->TotalDiscount, 2),
                        "subTotal" => number_format($resultGetCartSumObj->TotalPrice, 2));
                    echo json_encode($return_array);
                    exit();
                }
            } else {
                if (DEBUG) {
                    $err = "resultCheckItemCart error: " . mysqli_error($con);
                    $return_array = array("output" => "error", "msg" => $err);
                    echo json_encode($return_array);
                    exit();
                } else {
                    $err = "resultCheckItemCart query failed";
                    $return_array = array("output" => "error", "msg" => $err);
                    echo json_encode($return_array);
                    exit();
                }
            }
        }
    } else {
        if (DEBUG) {
            $err = "resultCheckItemCart error: " . mysqli_error($con);
            $return_array = array("output" => "error", "msg" => $err);
            echo json_encode($return_array);
            exit();
        } else {
            $err = "resultCheckItemCart query failed";
            $return_array = array("output" => "error", "msg" => $err);
            echo json_encode($return_array);
            exit();
        }
    }
} else {
    if (DEBUG) {
        $err = "resultdeleteItemCart error: " . mysqli_error($con);
        $return_array = array("output" => "error", "msg" => $err);
        echo json_encode($return_array);
        exit();
    } else {
        $err = "resultdeleteItemCart query failed";
        $return_array = array("output" => "error", "msg" => $err);
        echo json_encode($return_array);
        exit();
    }
}
?>