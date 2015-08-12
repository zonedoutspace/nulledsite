<?php

include '../config/config.php';

$WL_product_id = "";
$WL_product_type = "";

$userID = 0;
if (checkUserLogin()) {
    $userID = getSession('user_id');
}

extract($_POST);


if (checkUserLogin()) {
    if ($WL_product_id > 0 AND $WL_product_type != "") {

        // check availability for wishlist
        $sqlCheckProduct = "SELECT * FROM wishlists WHERE WL_product_id=$WL_product_id AND WL_product_type='$WL_product_type' AND WL_user_id=$userID";
        $resultCheckProduct = mysqli_query($con, $sqlCheckProduct);

        if (mysqli_num_rows($resultCheckProduct) > 0) {
           
            $return_array = array("output" => "info", "msg" => "Already added to wishlist.");
            echo json_encode($return_array);
            exit();
        } else {
            
            $insertWishlist = "";
            $insertWishlist .=' WL_product_id = "' . intval($WL_product_id) . '"';
            $insertWishlist .=', WL_product_type = "' . mysqli_real_escape_string($con, $WL_product_type) . '"';
            $insertWishlist .=', WL_user_id = "' . intval($userID) . '"';

            $sqlInsertWishlist = "INSERT INTO wishlists SET $insertWishlist";
            $resultInsertWishlist = mysqli_query($con, $sqlInsertWishlist);

            if ($resultInsertWishlist) {
                $return_array = array("output" => "success", "msg" => "Added to wishlist successfully.");
                echo json_encode($return_array);
                exit();
            } else {
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultInsertWishlist error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultInsertWishlist query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        }
    }
} else {
    $return_array = array("output" => "login", "msg" => "login");
    echo json_encode($return_array);
    exit();
}