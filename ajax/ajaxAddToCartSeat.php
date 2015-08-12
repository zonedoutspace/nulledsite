<?php

include '../config/config.php';

$coordinateId = 0;
$seatId = 0;
$placeId = 0;
$eventId = 0;
$venueId = 0;
$userID = 0;

$type = "seat";
$sessionID = session_id();
if ((getSession('user_id'))) {
    $userID = getSession('user_id');
}

extract($_POST);
$checkStatus = 0;

if ($coordinateId > 0 AND $seatId > 0 AND $placeId > 0 AND $eventId > 0 AND $venueId > 0) {
    //checking if event already saved in temp cart
    $sqlSearchEvent = "SELECT ETC_id FROM event_temp_cart WHERE ETC_event_id=$eventId AND ETC_session_id='$sessionID'";
    $resultSearchEvent = mysqli_query($con, $sqlSearchEvent);
    if ($resultSearchEvent) {
        $countRecord = mysqli_num_rows($resultSearchEvent);

        if ($countRecord == 0) { //event not saved in temp cart
            $insertEventTmpCart = '';
            $insertEventTmpCart .=' ETC_event_id = "' . intval($eventId) . '"';
            $insertEventTmpCart .=', ETC_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
            $insertEventTmpCart .=', ETC_user_id = "' . intval($userID) . '"';

            $sqlInsertEventTmpCart = "INSERT INTO event_temp_cart SET $insertEventTmpCart";
            $resultInsertEventTmpCart = mysqli_query($con, $sqlInsertEventTmpCart);

            if ($resultInsertEventTmpCart) {
                $ETC_id = mysqli_insert_id($con);
            } else {
                $checkStatus++;
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultInsertEventTmpCart error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultInsertEventTmpCart query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        } else { //event already exist in temp cart
            $resultSearchEventObj = mysqli_fetch_object($resultSearchEvent);
            if (isset($resultSearchEventObj->ETC_id)) {
                $ETC_id = $resultSearchEventObj->ETC_id;
            }
        }
    }


    $sqlSearchEventItem = "SELECT EITC_id FROM event_item_temp_cart WHERE EITC_venue_id=$venueId AND EITC_item_id=$coordinateId AND EITC_session_id='$sessionID' AND EITC_item_type='$type'";
    $resultSearchEventItem = mysqli_query($con, $sqlSearchEventItem);

    if ($resultSearchEventItem) {
        $countItems = mysqli_num_rows($resultSearchEventItem);

        //getting ticket information from database
        $seatPrice = 0;
        $seatDiscount = 0;
        $totalSeatPrice = 0;
        $totalSeatDiscount = 0;
        $sqlGetSeat = "SELECT * FROM event_seat_plan WHERE ESP_event_id=$eventId AND ESP_venue_id=$venueId AND ESP_place_id=$placeId AND ESP_template_id=$coordinateId";
        $resultGetItem = mysqli_query($con, $sqlGetSeat);

        if ($resultGetItem) {
            $resultGetItemObj = mysqli_fetch_object($resultGetItem);
            if (isset($resultGetItemObj->ESP_event_id)) {
                //getting item price and discount from databases
                $seatPrice = $resultGetItemObj->ESP_ticket_price;
                if ($resultGetItemObj->ESP_ticket_discount > 0) {
                    $seatDiscount = ($resultGetItemObj->ESP_ticket_discount);
                }
            }
        } else {
            $checkStatus++;
            if (DEBUG) {
                $return_array = array("output" => "error", "msg" => "resultGetItem error: " . mysqli_error($con));
                echo json_encode($return_array);
                exit();
            } else {
                $return_array = array("output" => "error", "msg" => "resultGetItem query failed.");
                echo json_encode($return_array);
                exit();
            }
        }


        $EITC_id = 0;
        if ($countItems > 0) {
//            $totalSeatPrice = $seatPrice * $quantity;
//            $totalSeatDiscount = $seatDiscount * $quantity;
            //item already exist in database, need to update
            $updateItemTmpCart = '';
            $updateItemTmpCart .=' EITC_quantity = EITC_quantity+1';
            $updateItemTmpCart .=', EITC_total_price = EITC_total_price+"' . floatval($seatPrice) . '"';
            $updateItemTmpCart .=', EITC_total_discount = EITC_total_discount+"' . floatval($seatDiscount) . '"';

            $sqlUpdateItemTmpCart = "UPDATE event_item_temp_cart SET $updateItemTmpCart WHERE EITC_venue_id=$venueId AND EITC_item_id=$coordinateId AND EITC_session_id='$sessionID' AND EITC_item_type='$type'";
            $resultUpdateItemTmpCart = mysqli_query($con, $sqlUpdateItemTmpCart);

            if (!$resultUpdateItemTmpCart) {
                $checkStatus++;
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultUpdateItemTmpCart error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultUpdateItemTmpCart query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            } else {
                $sqlGetEITC = "SELECT EITC_id from event_item_temp_cart WHERE EITC_venue_id=$venueId AND EITC_item_id=$coordinateId AND EITC_session_id='$sessionID' AND EITC_item_type='$type'";
                $resultGetEITC = mysqli_query($con, $sqlGetEITC);
                if ($resultGetEITC) {
                    $resultGetEITCObj = mysqli_fetch_object($resultGetEITC);
                    $EITC_id = $resultGetEITCObj->EITC_id;
                } else {
                    if (DEBUG) {
                        $return_array = array("output" => "error", "msg" => "resultGetEITC error: " . mysqli_error($con));
                        echo json_encode($return_array);
                        exit();
                    } else {
                        $return_array = array("output" => "error", "msg" => "resultGetEITC query failed.");
                        echo json_encode($return_array);
                        exit();
                    }
                }
            }
        } else {
            //item does not exist in database, need to insert
            $insertItemTmpCart = '';
            $insertItemTmpCart .=' EITC_ETC_id = "' . intval($ETC_id) . '"';
            $insertItemTmpCart .=', EITC_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
            $insertItemTmpCart .=', EITC_item_type = "' . mysqli_real_escape_string($con, $type) . '"';
            $insertItemTmpCart .=', EITC_venue_id = "' . intval($venueId) . '"';
            $insertItemTmpCart .=', EITC_item_id = "' . intval($coordinateId) . '"';
            $insertItemTmpCart .=', EITC_quantity = "' . intval(1) . '"';
            $insertItemTmpCart .=', EITC_unit_price = "' . floatval($seatPrice) . '"';
            $insertItemTmpCart .=', EITC_unit_discount = "' . floatval($seatDiscount) . '"';
            $insertItemTmpCart .=', EITC_total_price = "' . floatval($seatPrice) . '"';
            $insertItemTmpCart .=', EITC_total_discount = "' . floatval($seatDiscount) . '"';

            $sqlInsertItemTmpCart = "INSERT INTO event_item_temp_cart SET $insertItemTmpCart";
            $resultInsertItemTmpCart = mysqli_query($con, $sqlInsertItemTmpCart);

            if (!$resultInsertItemTmpCart) {
                $checkStatus++;
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultInsertItemTmpCart error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultInsertItemTmpCart query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            } else {
                $EITC_id = mysqli_insert_id($con);
            }
        }


        if ($EITC_id > 0) {
            $insertSeatTempCart = '';
            $insertSeatTempCart .=' EISTC_EITC_id = "' . intval($EITC_id) . '"';
            $insertSeatTempCart .=', EISTC_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
            $insertSeatTempCart .=', EISTC_event_id = "' . intval($eventId) . '"';
            $insertSeatTempCart .=', EISTC_venue_id = "' . intval($venueId) . '"';
            $insertSeatTempCart .=', EISTC_place_id = "' . intval($placeId) . '"';
            $insertSeatTempCart .=', EISTC_coordinate_id = "' . intval($coordinateId) . '"';
            $insertSeatTempCart .=', EISTC_seat_number = "' . intval($seatId) . '"';

            $sqlInsertSeatTmpCart = "INSERT INTO event_item_seat_temp_cart SET $insertSeatTempCart";
            $resultInsertSeatTmpCart = mysqli_query($con, $sqlInsertSeatTmpCart);

            if (!$resultInsertSeatTmpCart) {
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultGetEITC error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultGetEITC query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        }
    }






    /*     * ****************************************Temp Cart Generation********************************************* */

    $arrTmpCartEvent = array();
    $sqlGetTmpCartEvent = "SELECT event_title,event_web_logo,ETC_id FROM event_temp_cart "
            . "LEFT JOIN events ON event_id=ETC_event_id "
            . "WHERE ETC_session_id='$sessionID'";
    $resultGetTmpCartEvent = mysqli_query($con, $sqlGetTmpCartEvent);
    if ($resultGetTmpCartEvent) {
        while ($resultGetTmpCartEventObj = mysqli_fetch_object($resultGetTmpCartEvent)) {
            $arrTmpCartEvent[] = $resultGetTmpCartEventObj;
        }
    } else {
        $checkStatus++;
        if (DEBUG) {
            echo "resultGetTmpCartEventObj error: " . mysqli_error($con);
        } else {
            echo "resultGetTmpCartEventObj query failed.";
        }
    }

    $arrTmpCartItem = array();
    $strTicketID = '';
    $strIncludeID = '';
    $strSeatID = '';
    $totalCartAmount = 0;
    $sqlGetTmpCartItem = "SELECT * FROM event_item_temp_cart "
            . "WHERE EITC_session_id='$sessionID'";
    $resultGetTmpCartItem = mysqli_query($con, $sqlGetTmpCartItem);
    if ($resultGetTmpCartItem) {
        while ($resultGetTmpCartItemObj = mysqli_fetch_object($resultGetTmpCartItem)) {
            $arrTmpCartItem[$resultGetTmpCartItemObj->EITC_ETC_id][] = $resultGetTmpCartItemObj;
            $totalCartAmount += $resultGetTmpCartItemObj->EITC_total_price;
            if ($resultGetTmpCartItemObj->EITC_item_type == 'ticket') {
                $strTicketID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
            } elseif ($resultGetTmpCartItemObj->EITC_item_type == 'include') {
                $strIncludeID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
            } elseif ($resultGetTmpCartItemObj->EITC_item_type == 'seat') {
                $strSeatID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
            }
        }
    } else {
        $checkStatus++;
        if (DEBUG) {
            echo "resultGetTmpCartItem error: " . mysqli_error($con);
        } else {
            echo "resultGetTmpCartItem query failed.";
        }
    }

    $strTicketID = trim($strTicketID, ',');
    $strIncludeID = trim($strIncludeID, ',');
    $strSeatID = trim($strSeatID, ',');

    $arrSelectedTicket = array();
    if ($strTicketID != "") {

        $sqlGetSelectedTicket = "SELECT * FROM event_ticket_types WHERE TT_id IN ($strTicketID)";
        $resultGetSelectedTicket = mysqli_query($con, $sqlGetSelectedTicket);
        if ($resultGetSelectedTicket) {
            while ($resultGetSelectedTicketObj = mysqli_fetch_object($resultGetSelectedTicket)) {
                $arrSelectedTicket[$resultGetSelectedTicketObj->TT_id] = $resultGetSelectedTicketObj;
            }
        } else {
            $checkStatus++;
            if (DEBUG) {
                echo "resultGetSelectedTicket error: " . mysqli_error($con);
            } else {
                echo "resultGetSelectedTicket query failed.";
            }
        }
    }

    $arrSelectedInclude = array();
    if ($strIncludeID != "") {

        $sqlGetSelectedInclude = "SELECT * FROM event_includes WHERE EI_id IN ($strIncludeID)";
        $resultGetSelectedInclude = mysqli_query($con, $sqlGetSelectedInclude);
        if ($resultGetSelectedInclude) {
            while ($resultGetSelectedIncludeObj = mysqli_fetch_object($resultGetSelectedInclude)) {
                $arrSelectedInclude[$resultGetSelectedIncludeObj->EI_id] = $resultGetSelectedIncludeObj;
            }
        } else {
            $checkStatus++;
            if (DEBUG) {
                echo "resultGetSelectedInclude error: " . mysqli_error($con);
            } else {
                echo "resultGetSelectedInclude query failed.";
            }
        }
    }

    $arrSelectedSeat = array();
    if ($strSeatID != "") {

        $sqlGetSelectedSeat = "SELECT * FROM seat_place_coordinate WHERE SPC_id IN ($strSeatID)";
        $resultGetSelectedSeat = mysqli_query($con, $sqlGetSelectedSeat);
        if ($resultGetSelectedSeat) {
            while ($resultGetSelectedSeatObj = mysqli_fetch_object($resultGetSelectedSeat)) {
                $arrSelectedSeat[$resultGetSelectedSeatObj->SPC_id] = $resultGetSelectedSeatObj;
            }
        } else {
            $checkStatus++;
            if (DEBUG) {
                echo "resultGetSelectedSeat error: " . mysqli_error($con);
            } else {
                echo "resultGetSelectedSeat query failed.";
            }
        }
    }
    /*     * ****************************************Temp Cart Generation********************************************* */

    if ($checkStatus == 0) {
        $return_array = array("output" => "success",
            "msg" => "Cart successfully updated.",
            "arrTmpCartEvent" => $arrTmpCartEvent,
            "arrTmpCartItem" => $arrTmpCartItem,
            "arrSelectedTicket" => $arrSelectedTicket,
            "arrSelectedInclude" => $arrSelectedInclude,
            "arrSelectedSeat" => $arrSelectedSeat,
            "totalCartAmount" => number_format($totalCartAmount, 2));
    } else {
        $return_array = array("output" => "error", "msg" => "Add to cart process failed.");
    }
    echo json_encode($return_array);
}