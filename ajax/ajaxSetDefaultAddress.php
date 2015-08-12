<?php

include '../config/config.php';
$addressID = 0;
$type = '';
$userID = 0;

if (checkUserLogin()) {
    $userID = getSession('user_id');
}

extract($_POST);

if ($addressID > 0 AND ! empty($type)) {
    if ($type == "shipping") {
        $sqlUpdateDefaultShipping = "UPDATE users SET user_default_shipping=$addressID WHERE user_id=$userID";
        $resultUpdateDefaultShipping = mysqli_query($con, $sqlUpdateDefaultShipping);
        if ($resultUpdateDefaultShipping) {
            $return_array = array("output" => "success", "msg" => "Success!! Selected address is now your default delivery address.");
            echo json_encode($return_array);
            exit();
        } else {
            if (DEBUG) {
                $err = "resultUpdateDefaultShipping error: " . mysqli_error($con);
                $return_array = array("output" => "success", "msg" => $err);
                echo json_encode($return_array);
                exit();
            } else {
                $err = "resultUpdateDefaultShipping query failed";
                $return_array = array("output" => "success", "msg" => $err);
                echo json_encode($return_array);
                exit();
            }
        }
    } elseif($type == "billing"){
        $sqlUpdateDefaultShipping = "UPDATE users SET user_default_billing=$addressID WHERE user_id=$userID";
        $resultUpdateDefaultShipping = mysqli_query($con, $sqlUpdateDefaultShipping);
        if ($resultUpdateDefaultShipping) {
            $return_array = array("output" => "success", "msg" => "Success!! Selected address is now your default billing address.");
            echo json_encode($return_array);
            exit();
        } else {
            if (DEBUG) {
                $err = "resultUpdateDefaultShipping error: " . mysqli_error($con);
                $return_array = array("output" => "success", "msg" => $err);
                echo json_encode($return_array);
                exit();
            } else {
                $err = "resultUpdateDefaultShipping query failed";
                $return_array = array("output" => "success", "msg" => $err);
                echo json_encode($return_array);
                exit();
            }
        }
    }
}