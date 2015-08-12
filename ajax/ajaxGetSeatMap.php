<?php

include '../config/config.php';

$coordinateId = 0;
$placeId = 0;
$eventId = 0;
$venueId = 0;
$mapRow = 0;
$mapColumn = 0;
$arrDefaultSeatMap = array();
$arrSelectedSeatMap = array();
$ticketPrice = 0;
$sessionId = session_id();
$strBookedSeats = "";
extract($_POST);

if ($eventId > 0 AND $venueId > 0 AND $coordinateId > 0 AND $placeId > 0) {
    $sqlGetSeatMap = "SELECT * FROM event_seat_plan,seat_template"
            . " WHERE ESP_event_id=$eventId"
            . " AND ESP_venue_id=$venueId"
            . " AND ESP_place_id=$placeId"
            . " AND ESP_template_id=$coordinateId"
            . " AND ST_SP_id=$placeId"
            . " AND ST_SPC_id=$coordinateId";
    $resultGetSeatMap = mysqli_query($con, $sqlGetSeatMap);
    if ($resultGetSeatMap) {
        $resultGetSeatMapObj = mysqli_fetch_object($resultGetSeatMap);
        if (isset($resultGetSeatMapObj->ESP_event_id)) {
            $mapRow = $resultGetSeatMapObj->ST_row_count;
            $mapColumn = $resultGetSeatMapObj->ST_column_count;
            $arrDefaultSeatMap = unserialize($resultGetSeatMapObj->ST_template_array);
            $arrSelectedSeatMap = unserialize($resultGetSeatMapObj->ESP_seats_available_array);
            $ticketPrice = $resultGetSeatMapObj->ESP_ticket_price;
            $templateUserLimit = $resultGetSeatMapObj->ST_user_limit;
            $strBookedSeats = $resultGetSeatMapObj->ESP_seats_booked;


            $arrSelectedSeat = array();
            $sqlGetSelectedSeat = "SELECT * FROM event_item_seat_temp_cart "
                    . "WHERE EISTC_session_id = '$sessionId'"
                    . "AND EISTC_event_id = $eventId "
                    . "AND EISTC_venue_id = $venueId "
                    . "AND EISTC_place_id = $placeId "
                    . "AND EISTC_coordinate_id = $coordinateId";
            $resultGetSelectedSeat = mysqli_query($con, $sqlGetSelectedSeat);
            if ($resultGetSelectedSeat) {
                while($resultGetSelectedSeatObj = mysqli_fetch_object($resultGetSelectedSeat)){
                    $arrSelectedSeat[] = $resultGetSelectedSeatObj->EISTC_coordinate_id . "-" . $resultGetSelectedSeatObj->EISTC_seat_number;
                }
            } else {
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultGetSelectedSeat error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultGetSelectedSeat query failed");
                    echo json_encode($return_array);
                    exit();
                }
            }
        }
        $return_array = array("output" => "success",
            "mapRow" => $mapRow,
            "mapColumn" => $mapColumn,
            "arrDefaultSeatMap" => $arrDefaultSeatMap,
            "arrSelectedSeatMap" => $arrSelectedSeatMap,
            "ticketPrice" => $ticketPrice,
            "arrSelectedSeat" => $arrSelectedSeat,
            "templateUserLimit" => $templateUserLimit,
            "BookedSeat" => $strBookedSeats);
        echo json_encode($return_array);
        exit();
    } else {
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "resultGetSeatMap error: " . mysqli_error($con));
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "error", "msg" => "resultGetSeatMap query failed");
            echo json_encode($return_array);
            exit();
        }
    }
}