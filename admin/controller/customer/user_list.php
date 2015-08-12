<?php

include '../../../config/config.php';

header("Content-type: application/json");
$verb = $_SERVER["REQUEST_METHOD"];
if ($verb == "GET") {
    $arrayUser = array();
    $sqlUser = "SELECT * FROM users ORDER BY user_id DESC";
    $resultUser = mysqli_query($con, $sqlUser);
    if ($resultUser) {
        while ($resultUserObj = mysqli_fetch_object($resultUser)) {
            $arrayUser[] = $resultUserObj;
        }
    } else {
        if (DEBUG) {
            $err = "resultUser error: " . mysqli_error($con);
        } else {
            $err = "resultUser query failed";
        }
    }
    echo "{\"data\":" . json_encode($arrayUser) . "}";
}
?>