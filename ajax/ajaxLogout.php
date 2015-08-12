<?php

include '../config/config.php';
$return_array = array();
$status = 0;
$sessionID = session_id();

if (UserLogout()) {
    //deleting all data in temp cart
    $sqlDeleteTmpEvent = "DELETE FROM event_temp_cart WHERE ETC_session_id='$sessionID'";
    $resultDeleteTmpEvent = mysqli_query($con, $sqlDeleteTmpEvent);
    if (!$resultDeleteTmpEvent) {
        $status++;
    }

    //deleting all data in temp cart
    $sqlDeleteTmpItems = "DELETE FROM event_item_temp_cart WHERE EITC_session_id='$sessionID'";
    $resultDeleteTmpItems = mysqli_query($con, $sqlDeleteTmpItems);
    if (!$resultDeleteTmpItems) {
        $status++;
    }

    if ($status > 0) {
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "Temp cart clearing query failed.");
            echo json_encode($return_array);
            exit();
        }
    } else {
        $return_array = array("output" => "success", "msg" => "Logged out successfully");
        echo json_encode($return_array);
        exit();
    }
} else {
    $return_array = array("output" => "error", "msg" => "Login first");
    echo json_encode($return_array);
    exit();
}
?>