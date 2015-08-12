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
$UA_id = "";

extract($_POST);

$UA_phone = validateInput($UA_phone);
$UA_address = validateInput($UA_address);
$UA_zip = validateInput($UA_zip);
$UA_city_id = validateInput($UA_city_id);
$UA_country_id = validateInput($UA_country_id);
$UA_user_id = validateInput($UA_user_id);
$UA_id = validateInput($UA_id);


if ($UA_address != "") {

    $updateUserAddressArray = '';
    $updateUserAddressArray .=' UA_user_id = "' . $UA_user_id . '"';
    $updateUserAddressArray .=', UA_first_name = "' . $UA_first_name . '"';
    $updateUserAddressArray .=', UA_last_name = "' . $UA_last_name . '"';
    $updateUserAddressArray .=', UA_phone = "' . $UA_phone . '"';
    $updateUserAddressArray .=', UA_address = "' . $UA_address . '"';
    $updateUserAddressArray .=', UA_zip = "' . $UA_zip . '"';
    $updateUserAddressArray .=', UA_city_id = "' . $UA_city_id . '"';
    $updateUserAddressArray .=', UA_country_id = "' . $UA_country_id . '"';

    $sqlUpdateUserAddress = "UPDATE user_addresses SET $updateUserAddressArray WHERE UA_id = $UA_id";
    $resultUserAddressUpdate = mysqli_query($con, $sqlUpdateUserAddress);
    if ($resultUserAddressUpdate) {
        $return_array = array("output" => "success", "msg" => "User address updated successfully");
        echo json_encode($return_array);
        exit();
    } else {
        if (DEBUG) {
            $err = "resultUserAddressUpdate error: " . mysqli_error($con);
        } else {
            $err = "resultUserAddressUpdate query failed";
        }
    }
} else {
    $return_array = array("output" => "error", "msg" => "User address not updated");
    echo json_encode($return_array);
    exit();
}
?>

