<?php

include '../config/config.php';

$eventId = 0;
$venueId = 0;
$arrGetPlaceCoordinates = array();
$imgPlace = "";
$strPlaceTitle = "";
$placeId = 0;
$SP_height = '';
$SP_width = '';
extract($_POST);

if ($eventId > 0 AND $venueId > 0) {
    $sqlQuery = "SELECT * "
            . "FROM event_seat_plan "
            . "LEFT JOIN seat_place ON seat_place.SP_id = event_seat_plan.ESP_place_id "
            . "WHERE ESP_event_id=$eventId "
            . "AND ESP_venue_id=$venueId";
    $resultGetPlace = mysqli_query($con, $sqlQuery);
    if ($resultGetPlace) {
        $resultGetPlaceObj = mysqli_fetch_object($resultGetPlace);
        if (isset($resultGetPlaceObj->ESP_event_id)) {
            $imgPlace = $resultGetPlaceObj->SP_image;
            $strPlaceTitle = $resultGetPlaceObj->SP_title;
            $placeId = $resultGetPlaceObj->SP_id;
            $SP_height = $resultGetPlaceObj->SP_height;
            $SP_width = $resultGetPlaceObj->SP_width;
        }

        $sqlGetCoordinates = "SELECT * FROM seat_place_coordinate "
                . "WHERE SPC_SP_id=$placeId";
        $resultGetCoordinates = mysqli_query($con, $sqlGetCoordinates);
        if ($resultGetCoordinates) {
            while ($resultGetCoordinatesObj = mysqli_fetch_object($resultGetCoordinates)) {
                $arrGetPlaceCoordinates[] = $resultGetCoordinatesObj;
            }

            $return_array = array("output" => "success", 
                                "objCoordinates" => $arrGetPlaceCoordinates,
                                "strPlaceTitle" => $strPlaceTitle,
                                "imgPlace" => $imgPlace,
                                "height" => $SP_height,
                                "width" => $SP_width);
            echo json_encode($return_array);
            exit();
        } else {
            if (DEBUG) {
                $return_array = array("output" => "error", "msg" => "resultGetCoordinates error: " . mysqli_error($con));
                echo json_encode($return_array);
                exit();
            } else {
                $return_array = array("output" => "error", "msg" => "resultGetCoordinates query failed");
                echo json_encode($return_array);
                exit();
            }
        }
    } else {
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "resultGetPlace error: " . mysqli_error($con));
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "error", "msg" => "resultGetPlace query failed");
            echo json_encode($return_array);
            exit();
        }
    }
}