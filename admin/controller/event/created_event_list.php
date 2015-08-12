<?php

include '../../../config/config.php';

header("Content-type: application/json");
$verb = $_SERVER["REQUEST_METHOD"];
if ($verb == "GET") {
    $event_id = $_GET["event_id"];
    $arr = array();
    $get_sql = "SELECT `events`.*, categories.* FROM `events` LEFT JOIN categories ON `events`.event_category_id = categories.category_id WHERE `events`.event_status NOT IN ('delete')  AND `events`.event_id = '$event_id' ORDER BY event_updated_on DESC";
    $resultEventList = mysqli_query($con, $get_sql);
    if ($resultEventList) {
        while ($obj = mysqli_fetch_object($resultEventList)) {
            $arr[] = $obj;
        }
    } else {
        if (DEBUG) {
            $err = "resultEventList error: " . mysqli_error($con);
        } else {
            $err = "resultEventList query failed";
        }
    }
    echo "{\"data\":" . json_encode($arr) . "}";
}


if ($verb == "POST") {
    extract($_POST);
    $event_id = mysqli_real_escape_string($con, $event_id);
    $delete_sql = "UPDATE events SET event_status = 'delete' WHERE event_id = '" . $event_id . "'";
    $resultDeleteEvent = mysqli_query($con, $delete_sql);
    if ($resultDeleteEvent) {
        echo json_encode($resultDeleteEvent);
    } else {
        if (DEBUG) {
            $err = "resultDeleteEvent error: " . mysqli_error($con);
        } else {
            $err = "resultDeleteEvent query failed";
        }
    }
}
?>
