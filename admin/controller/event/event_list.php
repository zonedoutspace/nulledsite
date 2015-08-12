<?php

include '../../../config/config.php';

$adminEventPermission = '';
$adminID = 0;
$adminEventID = '';
if ((getSession('admin_event_permission')) AND ( getSession('admin_id'))) {
    $adminEventPermission = getSession('admin_event_permission');
    $adminID = getSession('admin_id');
    $adminEventID = getSession('admin_event_id');
}

header("Content-type: application/json");
$verb = $_SERVER["REQUEST_METHOD"];
if ($verb == "GET") {
    $arrayEvent = array();
    $get_sql = "SELECT `events`.*, categories.* FROM `events` "
            . "LEFT JOIN categories ON `events`.event_category_id = categories.category_id ";
    if ($adminEventPermission == "created") {
        $get_sql .= "WHERE event_created_by=$adminID ";
    } elseif ($adminEventPermission == "selected") {
        $get_sql .= "WHERE event_id IN ($adminEventID) ";
    }
    $get_sql .= "ORDER BY event_id DESC";
    $resultEventList = mysqli_query($con, $get_sql);
    if ($resultEventList) {
        while ($obj = mysqli_fetch_object($resultEventList)) {
            $arrayEvent[] = $obj;
        }
    } else {
        if (DEBUG) {
            $err = "resultEventList error: " . mysqli_error($con);
        } else {
            $err = "resultEventList query failed";
        }
    }
    echo "{\"data\":" . json_encode($arrayEvent) . "}";
}


if ($verb == "POST") {

    extract($_POST);
    $event_id = mysqli_real_escape_string($con, $event_id);
    $delete_sql = "UPDATE events SET event_status = 'delete' WHERE event_id = '" . $event_id . "'";
    $resultDelete = mysqli_query($con, $delete_sql);

    if ($resultDelete) {
        echo json_encode($resultDelete);
    } else {
        if (DEBUG) {
            $err = "resultDelete error: " . mysqli_error($con);
        } else {
            $err = "resultDelete query failed";
        }
    }
}
?>
