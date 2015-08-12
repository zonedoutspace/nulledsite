<?php

include '../config/config.php';
$return_array = array();
extract($_POST);
//debug($_POST);
$UA_id = $addressID;
//debug($UA_id);

$deleteUserAddressSql = "DELETE FROM user_addresses WHERE UA_id = $UA_id";
$deleteSqlResult = mysqli_query($con, $deleteUserAddressSql);
if ($deleteSqlResult) {
    $return_array = array("output" => "success", "msg" => "User address deleted successfully");
    echo json_encode($return_array);
    exit();
} else {
    if (DEBUG) {
        $err = "deleteSqlResult error: " . mysqli_error($con);
        $return_array = array("output" => "error", "msg" => $err);
        echo json_encode($return_array);
        exit();
    } else {
        $err = "deleteSqlResult query failed";
        $return_array = array("output" => "error", "msg" => $err);
        echo json_encode($return_array);
        exit();
    }
}
?>