<?php

include '../config/config.php';

$WL_id = 0;

$userID = 0;
if (checkUserLogin()) {
    $userID = getSession('user_id');
}

extract($_POST);

if ($userID > 0 AND $WL_id > 0) {
    $sqlDeleteWishlist = "DELETE FROM wishlists WHERE WL_id=$WL_id AND WL_user_id=$userID";
    $resultDeleteWishlist = mysqli_query($con, $sqlDeleteWishlist);

    if ($resultDeleteWishlist) {
        //checking remaining list
        $countRemain = 0;
        $sqlCheck = "SELECT WL_id FROM wishlists WHERE WL_user_id=$userID";
        $resultCheck = mysqli_query($con, $sqlCheck);
        if ($resultCheck) {
            $countRemain = mysqli_num_rows($resultCheck);
            $return_array = array("output" => "success", "msg" => "Product deleted successfully from your favorite list.", "count" => $countRemain);
            echo json_encode($return_array);
            exit();
        } else {
            if (DEBUG) {
                $return_array = array("output" => "error", "msg" => "resultCheck error: " . mysqli_error($con));
                echo json_encode($return_array);
                exit();
            } else {
                $return_array = array("output" => "error", "msg" => "resultCheck query failed.");
                echo json_encode($return_array);
                exit();
            }
        }
    } else {
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "resultDeleteWishlist error: " . mysqli_error($con));
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "error", "msg" => "resultDeleteWishlist query failed.");
            echo json_encode($return_array);
            exit();
        }
    }
}