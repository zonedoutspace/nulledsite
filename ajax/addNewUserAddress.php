<?php

include '../config/config.php';
$return_array = array();
$UA_title = "";
$UA_first_name = "";
$UA_last_name = "";
$UA_phone = "";
$UA_address = "";
$UA_zip = "";
$UA_city_id = "";
$UA_country_id = "";
$UA_user_id = "";

extract($_POST);

$UA_title = validateInput($UA_title);
$UA_phone = validateInput($UA_phone);
$UA_address = validateInput($UA_address);
$UA_zip = validateInput($UA_zip);
$UA_city_id = validateInput($UA_city_id);
$UA_country_id = validateInput($UA_country_id);
$UA_user_id = validateInput($UA_user_id);


if ($UA_address != "") {

    $addUserAddressArray = '';
    $addUserAddressArray .=' UA_user_id = "' . $UA_user_id . '"';
    $addUserAddressArray .=', UA_first_name = "' . $UA_first_name . '"';
    $addUserAddressArray .=', UA_last_name = "' . $UA_last_name . '"';
    $addUserAddressArray .=', UA_phone = "' . $UA_phone . '"';
    $addUserAddressArray .=', UA_address = "' . $UA_address . '"';
    $addUserAddressArray .=', UA_zip = "' . $UA_zip . '"';
    $addUserAddressArray .=', UA_city_id = "' . $UA_city_id . '"';
    $addUserAddressArray .=', UA_country_id = "' . $UA_country_id . '"';

    $sqlAddUserAddress = "INSERT INTO user_addresses SET $addUserAddressArray";
    $resultUserAddress = mysqli_query($con, $sqlAddUserAddress);
    if ($resultUserAddress) {
        $return_array = array("output" => "success", "msg" => "User address saved successfully");
        echo json_encode($return_array);
        exit();
    } else {
        if (DEBUG) {
            $err = "resultUserAddress error: " . mysqli_error($con);
        } else {
            $err = "resultUserAddress query failed";
        }
    }
} else {
    $return_array = array("output" => "error", "msg" => "User address not saved");
    echo json_encode($return_array);
    exit();
}
?>

